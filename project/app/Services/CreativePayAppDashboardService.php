<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Brand;
use App\Models\ChatbotPackage;
use App\Models\Consultancy;
use App\Models\Contact;
use App\Models\Counter;
use App\Models\Customer;
use App\Models\DomainExtension;
use App\Models\DomainRenewalOrder;
use App\Models\HostingRenewalOrder;
use App\Models\License;
use App\Models\LicenseRenewalOrder;
use App\Models\PaymentTransaction;
use App\Models\Portfolio;
use App\Models\PricingPlan;
use App\Models\Product;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\StolenDomain;
use App\Models\Team;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\UserDomain;
use App\Models\UserHosting;
use App\Models\WhyChooseUs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class CreativePayAppDashboardService
{
    /** @var array<string, array{type: string, target: string}> */
    private const NATIVE_ACTIONS = [
        'admin.consultancy.index'         => ['type' => 'module', 'target' => 'consultancy'],
        'admin.contact.index'             => ['type' => 'module', 'target' => 'contacts'],
        'admin.tickets.index'             => ['type' => 'module', 'target' => 'tickets'],
        'admin.license.index'             => ['type' => 'module', 'target' => 'licenses'],
        'admin.hosting.domains'           => ['type' => 'module', 'target' => 'domain_ext'],
        'admin.user.domains'              => ['type' => 'module', 'target' => 'user_domains'],
        'admin.user.hostings'             => ['type' => 'module', 'target' => 'user_hostings'],
        'admin.stolen.index'              => ['type' => 'screen', 'target' => 'stolen_logs'],
        'admin.payments.index'            => ['type' => 'screen', 'target' => 'invoices'],
        'admin.payments.sms-logs'         => ['type' => 'screen', 'target' => 'sms_logs'],
    ];

    public function build(Admin $admin): array
    {
        $isSuper = (int) $admin->role_id === 1;
        $stats = $this->gatherStats();
        return [
            'is_super' => $isSuper,
            'overview' => $this->buildOverview($stats),
            'counts'   => $this->menuCounts($stats),
        ];
    }

    public function gatherStats(): array
    {
        return [
            'themes'                   => Product::count(),
            'services'                 => Service::count(),
            'portfolios'               => Portfolio::count(),
            'team'                     => Team::count(),
            'customers'                => Customer::count(),
            'brands'                   => Brand::count(),
            'testimonials'             => Testimonial::count(),
            'why_choose'               => WhyChooseUs::count(),
            'pricing'                  => PricingPlan::count(),
            'counters'                 => Counter::count(),
            'social_links'             => SocialLink::count(),
            'licenses'                 => License::count(),
            'expired_licenses'         => License::where('expiry_date', '<', now())->count(),
            'contacts'                 => Contact::count(),
            'consultancy'              => Consultancy::count(),
            'chatbot_packages'         => ChatbotPackage::where('status', true)->count(),
            'total_users'              => User::count(),
            'new_users_30'             => User::where('created_at', '>=', now()->subDays(30))->count(),
            'total_admins'             => Admin::count(),
            'pending_tickets'          => Schema::hasTable('tickets')
                ? (int) DB::table('tickets')->where('status', 'pending')->count()
                : 0,
            'pending_payments'         => PaymentTransaction::where('status', 'pending')->count(),
            'paid_payments'            => PaymentTransaction::where('status', 'completed')->count(),
            'pending_hosting'          => UserHosting::where('status', 'Pending')->count(),
            'pending_license_renewals' => LicenseRenewalOrder::where('status', 'pending')->count(),
            'pending_domain_renewals'  => DomainRenewalOrder::where('status', 'pending')->count(),
            'pending_hosting_renewals' => HostingRenewalOrder::where('status', 'pending')->count(),
            'pending_stolen'           => StolenDomain::where('status', 0)->count(),
        ];
    }

    private function buildOverview(array $s): array
    {
        return [
            ['key' => 'pending_payments', 'label' => 'Pending Payments', 'value' => $s['pending_payments'], 'tone' => 'warning'],
            ['key' => 'paid_payments', 'label' => 'Paid Payments', 'value' => $s['paid_payments'], 'tone' => 'success'],
            ['key' => 'total_users', 'label' => 'Total Users', 'value' => $s['total_users'], 'tone' => 'primary'],
            ['key' => 'pending_actions', 'label' => 'Pending Actions', 'value' => collect($s)->only([
                'pending_tickets', 'pending_payments', 'pending_hosting',
                'pending_license_renewals', 'pending_domain_renewals',
                'pending_hosting_renewals', 'pending_stolen', 'expired_licenses',
            ])->sum(), 'tone' => 'danger'],
        ];
    }

    private function menuCounts(array $s): array
    {
        return [
            'consultancy'             => $s['consultancy'],
            'consultancy_pending'   => Consultancy::where('status', 0)->count(),
            'contacts'              => $s['contacts'],
            'contacts_unread'       => Contact::where('status', 0)->count(),
            'tickets'               => Schema::hasTable('tickets') ? (int) DB::table('tickets')->count() : 0,
            'licenses'              => $s['licenses'],
            'domain_ext'            => DomainExtension::count(),
            'user_domains'          => UserDomain::count(),
            'user_hostings'         => UserHosting::count(),
            'user_hostings_pending' => $s['pending_hosting'],
            'stolen'                => StolenDomain::whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')->from('stolen_domains')->groupBy('domain_name');
            })->count(),
            'stolen_pending'        => $s['pending_stolen'],
            'pending_payments'      => $s['pending_payments'],
            'paid_payments'         => $s['paid_payments'],
        ];
    }

    private function buildQuickLinks(bool $isSuper, array $s): array
    {
        $links = [];

        $add = function (
            string $route,
            string $title,
            string $group,
            ?string $meta = null,
            $badge = null,
            string $badgeType = 'warning'
        ) use (&$links) {
            $links[] = compact('route', 'title', 'group', 'meta', 'badge', 'badgeType');
        };

        if ($isSuper) {
            $add('admin.pos.index', 'POS - New Sale', 'Finance & POS', 'Create a new sale');
            $add('admin.customer.index', 'Customers', 'Finance & POS', number_format($s['customers']) . ' total');
            $add('admin.expense.index', 'Daily Expenses', 'Finance & POS', 'Track daily costs');
            $add('admin.report.fund', 'Fund Statement', 'Finance & POS', 'Income & expense report');
            $add('admin.products.index', 'All Themes', 'Theme Management', number_format($s['themes']) . ' themes');
            $add('admin.products.create', 'Add New Theme', 'Theme Management', 'Upload a new theme');
            $add('admin.hosting.servers', 'WHM Servers', 'Hosting & Domains');
            $add('admin.hosting.plans', 'Hosting Packages', 'Hosting & Domains');
            $add('admin.hosting.domains', 'Domain Pricing', 'Hosting & Domains');
            $add('admin.user.hostings', 'Active Hostings', 'Hosting & Domains', null, $s['pending_hosting'] ?: null, 'danger');
            $add('admin.user.domains', 'Registered Domains', 'Hosting & Domains');
            $add('admin.service.index', 'All Services', 'Our Services', number_format($s['services']) . ' items');
            $add('admin.service.create', 'Add New Service', 'Our Services');
            $add('admin.portfolio-category.index', 'Portfolio Categories', 'Portfolio');
            $add('admin.portfolio.index', 'All Projects', 'Portfolio', number_format($s['portfolios']) . ' projects');
            $add('admin.portfolio.create', 'Add New Project', 'Portfolio');
            $add('admin.team.index', 'All Members', 'Team', number_format($s['team']) . ' members');
            $add('admin.team.create', 'Add New Member', 'Team');
            $add('admin.tickets.index', 'Support Tickets', 'Support', null, $s['pending_tickets'] ?: null, 'danger');
            $add('admin.consultancy.index', 'Free Consultancy', 'Support', number_format($s['consultancy']) . ' requests');
            $add('admin.contact.index', 'Contact Messages', 'Support', number_format($s['contacts']) . ' messages');
            $add('admin.license.index', 'License Management', 'Licenses', number_format($s['licenses']) . ' licenses', $s['expired_licenses'] ?: null, 'warning');
            $add('admin.script-updates.index', 'Script Update Files', 'Licenses');
            $add('admin.license-renewal-orders.index', 'License Renewal Orders', 'Renewals', null, $s['pending_license_renewals'] ?: null, 'warning');
            $add('admin.domain-renewal-orders.index', 'Domain Renewal Orders', 'Renewals', null, $s['pending_domain_renewals'] ?: null, 'warning');
            $add('admin.hosting-renewal-orders.index', 'Hosting Renewal Orders', 'Renewals', null, $s['pending_hosting_renewals'] ?: null, 'warning');
            $add('admin.payments.index', 'CreativePay Payments', 'CreativePay', null, $s['pending_payments'] ?: null, 'warning');
            $add('admin.payments.app-qr', 'App QR Login', 'CreativePay');
            $add('admin.payments.sms-logs', 'Payment SMS Logs', 'CreativePay');
            $add('admin.stolen.index', 'Stolen Reports', 'Security', null, $s['pending_stolen'] ?: null, 'danger');
            $add('admin.sms.settings', 'SMS API Configuration', 'SMS Reseller');
            $add('admin.sms.history', 'All SMS History', 'SMS Reseller');
        }

        $add('admin.chatbot.app-settings.index', 'Facebook App Settings', 'Facebook Chatbot');
        $add('admin.chatbot.packages.index', 'Chatbot Packages', 'Facebook Chatbot', number_format($s['chatbot_packages']) . ' active');
        $add('admin.chatbot.webhook-guide', 'Webhook Setup Guide', 'Facebook Chatbot');

        if ($isSuper) {
            $add('admin.chatbot.pages', 'Connected Pages', 'Facebook Chatbot');
            $add('admin.chatbot.analytics', 'Analytics', 'Facebook Chatbot');
            $add('admin.about.edit', 'Edit About Section', 'Frontend Setup');
            $add('admin.testimonial.index', 'Testimonials', 'Frontend Setup', number_format($s['testimonials']) . ' reviews');
            $add('admin.counter.index', 'Counter Section', 'Frontend Setup', number_format($s['counters']) . ' counters');
            $add('admin.service.edit_text', 'Edit Section Text', 'Frontend Setup');
            $add('admin.brand.index', 'All Brands', 'Frontend Setup', number_format($s['brands']) . ' brands');
            $add('admin.pricing.index', 'All Pricing Plans', 'Frontend Setup', number_format($s['pricing']) . ' plans');
            $add('admin.why-choose-us.index', 'Why Choose Us', 'Frontend Setup', number_format($s['why_choose']) . ' features');
            $add('social.link.index', 'Social Settings', 'Frontend Setup', number_format($s['social_links']) . ' links');
            $add('admin.staff.create', 'Add New User', 'User Management');
            $add('admin.staff.index', 'All Users List', 'User Management');
            $add('admin.role.index', 'Roles & Permissions', 'User Management');
            $add('admin.administator.index', 'Administrators', 'User Management');
            $add('admin.language.index', 'Language', 'Settings');
            $add('admin.admin_language.index', 'Admin Language', 'Settings');
            $add('admin.generalsettings.websiteContent', 'Website Settings', 'Settings');
            $add('admin.generalsettings.logo', 'Logo', 'Settings');
            $add('admin.languagelogo.index', 'Language Base Logo', 'Settings');
            $add('admin.generalsettings.favicon', 'Favicon', 'Settings');
            $add('admin.email.config', 'Email Configurations', 'Settings');
            $add('seo.meta.keywords', 'Website Meta Keywords', 'Settings');
            $add('admin.profile', 'My Profile', 'Settings');
        }

        if (!$isSuper) {
            $add('admin.script-updates.index', 'Script Update Files', 'Licenses');
            $add('admin.license-renewal-orders.index', 'License Renewal Orders', 'Renewals', null, $s['pending_license_renewals'] ?: null, 'warning');
            $add('admin.domain-renewal-orders.index', 'Domain Renewal Orders', 'Renewals', null, $s['pending_domain_renewals'] ?: null, 'warning');
            $add('admin.payments.index', 'CreativePay Payments', 'CreativePay', null, $s['pending_payments'] ?: null, 'warning');
            $add('admin.hosting-renewal-orders.index', 'Hosting Renewal Orders', 'Renewals', null, $s['pending_hosting_renewals'] ?: null, 'warning');
        }

        return $links;
    }

    private function toAppCard(array $link): array
    {
        $route = $link['route'];
        $native = self::NATIVE_ACTIONS[$route] ?? null;
        $webPath = $this->resolveWebPath($route);

        if ($native) {
            $actionType = $native['type'];
            $actionTarget = $native['target'];
        } else {
            $actionType = 'web';
            $actionTarget = ltrim($webPath, '/');
        }

        return [
            'id'            => $route,
            'title'         => $link['title'],
            'meta'          => $link['meta'] ?? '',
            'group'         => $link['group'],
            'badge'         => $link['badge'] ? (int) $link['badge'] : 0,
            'badge_type'    => $link['badgeType'] ?? 'warning',
            'action_type'   => $actionType,
            'action_target' => $actionTarget,
            'web_path'      => ltrim($webPath, '/'),
        ];
    }

    private function resolveWebPath(string $routeName): string
    {
        if ($this->isFraudRelated($routeName) || !Route::has($routeName)) {
            return '/admin/dashboard';
        }

        try {
            return route($routeName, [], false);
        } catch (\Throwable) {
            return '/admin/dashboard';
        }
    }

    private function isFraudRelated(string $value): bool
    {
        $needle = strtolower($value);

        return str_contains($needle, 'fraud');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\PollQuestion;
use App\Models\Post;
use App\Models\Role;
use App\Models\Rss;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use App\Models\StolenDomain;
use App\Models\UnauthorizedLog;
use App\Models\Admin;
use App\Models\User;
use App\Models\Font;
use App\Models\Product;
use App\Models\Service;
use App\Models\Portfolio;
use App\Models\Team;
use App\Models\Customer;
use App\Models\PaymentTransaction;
use App\Models\UserHosting;
use App\Models\License;
use App\Models\LicenseRenewalOrder;
use App\Models\DomainRenewalOrder;
use App\Models\HostingRenewalOrder;
use App\Models\Contact;
use App\Models\Consultancy;
use App\Models\ChatbotPackage;
use App\Models\Brand;
use App\Models\Testimonial;
use App\Models\WhyChooseUs;
use App\Models\PricingPlan;
use App\Models\SocialLink;
use App\Models\Counter;
use App\Models\Fund;
use App\Models\FundTransaction;
use App\Models\SiteVisit;
use App\Services\SiteVisitTracker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('admin.otp')->except(['logout']);
    }
    
    public function index(){
        $data['userRole'] = Role::where('id', '!=', 1)->get();
        $data['subscribers'] = Subscriber::orderBy('id', 'desc')->take(10)->get();
        $data['subscribers_count'] = Subscriber::count();
        $data['total_admins'] = Admin::count();
        $data['total_users'] = User::count();
        $data['new_users_30'] = User::where('created_at', '>=', now()->subDays(30))->count();
        $data['recent_users'] = User::latest()->take(10)->get(['name', 'email', 'created_at']);
        $data['total_fonts'] = Font::count();
        $data['isSuper'] = (bool) Auth::guard('admin')->user()?->IsSuper();

        $data['stats'] = [
            'themes'              => Product::count(),
            'services'            => Service::count(),
            'portfolios'          => Portfolio::count(),
            'team'                => Team::count(),
            'customers'           => Customer::count(),
            'brands'              => Brand::count(),
            'testimonials'        => Testimonial::count(),
            'why_choose'          => WhyChooseUs::count(),
            'pricing'             => PricingPlan::count(),
            'counters'            => Counter::count(),
            'social_links'        => SocialLink::count(),
            'licenses'            => License::count(),
            'expired_licenses'    => License::where('expiry_date', '<', now())->count(),
            'contacts'            => Contact::count(),
            'consultancy'         => Consultancy::count(),
            'chatbot_packages'    => ChatbotPackage::where('status', true)->count(),
            'pending_tickets'     => Schema::hasTable('tickets')
                ? (int) DB::table('tickets')->where('status', 'pending')->count()
                : 0,
            'pending_payments'    => PaymentTransaction::where('status', 'pending')->count(),
            'pending_hosting'     => UserHosting::where('status', 'Pending')->count(),
            'pending_license_renewals'  => LicenseRenewalOrder::where('status', 'pending')->count(),
            'pending_domain_renewals'   => DomainRenewalOrder::where('status', 'pending')->count(),
            'pending_hosting_renewals'  => HostingRenewalOrder::where('status', 'pending')->count(),
            'pending_stolen'      => StolenDomain::where('status', 0)->count(),
        ];

        $data['pending_total'] = collect($data['stats'])->only([
            'pending_tickets',
            'pending_payments',
            'pending_hosting',
            'pending_license_renewals',
            'pending_domain_renewals',
            'pending_hosting_renewals',
            'pending_stolen',
            'expired_licenses',
        ])->sum();

        $data['quickLinks'] = $this->buildQuickLinks($data['isSuper'], $data['stats']);
        $data['incomeChart'] = $this->buildIncomeChart();
        $data['traffic'] = $this->buildTrafficStats();

        return view('admin.dashboard', $data);
    }

    private function buildTrafficStats(): array
    {
        return Cache::remember('dashboard_traffic_stats', 120, function () {
            return $this->computeTrafficStats();
        });
    }

    private function computeTrafficStats(): array
    {
        $meta = SiteVisitTracker::sources();
        $empty = [
            'total_views'   => 0,
            'theme_views'   => 0,
            'sources'       => [],
            'theme_sources' => [],
            'source_meta'   => $meta,
        ];

        if (!Schema::hasTable('site_visits')) {
            return $empty;
        }

        $total = (int) SiteVisit::count();
        $themeViews = (int) SiteVisit::where('page_type', 'theme')->count();

        $sourceRows = SiteVisit::query()
            ->selectRaw('source, COUNT(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        $themeSourceRows = SiteVisit::query()
            ->where('page_type', 'theme')
            ->selectRaw('source, COUNT(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        $sources = [];
        $themeSources = [];

        foreach ($meta as $key => $info) {
            $count = (int) ($sourceRows[$key] ?? 0);
            $themeCount = (int) ($themeSourceRows[$key] ?? 0);

            if ($count > 0) {
                $sources[] = array_merge($info, [
                    'key'     => $key,
                    'count'   => $count,
                    'percent' => $total > 0 ? round($count / $total * 100, 1) : 0,
                ]);
            }

            if ($themeCount > 0) {
                $themeSources[] = array_merge($info, [
                    'key'     => $key,
                    'count'   => $themeCount,
                    'percent' => $themeViews > 0 ? round($themeCount / $themeViews * 100, 1) : 0,
                ]);
            }
        }

        usort($sources, fn ($a, $b) => $b['count'] <=> $a['count']);
        usort($themeSources, fn ($a, $b) => $b['count'] <=> $a['count']);

        return [
            'total_views'   => $total,
            'theme_views'   => $themeViews,
            'sources'       => $sources,
            'theme_sources' => $themeSources,
            'source_meta'   => $meta,
        ];
    }

    private function buildIncomeChart(): array
    {
        $days = 30;
        $start = now()->subDays($days - 1)->startOfDay();
        $rows = collect();

        if (Schema::hasTable('fund_transactions')) {
            $rows = FundTransaction::query()
                ->whereIn('type', ['income', 'due_collection'])
                ->where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
                ->groupBy('day')
                ->pluck('total', 'day');
        }

        $labels = [];
        $values = [];

        for ($i = 0; $i < $days; $i++) {
            $date = $start->copy()->addDays($i);
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            $values[] = round((float) ($rows[$key] ?? 0), 2);
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total'  => round(array_sum($values), 2),
            'today'  => round((float) ($rows[now()->format('Y-m-d')] ?? 0), 2),
            'fund'   => Schema::hasTable('funds') ? (float) (Fund::value('current_balance') ?? 0) : 0,
        ];
    }

    private function buildQuickLinks(bool $isSuper, array $s): array
    {
        $links = [];

        $add = function (string $route, string $icon, string $title, ?string $meta = null, $badge = null, string $badgeType = 'warning') use (&$links) {
            $links[] = compact('route', 'icon', 'title', 'meta', 'badge', 'badgeType');
        };

        // 1. Websites Collection (রেডিমেড ওয়েবসাইট কালেকশন)
        $add('admin.products.index', 'fas fa-laptop-code', __('Websites Collection'), number_format($s['themes']) . ' ' . __('websites'));
        $add('admin.products.create', 'fas fa-plus-circle', __('Add New Website'), __('Add ready-to-sell website'));

        // 2. Services (সার্ভিস)
        $add('admin.service.index', 'fas fa-tools', __('All Services'), number_format($s['services']) . ' ' . __('services'));
        $add('admin.service.create', 'fas fa-plus', __('Add New Service'));

        // 3. Portfolio Projects (পোর্টফোলিও)
        $add('admin.portfolio.index', 'fas fa-images', __('All Projects'), number_format($s['portfolios']) . ' ' . __('projects'));
        $add('admin.portfolio-category.index', 'fas fa-folder', __('Portfolio Categories'));
        $add('admin.portfolio.create', 'fas fa-plus', __('Add New Project'));

        // 4. Pricing Plans (প্রাইসিং প্ল্যান)
        $add('admin.pricing.index', 'fas fa-tags', __('Pricing Plans'), number_format($s['pricing']) . ' ' . __('plans'));

        // 5. Contact Inquiries (কন্ট্যাক্ট মেসেজ)
        $add('admin.contact.index', 'fas fa-envelope', __('Contact Messages'), number_format($s['contacts']) . ' ' . __('messages'));

        // 6. Frontend Content
        $add('admin.about.edit', 'fas fa-star', __('Hero & Mockup Content'));
        $add('admin.testimonial.index', 'fas fa-quote-left', __('Testimonials'), number_format($s['testimonials']) . ' ' . __('reviews'));
        $add('admin.why-choose-us.index', 'fas fa-check-double', __('Why Choose Us'), number_format($s['why_choose']) . ' ' . __('items'));
        $add('admin.brand.index', 'fas fa-building', __('Client Brands'), number_format($s['brands']) . ' ' . __('brands'));
        $add('social.link.index', 'fas fa-share-alt', __('Social Settings'), number_format($s['social_links']) . ' ' . __('links'));

        // 7. General Settings & SEO
        $add('admin.generalsettings.websiteContent', 'fas fa-sliders-h', __('Website Settings'));
        $add('admin.generalsettings.logo', 'fas fa-image', __('Logo & Favicon'));
        $add('seo.meta.keywords', 'fas fa-search', __('Website Meta Keywords'));
        $add('admin.cache.clear', 'fas fa-database', __('Clear Cache'));

        return $links;
    }



// ১. সব রিপোর্ট দেখানোর মেথড (Ajax DataTable সহ)
public function stolenReports(Request $request)
{
    if ($request->ajax()) {
        // প্রতি domain_name থেকে শুধু সর্বশেষ (latest) একটি entry দেখাবে
        $data = \App\Models\StolenDomain::whereIn('id', function ($q) {
                $q->selectRaw('MAX(id)')
                  ->from('stolen_domains')
                  ->groupBy('domain_name');
            })->latest()->get();

        return \DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('checkbox', function($row){
                return '<input type="checkbox" class="row-checkbox" value="'.$row->id.'" data-id="'.$row->id.'">';
            })
            ->addColumn('domain_info', function($row){
                return '<div class="sr-domain">
                            <span class="sr-domain__name">'.e($row->domain_name).'</span>
                            <a href="http://'.e($row->domain_name).'" target="_blank" rel="noopener" class="sr-domain__visit">
                                <i class="fas fa-external-link-alt"></i> Visit Site
                            </a>
                        </div>';
            })
            ->addColumn('details', function($row){
                $url = e($row->full_url);
                return '<div class="sr-details">
                            <span class="sr-details__row"><i class="fas fa-network-wired"></i> '.e($row->ip_address).'</span>
                            <span class="sr-details__row sr-details__url" title="'.$url.'">'.\Illuminate\Support\Str::limit($row->full_url, 55).'</span>
                        </div>';
            })
            ->addColumn('status', function($row){
                if (!empty($row->is_blocked)) {
                    return '<span class="sr-pill sr-pill--blocked"><i class="fas fa-ban"></i> Blocked</span>';
                }
                if ($row->status == 1) {
                    return '<span class="sr-pill sr-pill--resolved"><i class="fas fa-check"></i> Resolved</span>';
                }
                return '<span class="sr-pill sr-pill--pending"><i class="fas fa-clock"></i> Pending</span>';
            })
            ->addColumn('date', function($row){
                return '<span class="sr-date__main">'.$row->created_at->format('d M, Y').'</span>
                        <span class="sr-date__sub">'.$row->created_at->diffForHumans().'</span>';
            })
            ->addColumn('action', function($row){
                $cacheCleared = $row->cache_signal ? '<span class="sr-cache-pill" id="badge-'.$row->id.'"><i class="fas fa-check"></i> Cache Sent</span>' : '';
                $blockBtnClass = !empty($row->is_blocked) ? 'btn-unblock-domain' : 'btn-block-domain';
                $blockBtnTitle = !empty($row->is_blocked) ? 'এই ডোমেইন Unblock করুন' : 'এই ডোমেইন Block করুন';
                $blockBtnIcon = !empty($row->is_blocked) ? 'fas fa-unlock' : 'fas fa-ban';
                $killColumnOk = Schema::hasTable('unauthorized_logs') && Schema::hasColumn('unauthorized_logs', 'kill_switch');
                $killActive = false;
                if ($killColumnOk) {
                    try {
                        $killActive = UnauthorizedLog::killSwitchWantsWipeOut($row->domain_name);
                    } catch (\Throwable $e) {
                        $killActive = false;
                    }
                }
                $killBtnClass = !$killColumnOk
                    ? 'btn-kill-switch-missing'
                    : ($killActive ? 'btn-kill-switch-on' : 'btn-kill-switch-off');
                $killTitle = !$killColumnOk
                    ? 'kill_switch কলাম নেই — প্রজেক্টে php artisan migrate চালান, তারপর আবার চেষ্টা করুন'
                    : ($killActive
                        ? 'Kill switch বন্ধ করুন (ক্লায়েন্টে wipe_out বন্ধ)'
                        : 'Kill switch চালু — cache-signal এ action=wipe_out, ক্লায়েন্ট সেল্ফ-ডেস্ট্রাক্ট হতে পারে');
                $killIcon = !$killColumnOk ? 'fa-exclamation-triangle' : ($killActive ? 'fa-power-off' : 'fa-skull');
                $killBtn = '<button type="button"
                                data-id="'.$row->id.'"
                                data-domain="'.htmlspecialchars($row->domain_name, ENT_QUOTES, 'UTF-8').'"
                                data-url="'.route('admin.stolen.kill_switch', $row->id).'"
                                data-kill-active="'.($killActive ? '1' : '0').'"
                                data-db-ready="'.($killColumnOk ? '1' : '0').'"
                                class="btn-action '.$killBtnClass.' mr-2 btn-kill-switch-action"
                                title="'.htmlspecialchars($killTitle, ENT_QUOTES, 'UTF-8').'">
                                <i class="fas '.$killIcon.'"></i>
                            </button>';
                return '<div class="sr-actions">
                            '.$cacheCleared.'
                            <button type="button"
                                data-id="'.$row->id.'"
                                data-domain="'.htmlspecialchars($row->domain_name).'"
                                data-url="'.route('admin.stolen.clear-cache', $row->id).'"
                                class="btn-action btn-clear-cache mr-2 btn-clear-cache-action"
                                title="Client Cache Clear করুন (৩০ মিনিটের মধ্যে কাজ হবে)">
                                <i class="fas fa-bolt"></i>
                            </button>
                            '.$killBtn.'
                            <button type="button"
                                data-id="'.$row->id.'"
                                data-domain="'.htmlspecialchars($row->domain_name).'"
                                data-url="'.route('admin.stolen.block', $row->id).'"
                                data-blocked="'.(!empty($row->is_blocked) ? '1' : '0').'"
                                class="btn-action '.$blockBtnClass.' mr-2 btn-toggle-block-action"
                                title="'.$blockBtnTitle.'">
                                <i class="'.$blockBtnIcon.'"></i>
                            </button>
                            <a href="'.route('admin.stolen.status', $row->id).'" class="btn-action btn-status-change mr-2" title="Change Status">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                            <button type="button" data-href="'.route('admin.stolen.delete', $row->id).'" class="btn-action btn-delete-report delete-btn" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>';
            })
            ->rawColumns(['checkbox', 'domain_info', 'details', 'status', 'date', 'action'])
            ->make(true);
    }

    $latestRecords = \App\Models\StolenDomain::whereIn('id', function ($q) {
        $q->selectRaw('MAX(id)')->from('stolen_domains')->groupBy('domain_name');
    })->get();

    $stats = [
        'total'    => $latestRecords->count(),
        'pending'  => $latestRecords->filter(fn ($r) => empty($r->is_blocked) && (int) $r->status === 0)->count(),
        'blocked'  => $latestRecords->filter(fn ($r) => !empty($r->is_blocked))->count(),
        'resolved' => $latestRecords->filter(fn ($r) => empty($r->is_blocked) && (int) $r->status === 1)->count(),
    ];

    return view('admin.stolen.index', compact('stats'));
}

// ২. Cache Signal Clear — এটা করলে client site এর license cache মুছে যাবে (৩০ মিনিটের মধ্যে)
public function clearCacheSignal($id)
{
    $record = \App\Models\StolenDomain::findOrFail($id);

    if (!empty($record->is_blocked)) {
        return response()->json([
            'status'  => 'error',
            'message' => '"' . $record->domain_name . '" ডোমেইন বর্তমানে blocked আছে। আগে unblock করুন।',
        ], 422);
    }

    // নতুন random signal token generate করা
    $newSignal = \Illuminate\Support\Str::random(32);
    $record->cache_signal = $newSignal;
    $record->status = 0; // pending এ রিসেট
    $record->save();

    return response()->json([
        'status'  => 'success',
        'message' => '"' . $record->domain_name . '" এর cache signal আপডেট হয়েছে। সর্বোচ্চ ৩০ মিনিটের মধ্যে client site এর cache মুছে যাবে।',
        'signal'  => $newSignal,
    ]);
}

// Domain Block / Unblock টগল
public function toggleBlockStolenReport($id)
{
    $record = \App\Models\StolenDomain::findOrFail($id);

    try {
        $record->is_blocked = !empty($record->is_blocked) ? 0 : 1;

        // Block/Unblock দুই ক্ষেত্রেই client re-check করতে বাধ্য করার জন্য নতুন signal
        $record->cache_signal = \Illuminate\Support\Str::random(32);
        $record->status = 0;
        $record->save();
    } catch (\Illuminate\Database\QueryException $e) {
        $sql = $e->getMessage();
        if (str_contains($sql, 'is_blocked') || str_contains($sql, 'Unknown column')) {
            return response()->json([
                'status'  => 'error',
                'message' => 'ডাটাবেসে is_blocked কলাম নেই। প্রজেক্ট ফোল্ডারে php artisan migrate চালান, অথবা phpMyAdmin এ stolen_domains টেবিলে is_blocked কলাম যোগ করুন।',
            ], 422);
        }
        throw $e;
    }

    $message = !empty($record->is_blocked)
        ? '"' . $record->domain_name . '" ডোমেইন সফলভাবে blocked হয়েছে।'
        : '"' . $record->domain_name . '" ডোমেইন unblock করা হয়েছে।';

    return response()->json([
        'status' => 'success',
        'blocked' => !empty($record->is_blocked),
        'message' => $message,
    ]);
}

/**
 * unauthorized_logs.kill_switch টগল — ডোমেইন অনুযায়ী (cache-signal এ wipe_out)।
 */
public function toggleKillSwitchUnauthorized($id)
{
    if (!Schema::hasTable('unauthorized_logs') || !Schema::hasColumn('unauthorized_logs', 'kill_switch')) {
        return response()->json([
            'status'  => 'error',
            'message' => 'unauthorized_logs টেবিলে kill_switch কলাম নেই। php artisan migrate চালান।',
        ], 422);
    }

    $record = StolenDomain::findOrFail($id);
    $domain = $record->domain_name;
    $currentlyOn = UnauthorizedLog::killSwitchWantsWipeOut($domain);
    $newOn = !$currentlyOn;

    if ($newOn) {
        $updated = UnauthorizedLog::where('domain', $domain)->update(['kill_switch' => true]);
        if ($updated === 0) {
            UnauthorizedLog::create([
                'domain'       => $domain,
                'ip'           => null,
                'reason'       => '[Admin] Kill switch — remote wipe_out (client self-destruct) enabled',
                'url'          => null,
                'attempted_at' => now(),
                'kill_switch'  => true,
            ]);
        }
    } else {
        UnauthorizedLog::where('domain', $domain)->update(['kill_switch' => false]);
    }

    $message = $newOn
        ? '"' . $domain . '" — Kill switch চালু। ক্লায়েন্ট সাইট cache-signal/verify এ action=wipe_out পাবে।'
        : '"' . $domain . '" — Kill switch বন্ধ।';

    return response()->json([
        'status'      => 'success',
        'kill_switch' => $newOn,
        'message'     => $message,
    ]);
}

// ৩. স্ট্যাটাস পরিবর্তন করার মেথড (এই মেথডটি মিসিং ছিল)
public function statusStolenReport($id)
{
    $data = \App\Models\StolenDomain::findOrFail($id);
    $data->status = $data->status == 1 ? 0 : 1;
    $data->update();

    return redirect()->back()->with('success', 'Status Updated Successfully.');
}

// ৩. ডিলিট করার মেথড
public function deleteStolenReport($id)
{
    $data = \App\Models\StolenDomain::findOrFail($id);
    $data->delete();

    return redirect()->back()->with('success', 'Report Deleted Successfully.');
}

// ৪. সিলেক্ট অল করে সব রিপোর্ট ডিলিট করার মেথড
public function deleteAllStolenReports()
{
    \App\Models\StolenDomain::truncate();
    return redirect()->back()->with('success', 'All reports deleted successfully.');
}

// ৫. সিলেক্টেড রিপোর্টগুলো ডিলিট করার মেথড
public function deleteSelectedStolenReports(Request $request)
{
    $ids = $request->input('ids', []);
    if (empty($ids)) {
        return redirect()->back()->with('error', 'কোনো আইটেম সিলেক্ট করা হয়নি।');
    }
    \App\Models\StolenDomain::whereIn('id', $ids)->delete();
    return redirect()->back()->with('success', 'সিলেক্টেড রিপোর্টগুলো সফলভাবে ডিলিট হয়েছে।');
}

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile.edit',compact('data'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section
        $data = Auth::guard('admin')->user();
        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'required|unique:admins,email,'.$data->id,
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $input = $request->all();
    
        if ($file = $request->file('photo'))
        {
            admin_delete_image('assets/images/admin', $data?->photo);
            $input['photo'] = admin_upload_image($file, 'assets/images/admin');
        }
        $data->update($input);
        $msg = 'Successfully updated your profile';
        return response()->json($msg);
    }

    public function passwordreset()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile.password',compact('data'));
    }

    public function changepass(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($request->cpass){
            if (Hash::check($request->cpass, $admin->password)){
                if ($request->newpass == $request->renewpass){
                    $input['password'] = Hash::make($request->newpass);
                }else{
                    return response()->json(array('errors' => [ 0 => 'Confirm password does not match.' ]));
                }
            }else{
                return response()->json(array('errors' => [ 0 => 'Current password Does not match.' ]));
            }
        }
        $admin->update($input);
        $msg = 'Successfully change your passwprd';
        return response()->json($msg);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }


    public function generate_bkup()
    {
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            $bkuplink = url($chk);
        }
        return view('admin.movetoserver',compact('bkuplink','chk'));
    }


    public function clear_bkup()
    {
        $destination  = public_path().'/install';
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        $handle = fopen('backup.txt','w+');
        fwrite($handle,"");
        fclose($handle);
        //return "No Backup File Generated.";
        return redirect()->back()->with('success','Backup file Deleted Successfully!');
    }


    public function activation()
    {
        $activation_data = "";
        if (file_exists(public_path().'/project/license.txt')){
            $license = file_get_contents(public_path().'/project/license.txt');
            if ($license != ""){
                $activation_data = "<i style='color:darkgreen;' class='icofont-check-circled icofont-4x'></i><br><h3 style='color:darkgreen;'>Your System is Activated!</h3><br> Your License Key:  <b>".$license."</b>";
            }
        }
        return view('admin.activation',compact('activation_data'));
    }


    public function activation_submit(Request $request)
    {
        //return config('services.genius.ocean');
        $purchase_code =  $request->pcode;
        $my_script =  'Newspaper';
        $my_domain = url('/');

        $varUrl = str_replace (' ', '%20', config('services.genius.ocean').'purchase112662activate.php?code='.$purchase_code.'&domain='.$my_domain.'&script='.$my_script);

        if( ini_get('allow_url_fopen') ) {
            $contents = file_get_contents($varUrl);
        }else{
            $ch = curl_init();
            curl_setopt ($ch, CURLOPT_URL, $varUrl);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            $contents = curl_exec($ch);
            curl_close($ch);
        }

        $chk = json_decode($contents,true);

        if($chk['status'] != "success")
        {

            $msg = $chk['message'];
            return response()->json($msg);
            //return redirect()->back()->with('unsuccess',$chk['message']);

        }else{
            $this->setUp($chk['p2'],$chk['lData']);

            if (file_exists(public_path().'/rooted.txt')){
                unlink(public_path().'/rooted.txt');
            }

            $fpbt = fopen(public_path().'/project/license.txt', 'w');
            fwrite($fpbt, $purchase_code);
            fclose($fpbt);

            $msg = 'Congratulation!! Your System is successfully Activated.';
            return response()->json($msg);
            //return redirect('admin/dashboard')->with('success','Congratulation!! Your System is successfully Activated.');
        }
        //return config('services.genius.ocean');
    }

    function setUp($mtFile,$goFileData){
        $fpa = fopen(public_path().$mtFile, 'w');
        fwrite($fpa, $goFileData);
        fclose($fpa);
    }



    public function movescript(){
        ini_set('max_execution_time', 3000);

        $destination  = public_path().'/install';
        $chk = file_get_contents('backup.txt');
        if ($chk != ""){
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }

        $src = base_path().'/vendor/update';
        $this->recurse_copy($src,$destination);
        $files = public_path();
        $bkupname = 'GeniusCart-By-GeniusOcean-'.date('Y-m-d').'.zip';

        $zipper = new \Chumper\Zipper\Zipper;

        $zipper->make($bkupname)->add($files);

        $zipper->remove($bkupname);

        $zipper->close();

        $handle = fopen('backup.txt','w+');
        fwrite($handle,$bkupname);
        fclose($handle);

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        return response()->json(['status' => 'success','backupfile' => url($bkupname),'filename' => $bkupname],200);
    }

    public function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function deleteDir($dirPath) {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath must be a directory");
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

}

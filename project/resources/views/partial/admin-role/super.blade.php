{{-- ============================================================
     JUKTI TECH — MODERN STREAMLINED ADMIN SIDEBAR (SUPER ADMIN)
     Clean, categorized SaaS navigation with sleek icons
============================================================ --}}

{{-- Category 1: কোর বিজনেস --}}
<li class="sidebar-section-title">কোর বিজনেস</li>

{{-- 1. Websites Collection (রেডিমেড ওয়েবসাইট কালেকশন) --}}
<li>
    <a href="#product-section" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-laptop-code"></i>
        <span>{{ __('Websites Collection') }}</span>
        <i class="fas fa-chevron-right sidebar-chevron"></i>
    </a>
    <ul class="collapse list-unstyled" id="product-section" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.products.index') }}">
                <i class="fas fa-circle" style="font-size: 0.45rem;"></i> <span>{{ __('সকল ওয়েবসাইট') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.create') }}">
                <i class="fas fa-plus"></i> <span>{{ __('নতুন ওয়েবসাইট যুক্ত করুন') }}</span>
            </a>
        </li>
    </ul>
</li>

{{-- 1.1 Website Orders (ওয়েবসাইট অর্ডারসমূহ) --}}
<li>
    <a href="{{ route('admin.website_orders.index') }}" class="wave-effect">
        <i class="fas fa-shopping-bag"></i> 
        <span>{{ __('Website Orders (অর্ডারসমূহ)') }}</span>
        @php
            $pending_orders_count = \App\Models\Order::where('status', 'Pending')->count();
        @endphp
        @if($pending_orders_count > 0)
            <span class="badge badge-pill badge-warning ml-auto" style="background: #ff5722; color: #fff; font-size: 0.72rem; font-weight: 700;">{{ $pending_orders_count }}</span>
        @endif
    </a>
</li>

{{-- 2. Our Services (আমাদের সেবাসমূহ) --}}
<li>
    <a href="#service-section" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-tools"></i>
        <span>{{ __('Our Services (সেবাসমূহ)') }}</span>
        <i class="fas fa-chevron-right sidebar-chevron"></i>
    </a>
    <ul class="collapse list-unstyled" id="service-section" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.service.index') }}">
                <i class="fas fa-circle" style="font-size: 0.45rem;"></i> <span>{{ __('সকল সার্ভিস') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.service.create') }}">
                <i class="fas fa-plus"></i> <span>{{ __('নতুন সার্ভিস যোগ করুন') }}</span>
            </a>
        </li>
    </ul>
</li>

{{-- 3. Portfolio Projects (পোর্টফোলিও ও প্রজেক্ট শোকেস) --}}
<li>
    <a href="#portfolio-section" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-briefcase"></i>
        <span>{{ __('Portfolio (পোর্টফোলিও)') }}</span>
        <i class="fas fa-chevron-right sidebar-chevron"></i>
    </a>
    <ul class="collapse list-unstyled" id="portfolio-section" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.portfolio-category.index') }}">
                <i class="fas fa-folder"></i> <span>{{ __('প্রজেক্ট ক্যাটাগরি') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.portfolio.index') }}">
                <i class="fas fa-circle" style="font-size: 0.45rem;"></i> <span>{{ __('সকল প্রজেক্ট') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.portfolio.create') }}">
                <i class="fas fa-plus"></i> <span>{{ __('নতুন প্রজেক্ট যুক্ত করুন') }}</span>
            </a>
        </li>
    </ul>
</li>

{{-- 4. Pricing Plans (প্রাইসিং প্ল্যান) --}}
<li>
    <a href="{{ route('admin.pricing.index') }}" class="wave-effect">
        <i class="fas fa-tags"></i> <span>{{ __('Pricing Plans (প্যাকেজ)') }}</span>
    </a>
</li>

{{-- Category 2: গ্রাহক যোগাযোগ --}}
<li class="sidebar-section-title">গ্রাহক যোগাযোগ</li>

{{-- 5. Contact Messages (গ্রাহকদের মেসেজ ও ইনকোয়ারি) --}}
<li>
    <a href="{{ route('admin.contact.index') }}" class="wave-effect">
        <i class="fas fa-envelope"></i> 
        <span>{{ __('Contact Messages') }}</span>
        @php
            $unread_messages = \App\Models\Contact::count();
        @endphp
        @if($unread_messages > 0)
            <span class="badge badge-pill badge-danger ml-auto" style="background: #ff5722; font-size: 0.72rem;">{{ $unread_messages }}</span>
        @endif
    </a>
</li>

{{-- Category 3: কনটেন্ট ও হোমপেজ --}}
<li class="sidebar-section-title">কনটেন্ট ও হোমপেজ</li>

{{-- 6. Frontend Setup (হোমপেজ ও ফ্রন্টএন্ড কনটেন্ট) --}}
<li>
    <a href="#frontend-customization" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-palette"></i>
        <span>{{ __('Frontend Setup') }}</span>
        <i class="fas fa-chevron-right sidebar-chevron"></i>
    </a>
    <ul class="collapse list-unstyled" id="frontend-customization" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.about.edit') }}">
                <i class="fas fa-magic"></i> <span>{{ __('Hero & Mockup Content') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.homepage.sections') }}">
                <i class="fas fa-layer-group"></i> <span>{{ __('Homepage Sections') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.testimonial.index') }}">
                <i class="fas fa-quote-left"></i> <span>{{ __('Testimonials (রিভিউ)') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.why-choose-us.index') }}">
                <i class="fas fa-check-circle"></i> <span>{{ __('Why Choose Us') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.brand.index') }}">
                <i class="fas fa-building"></i> <span>{{ __('Client Brands (ব্র্যান্ড)') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('social.link.index') }}">
                <i class="fas fa-share-alt"></i> <span>{{ __('Social Links') }}</span>
            </a>
        </li>
    </ul>
</li>

{{-- Category 4: সেটিংস ও মেইনটেনেন্স --}}
<li class="sidebar-section-title">সিস্টেম সেটিংস</li>

{{-- 7. General Settings & SEO (সেটিংস ও এসইও) --}}
<li>
    <a href="#general" class="accordion-toggle wave-effect" data-toggle="collapse" aria-expanded="false">
        <i class="fas fa-cog"></i>
        <span>{{ __('General Settings') }}</span>
        <i class="fas fa-chevron-right sidebar-chevron"></i>
    </a>
    <ul class="collapse list-unstyled" id="general" data-parent="#accordion">
        <li>
            <a href="{{ route('admin.generalsettings.websiteContent') }}">
                <i class="fas fa-globe"></i> <span>{{ __('Website Info & Contact') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.generalsettings.logo') }}">
                <i class="fas fa-image"></i> <span>{{ __('Website Logo & Banner') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.generalsettings.favicon') }}">
                <i class="fas fa-star"></i> <span>{{ __('Website Favicon') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('seo.meta.keywords') }}">
                <i class="fas fa-search"></i> <span>{{ __('SEO Meta & Keywords') }}</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.staff.index') }}">
                <i class="fas fa-user-shield"></i> <span>{{ __('Admin Staff & Profile') }}</span>
            </a>
        </li>
    </ul>
</li>

{{-- 8. Clear Cache --}}
<li>
    <a href="{{ route('admin.cache.clear') }}" class="wave-effect">
        <i class="fas fa-sync-alt"></i> <span>{{ __('Clear Cache') }}</span>
    </a>
</li>
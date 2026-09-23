@php
    $currentRoute = Route::currentRouteName();
    $links = [
        ['route' => 'admin.frontend-theme.edit', 'label' => __('Theme'), 'icon' => 'fas fa-palette'],
        ['route' => 'admin.about.edit', 'label' => __('Hero'), 'icon' => 'fas fa-star'],
        ['route' => 'admin.homepage.sections', 'label' => __('Sections'), 'icon' => 'fas fa-th-large'],
        ['route' => 'admin.testimonial.index', 'label' => __('Testimonials'), 'icon' => 'fas fa-quote-left'],
        ['route' => 'admin.counter.index', 'label' => __('Counters'), 'icon' => 'fas fa-sort-numeric-up'],
        ['route' => 'admin.service.edit_text', 'label' => __('Section Text'), 'icon' => 'fas fa-edit'],
        ['route' => 'admin.brand.index', 'label' => __('Brands'), 'icon' => 'fas fa-building'],
        ['route' => 'admin.pricing.index', 'label' => __('Pricing'), 'icon' => 'fas fa-tags'],
        ['route' => 'admin.why-choose-us.index', 'label' => __('Why Us'), 'icon' => 'fas fa-star'],
        ['route' => 'social.link.index', 'label' => __('Social'), 'icon' => 'fas fa-share-alt'],
    ];
@endphp
<nav class="fe-nav" aria-label="{{ __('Frontend Setup Navigation') }}">
    @foreach($links as $link)
        <a href="{{ route($link['route']) }}"
           class="{{ $currentRoute === $link['route'] ? 'is-active' : '' }}">
            <i class="{{ $link['icon'] }}"></i> {{ $link['label'] }}
        </a>
    @endforeach
</nav>

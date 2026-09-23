@php
    $mockup = $hero->mockup ?? [];
    $mockupType = $mockup['type'] ?? 'wireframe';
@endphp

@if($mockupType === 'image' && !empty($hero->hero_mockup_image))
    <div class="home-hero__visual home-hero__visual--image">
        <div class="home-hero__visual-glow"></div>
        <img src="{{ asset_image('assets/images/hero/'.$hero->hero_mockup_image) }}"
             alt="Hero"
             class="home-hero__mockup-img">
    </div>
@else
    <div class="home-hero__visual">
        <div class="home-hero__visual-glow"></div>

        @if(!empty($mockup['float_1']))
            <div class="home-hero__float home-hero__float--1">
                <i class="fas fa-credit-card"></i> {{ $mockup['float_1'] }}
            </div>
        @endif
        @if(!empty($mockup['float_2']))
            <div class="home-hero__float home-hero__float--2">
                <i class="fas fa-truck"></i> {{ $mockup['float_2'] }}
            </div>
        @endif
        @if(!empty($mockup['float_3']))
            <div class="home-hero__float home-hero__float--3">
                <i class="fas fa-money-bill-wave"></i> {{ $mockup['float_3'] }}
            </div>
        @endif

        <div class="home-hero__browser">
            <div class="home-hero__browser-bar">
                <span class="home-hero__dot home-hero__dot--red"></span>
                <span class="home-hero__dot home-hero__dot--yellow"></span>
                <span class="home-hero__dot home-hero__dot--green"></span>
                <div class="home-hero__browser-url">
                    <i class="fas fa-lock"></i> {{ $mockup['browser_url'] ?? 'shop.yourstore.com' }}
                </div>
            </div>

            <div class="home-hero__browser-body">
                <div class="home-hero__shop-header">
                    <span class="home-hero__shop-logo"></span>
                    <div class="home-hero__shop-search">
                        <i class="fas fa-search"></i>
                        <span>{{ $mockup['search_placeholder'] ?? 'প্রোডাক্ট খুঁজুন...' }}</span>
                    </div>
                    <div class="home-hero__shop-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                        <em>{{ $mockup['cart_count'] ?? '3' }}</em>
                    </div>
                </div>

                <div class="home-hero__shop-banner">
                    @if(!empty($mockup['sale_text']))
                        <span class="home-hero__shop-sale">{{ $mockup['sale_text'] }}</span>
                    @endif
                    <div class="home-hero__shop-banner-text">
                        <span class="line lg"></span>
                        <span class="line md"></span>
                        <span class="line sm"></span>
                        @if(!empty($mockup['banner_btn']))
                            <span class="home-hero__shop-banner-btn">{{ $mockup['banner_btn'] }}</span>
                        @endif
                    </div>
                </div>

                @php $categories = $mockup['categories'] ?? ['All', 'Electronics', 'Fashion', 'Home']; @endphp
                @if(count($categories))
                    <div class="home-hero__shop-cats">
                        @foreach($categories as $i => $cat)
                            <span class="{{ $i === 0 ? 'is-active' : '' }}">{{ $cat }}</span>
                        @endforeach
                    </div>
                @endif

                @php $products = $mockup['products'] ?? []; @endphp
                @if(count($products))
                    <div class="home-hero__shop-products">
                        @foreach($products as $product)
                            <div class="home-hero__product">
                                @if(!empty($product['badge']))
                                    <span class="home-hero__product-badge {{ $product['badge_class'] ?? '' }}">{{ $product['badge'] }}</span>
                                @endif
                                <span class="home-hero__product-name"></span>
                                <span class="home-hero__product-name is-short"></span>
                                <div class="home-hero__product-meta">
                                    @if(!empty($product['price']))
                                        <strong>{{ $product['price'] }}</strong>
                                    @endif
                                    @if(!empty($product['rating']))
                                        <small><i class="fas fa-star"></i> {{ $product['rating'] }}</small>
                                    @endif
                                </div>
                                <span class="home-hero__product-add"><i class="fas fa-plus"></i></span>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!empty($mockup['checkout_text']) || !empty($mockup['checkout_btn']))
                    <div class="home-hero__shop-checkout">
                        @if(!empty($mockup['checkout_text']))
                            <span><i class="fas fa-shopping-bag"></i> {!! $mockup['checkout_text'] !!}</span>
                        @endif
                        @if(!empty($mockup['checkout_btn']))
                            <span class="home-hero__shop-pay">{{ $mockup['checkout_btn'] }} <i class="fas fa-arrow-right"></i></span>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

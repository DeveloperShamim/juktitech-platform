    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="text-white d-flex align-items-center">
    <img src="{{ asset_image('assets/images/logo/'.$gs->footer_logo) }}" alt="Logo" style="height: 40px; margin-right: 10px;">
</h5>
                    <p>{{ $gs->footer_details }}</p>
                    <div class="social-icons mt-3">
					
				@foreach ($social_links->sortBy('id') as $social_link) 
    <a href="{{ $social_link->link}}"><i class="{{$social_link->icon}}"></i></a>
@endforeach
                    </div>
                </div>
                <div class="col-lg-2 col-6 mb-4">
                    <h5>লিঙ্কস</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/') }}">হোম</a></li>
                        <li class="mb-2"><a href="{{ url('/about') }}">আমাদের সম্পর্কে</a></li>
                        <li class="mb-2"><a href="{{ url('/service') }}">সার্ভিস</a></li>
                        <li class="mb-2"><a href="{{ url('/contact') }}">যোগাযোগ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6 mb-4">
                    <h5>সার্ভিসসমূহ</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ url('/service') }}">ই-কমার্স</a></li>
                        <li class="mb-2"><a href="{{ url('/service') }}">এসইও</a></li>
                        <li class="mb-2"><a href="{{ url('/service') }}">গ্রাফিক ডিজাইন</a></li>
                        <li class="mb-2"><a href="{{ url('/service') }}">কনটেন্ট রাইটিং</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 mb-4">
                    <h5>যোগাযোগ</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> {{ $gs->address }}</p>
                    <p><i class="fas fa-phone me-2"></i> {{ $gs->phone }}</p>
                    <p><i class="fas fa-envelope me-2"></i> {{ $gs->email }}</p>
                </div>
            </div>
            <hr class="border-secondary mt-4">
            <div class="text-center pt-3">
               <p class="mb-0">&copy; {{ date('Y') }} সকল কিছুর স্বত্বাধিকার সংরক্ষিতঃ <a href="{{ url('/') }}">{{ $gs->title ?? 'Jukti Tech' }}</a></p>
            </div>
        </div>
    </footer>

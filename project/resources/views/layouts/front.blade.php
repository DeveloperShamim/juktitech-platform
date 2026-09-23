<!DOCTYPE html>
<html lang="bn">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
      @yield('meta')
		<title>{{ $gs->title }}</title>
		<meta name="google-site-verification" content="RmBXIivmPeG5ymjxBj8ZiDEq399ypy5So2_XxKyiRtQ" />
		<link rel="shortcut icon" href="{{asset_image('assets/images/'.$gs->favicon)}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @include('partial.front.theme')

    <style>
        body {
            font-family: 'Hind Siliguri', 'Noto Sans Bengali', sans-serif;
            overflow-x: hidden;
        }

        /* Navbar Customization */
        .navbar {
            box-shadow: 0 2px 12px var(--navbar-border);
            background: white;
            padding: 15px 0;
            border-bottom: 1px solid var(--navbar-border);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-hover) !important;
        }
        .nav-link {
            font-weight: 500;
            color: var(--text-heading) !important;
            margin-right: 15px;
        }
        .nav-link:hover {
            color: var(--primary-color) !important;
        }
        .navbar .btn-custom {
            background: var(--accent-gradient-vertical);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 8px 20px var(--accent-shadow-md);
        }
        .navbar .btn-custom:hover {
            background: var(--accent-gradient-vertical-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px var(--accent-shadow);
        }
        .btn-custom {
            background: var(--accent-gradient);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 25px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 8px 20px var(--accent-shadow-md);
        }
        .btn-custom:hover {
            background: var(--accent-gradient-hover);
            color: white;
            transform: translateY(-2px);
        }
		
		

/* ড্রপডাউন আইটেম হোভার ইফেক্ট */
.dropdown-item {
    font-size: 14px;
    cursor: pointer;
}
.dropdown-item:hover {
    background-color: var(--primary-bg-lighter);
    color: var(--primary-color);
}
        /* Hero Section */
        .hero-section {
            padding: 80px 0;
            background: linear-gradient(to right, #fff 50%, #f0f8ff 50%);
            display: flex;
            align-items: center;
        }
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }
        .hero-img {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 100%;
            transition: transform 0.3s;
        }
        .hero-img:hover {
            transform: scale(1.02);
        }

         /* Services Section */
        .service-card {
            border: none;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            height: 100%;
        }
        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .icon-box {
            width: 70px;
            height: 70px;
            line-height: 70px;
            background: rgba(13, 110, 253, 0.1);
            color: var(--secondary-color);
            border-radius: 50%;
            font-size: 30px;
            margin: 0 auto 20px;
        }

        /* Pricing Section */
        .pricing-card {
            border: 1px solid #eee;
            border-radius: 20px;
            padding: 40px 20px;
            text-align: center;
            transition: 0.3s;
            position: relative;
            background: white;
            overflow: hidden;
        }
        .pricing-card:hover {
            border-color: var(--primary-color);
            transform: scale(1.03);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            z-index: 2;
        }
        .pricing-card.popular {
            background: #fff5eb;
            border: 2px solid var(--primary-color);
        }
        .price-tag {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 20px 0;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin-bottom: 30px;
        }
        .feature-list li {
            margin-bottom: 10px;
            font-size: 0.95rem;
            color: #666;
        }
        .feature-list li i {
            color: #28a745;
            margin-right: 8px;
        }

        /* Team Section Styles */
        .team-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
            border: 1px solid #eee;
            max-width: 300px;
            margin: 0 auto;
        }
        .team-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-color: var(--primary-color);
        }
        .team-img-wrapper {
            position: relative;
            overflow: hidden;
        }
        .team-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            object-position: top center;
            transition: 0.5s;
        }
        .team-card:hover .team-img {
            transform: scale(1.05);
        }
        .team-social {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.8);
            text-align: center;
            padding: 10px 0;
            transition: 0.3s;
        }
        .team-card:hover .team-social {
            bottom: 0;
        }
        .team-social a {
            color: white;
            margin: 0 10px;
            font-size: 1rem;
            transition: 0.3s;
        }
        .team-social a:hover {
            color: var(--primary-color);
        }

        /* Testimonial Section Styles */
        .testimonial-section {
            background-color: #fff8f0; 
            padding-bottom: 50px;
        }
        .testimonial-divider {
            color: var(--primary-color);
            font-weight: 700;
            position: relative;
            display: inline-block;
            padding-bottom: 10px;
        }
        .testimonial-divider::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        .testimonial-card {
            background: white;
            border-radius: 20px;
            padding: 30px 20px;
            padding-top: 60px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-top: 50px; 
            margin-bottom: 10px;
            text-align: left;
            border: 1px solid #ffeacc;
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .testimonial-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            position: absolute;
            top: -45px;
            left: 50%; 
            transform: translateX(-50%); 
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .carousel-indicators {
            position: relative; 
            margin-top: 30px;
            margin-bottom: 0;
        }
        .carousel-indicators button {
            background-color: var(--primary-color) !important;
            width: 10px !important;
            height: 10px !important;
            border-radius: 50%;
            margin: 0 5px;
            opacity: 0.5;
        }
        .carousel-indicators button.active {
            opacity: 1;
        }

        /* Brand Logo Section */
        .brand-section {
            background-color: white;
            padding: 50px 0;
            border-top: 1px solid #eee;
        }
        .brand-logo {
            width: 100%;
            max-width: 140px;
            height: auto;
            filter: grayscale(100%); /* সাদা-কালো */
            opacity: 0.6;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .brand-logo:hover {
            filter: grayscale(0%); /* মাউস নিলে রঙিন */
            opacity: 1;
            transform: scale(1.1);
        }

        /* Stats Section */
        .stats-section {
            background: var(--dark-bg);
            color: white;
            padding: 60px 0;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        /* Footer */
        footer {
            background: var(--footer-gradient);
            color: rgba(255, 255, 255, 0.72);
            padding: 50px 0 20px;
        }
        footer h5 {
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
        }
        footer a {
            color: rgba(255, 255, 255, 0.72);
            text-decoration: none;
            transition: 0.3s;
        }
        footer a:hover {
            color: var(--footer-link-hover);
        }
        footer p i {
            color: var(--footer-icon-color);
        }
        footer hr {
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        .social-icons a {
            font-size: 1.2rem;
            margin-right: 15px;
            color: rgba(255, 255, 255, 0.78);
        }
        .social-icons a:hover {
            color: var(--footer-link-hover);
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                background: white;
                text-align: center;
                padding: 40px 0;
            }
            .hero-title {
                font-size: 2rem;
            }
            .hero-img {
                margin-top: 30px;
            }
        }
    </style>
    
    <!-- Meta Pixel Code -->
 <script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '1745630803274780');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=1745630803274780&ev=PageView&noscript=1"
/></noscript>
    <!-- End Meta Pixel Code -->
    <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TZTRCS4W');</script>
<!-- End Google Tag Manager -->
</head>
<body>

   
   
   
   
   
   
   
   
   
   <!-- Header Part-->
    @include('partial.front.header')
    <!-- Header Part End-->
   

    



  
     <!--Content of each page-->
    @yield('contents')
	<!--Content of each page end-->
                                           


                        
                            

                       
                        
                        
        
        
                
       
	<!-- Footer Area Start -->
	@include('partial.front.footer')
	<!-- Footer Area End -->










<script type="text/javascript">
    // ১. ভাষা পরিবর্তন করার লজিক (কুকি + localStorage)
    function changeLanguage(lang) {
        if (lang === 'bn') {
            // বাংলায় ক্লিক করলে পছন্দ সংরক্ষণ ও কুকি মুছে ফেলা
            try { localStorage.setItem('siteLang', 'bn'); } catch (e) {}
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; domain=." + document.domain + "; path=/;";
        } else {
            // ইংরেজিতে ক্লিক করলে পছন্দ সংরক্ষণ ও কুকি সেট করা
            try { localStorage.setItem('siteLang', 'en'); } catch (e) {}
            document.cookie = "googtrans=/bn/en; path=/";
        }
        location.reload();
    }

    // ২. গুগল ট্রান্সলেট লোড করা
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'bn',
            includedLanguages: 'bn,en',
            autoDisplay: false
        }, 'google_translate_element');
        
        // ৩. ডিফল্ট বাংলা – শুধু ইংরেজি সিলেক্ট করলে ট্রান্সলেট হবে
        // প্রথম ভিজিট বা বাংলা সিলেক্ট করলে অরিজিনাল বাংলা দেখাবে
        // changeLanguage('en') ক্লিক করলেই googtrans সেট হবে ও পেজ রিলোড
        
        // ৪. গুগল ট্রান্সলেট বার সম্পূর্ণভাবে হাইড করা
        setTimeout(function() {
            var googleBar = document.querySelector('.goog-te-banner-frame');
            if (googleBar) {
                googleBar.style.display = 'none';
            }
            // body থেকে top style রিমুভ করা
            document.body.style.top = '0';
        }, 100);
    }
    
    // ৫. পেজ লোড হওয়ার পর গুগল বার চেক করা
    window.addEventListener('load', function() {
        setInterval(function() {
            var frames = document.querySelectorAll('iframe.goog-te-banner-frame, .skiptranslate iframe');
            frames.forEach(function(frame) {
                frame.style.display = 'none';
                frame.style.visibility = 'hidden';
            });
            
            // body style ফিক্স রাখা
            if (document.body.style.top !== '0px') {
                document.body.style.top = '0px';
            }
        }, 500);
    });
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
$(document).ready(function(){
    $('.brand-slider').owlCarousel({
        loop: true,           // স্লাইডারটি লুপ হবে
        margin: 30,           // লোগোগুলোর মধ্যবর্তী দূরত্ব
        nav: false,           // নেভিগেশন এরো বন্ধ (প্রয়োজন হলে true দিন)
        dots: false,          // নিচের ডটস বন্ধ
        autoplay: true,       // অটো-প্লে চালু
        autoplayTimeout: 3000,// ৩ সেকেন্ড পরপর স্লাইড হবে
        autoplayHoverPause: true, // মাউস রাখলে স্লাইড থমকে যাবে
        responsive:{
            0:{
                items: 2      // মোবাইলে ২ টি লোগো
            },
            600:{
                items: 3      // ট্যাবে ৩ টি লোগো
            },
            1000:{
                items: 6      // কম্পিউটারে ৬ টি লোগো
            }
        }
    });
});
</script>
<script>
    $(document).ready(function(){
        $(".testimonial-carousel").owlCarousel({
            loop: true,           // লুপ হবে
            margin: 20,           // কার্ডের মাঝে গ্যাপ
            nav: false,           // নেভিগেশন এরো বন্ধ
            dots: true,           // নিচে ডট বাটন চালু
            autoplay: true,       // অটো প্লে হবে
            autoplayTimeout: 4000,// ৪ সেকেন্ড পর পর ঘুরবে
            smartSpeed: 1000,     // স্মুথ এনিমেশন
            responsive:{
                0:{
                    items: 1      // মোবাইলে ১টি দেখাবে
                },
                768:{
                    items: 2      // ট্যাবলেটে ২টি দেখাবে
                },
                1000:{
                    items: 3      // ল্যাপটপে ৩টি দেখাবে
                }
            }
        });
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
<script>
$(document).ready(function(){
    $('.team-slider').owlCarousel({
        loop: true,           // স্লাইডার বারবার চলবে
        margin: 25,           // কার্ডগুলোর মধ্যবর্তী ফাঁকা জায়গা
        nav: false,           // নেভিগেশন এরো (প্রয়োজন হলে true দিন)
        dots: true,           // নিচে গোল ডট দেখাবে
        autoplay: true,       // অটো-প্লে চালু
        autoplayTimeout: 4000,// ৪ সেকেন্ড পর পর পরিবর্তন হবে
        responsive:{
            0:{
                items: 1      // মোবাইলে ১ জন
            },
            600:{
                items: 2      // ট্যাবে ২ জন
            },
            1000:{
                items: 4      // বড় স্ক্রিনে ৪ জন
            }
        }
    });
});
</script>
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

<script>
$(document).ready(function() {
    // Isotope গ্রিড ইনিশিয়ালাইজ করা
    var $grid = $('.portfolio-grid').isotope({
        itemSelector: '.portfolio-item',
        layoutMode: 'fitRows',
        percentPosition: true
    });

    // ফিল্টার বাটনে ক্লিক করলে
    $('.portfolio-menu button').on('click', function() {
        var filterValue = $(this).attr('data-filter');
        
        // Isotope ফিল্টার রান করা
        $grid.isotope({ filter: filterValue });

        // একটিভ ক্লাস পরিবর্তন
        $('.portfolio-menu button').removeClass('active');
        $(this).addClass('active');

        // যদি ফিল্টার করার পর কোনো আইটেম না থাকে তবে মেসেজ দেখানো
        setTimeout(function() {
            var visibleItems = $grid.data('isotope').filteredItems.length;
            if (visibleItems === 0) {
                if ($("#empty-msg").length === 0) {
                    $(".portfolio-grid").append('<div id="empty-msg" class="col-12 text-center py-5 text-muted"><h5>এই ক্যাটাগরিতে কোনো প্রজেক্ট নেই।</h5></div>');
                }
            } else {
                $("#empty-msg").remove();
            }
        }, 300);
    });
});
</script>
<script>
    $(document).ready(function(){
        // ১. পোর্টফোলিও গ্রিড ইনিশিয়ালাইজ করা
        var $grid = $('.portfolio-grid').isotope({
            itemSelector: '.portfolio-item',
            layoutMode: 'fitRows'
        });

        // ২. বাটনে ক্লিক করলে ফিল্টার হবে
        $('.portfolio-menu').on( 'click', 'button', function() {
            var filterValue = $(this).attr('data-filter');
            $grid.isotope({ filter: filterValue });
        });

        // ৩. একটিভ ক্লাস পরিবর্তন করা (বাটন কালার চেইঞ্জ)
        $('.portfolio-menu button').on('click', function(event) {
            $(this).siblings('.active').removeClass('active');
            $(this).addClass('active');
            event.preventDefault();
        });
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TZTRCS4W"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
</body>
</html>
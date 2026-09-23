<!DOCTYPE html>
<html lang="bn" dir="ltr">

<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="author" content="GeniusOcean">
    	<meta name="csrf-token" content="{{ csrf_token() }}">
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Sans+Bengali:wght@400;500;600;700&display=swap" rel="stylesheet">
		<!-- Title -->
		<title>{{__('এডমিন প্যানেল')}}</title>
		<!-- favicon -->
		<link rel="shortcut icon" href="{{asset_image('assets/images/'.$gs->favicon)}}" type="image/x-icon">
		<!-- Bootstrap -->
		<link href="{{asset_image('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" />
		<!-- Fontawesome 6 & 5 -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="{{asset_image('assets/admin/css/fontawesome.css')}}">
		<!-- icofont -->
		<link rel="stylesheet" href="{{asset_image('assets/admin/css/icofont.min.css')}}">
		<link rel="stylesheet" href="{{asset_image('assets/admin/css/jquery-ui.css')}}">
		<!-- Sidemenu Css -->
		<link href="{{asset_image('assets/admin/plugins/fullside-menu/css/dark-side-style.css')}}?v={{ time() }}" rel="stylesheet" />
		<link href="{{asset_image('assets/admin/plugins/fullside-menu/waves.min.css')}}" rel="stylesheet" />

		<link href="{{asset_image('assets/admin/css/plugin.css')}}" rel="stylesheet" />

		<link href="{{asset_image('assets/admin/css/jquery.tagit.css')}}" rel="stylesheet" />
    	<link rel="stylesheet" href="{{ asset_image('assets/admin/css/bootstrap-coloroicker.css') }}">
		<link rel="stylesheet" href="{{ asset_image('assets/admin/css/datetimepicker.css') }}">
		<link rel="stylesheet" href="{{ asset_image('assets/admin/css/bootstrap-tagsinput.css') }}">
				
		<link href="{{asset_image('assets/admin/css/tagify.css')}}" rel="stylesheet"/>

		<!-- Main Css -->

		<!-- stylesheet -->
		@if(DB::table('admin_languages')->where('is_default','=',1)->first()->rtl == 1)

		<link href="{{asset_image('assets/admin/css/rtl/style.css')}}" rel="stylesheet"/>
		<link href="{{asset_image('assets/admin/css/rtl/custom.css')}}" rel="stylesheet"/>
		<link href="{{asset_image('assets/admin/css/rtl/responsive.css')}}" rel="stylesheet" />
		<link href="{{asset_image('assets/admin/css/common.css')}}" rel="stylesheet" />

		@else

		<link href="{{asset_image('assets/admin/css/style.css')}}" rel="stylesheet"/>
		<link href="{{asset_image('assets/admin/css/custom.css')}}" rel="stylesheet"/>
		<link href="{{asset_image('assets/admin/css/responsive.css')}}" rel="stylesheet" />
		<link href="{{asset_image('assets/admin/css/common.css')}}" rel="stylesheet" />
		@endif

		<link href="{{asset_image('assets/admin/css/admin-pro.css')}}?v={{ time() }}" rel="stylesheet" />
		@include('admin.partials.pro-styles')

		<style>
		.gocover{display:none!important}
		.submit-loader{display:none!important}
		.page, .page body, .page input, .page textarea, .page select, .page button, .page label, .page .table {
			font-family: 'Hind Siliguri', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif !important;
		}

		/* =========================================================
		   MODERN TOPBAR HEADER
		   ========================================================= */
		.page .header {
			background: #ffffff !important;
			border-bottom: 1px solid #e2e8f0 !important;
			box-shadow: 0 1px 4px rgba(15, 23, 42, 0.04) !important;
			height: 66px !important;
			padding: 0 20px !important;
			z-index: 1000 !important;
			position: sticky !important;
			top: 0 !important;
		}
		.page .header .container-fluid {
			height: 100% !important;
			padding: 0 !important;
		}
		.admin-topbar-flex {
			display: flex !important;
			align-items: center !important;
			justify-content: space-between !important;
			height: 100% !important;
			gap: 16px !important;
		}
		.admin-topbar-left {
			display: flex !important;
			align-items: center !important;
			gap: 16px !important;
		}
		.admin-logo-link {
			display: inline-flex !important;
			align-items: center !important;
			text-decoration: none !important;
		}
		.admin-logo-img {
			height: 38px !important;
			max-height: 40px !important;
			width: auto !important;
			object-fit: contain !important;
		}
		.admin-toggle-btn {
			width: 38px !important;
			height: 38px !important;
			border-radius: 10px !important;
			border: 1px solid #e2e8f0 !important;
			background: #f8fafc !important;
			color: #334155 !important;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			cursor: pointer !important;
			transition: all 0.2s ease !important;
		}
		.admin-toggle-btn:hover {
			background: #f1f5f9 !important;
			color: #0f172a !important;
			border-color: #cbd5e1 !important;
		}
		.admin-visit-store-btn {
			display: inline-flex !important;
			align-items: center !important;
			gap: 8px !important;
			padding: 7px 14px !important;
			border-radius: 999px !important;
			background: #fff7ed !important;
			border: 1px solid #fed7aa !important;
			color: #ea580c !important;
			font-size: 0.82rem !important;
			font-weight: 700 !important;
			text-decoration: none !important;
			transition: all 0.2s ease !important;
		}
		.admin-visit-store-btn:hover {
			background: #ffedd5 !important;
			color: #c2410c !important;
			transform: translateY(-1px) !important;
		}
		.admin-topbar-right {
			display: flex !important;
			align-items: center !important;
			gap: 12px !important;
		}
		.admin-icon-btn {
			position: relative !important;
			width: 38px !important;
			height: 38px !important;
			border-radius: 10px !important;
			border: 1px solid #e2e8f0 !important;
			background: #f8fafc !important;
			color: #475569 !important;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			text-decoration: none !important;
			transition: all 0.2s ease !important;
			font-size: 0.95rem !important;
		}
		.admin-icon-btn:hover {
			background: #f1f5f9 !important;
			color: #ff5722 !important;
			border-color: #cbd5e1 !important;
		}
		.admin-icon-badge {
			position: absolute !important;
			top: -4px !important;
			right: -4px !important;
			background: #ff5722 !important;
			color: #ffffff !important;
			font-size: 0.68rem !important;
			font-weight: 700 !important;
			min-width: 18px !important;
			height: 18px !important;
			border-radius: 999px !important;
			display: inline-flex !important;
			align-items: center !important;
			justify-content: center !important;
			padding: 0 4px !important;
			border: 2px solid #ffffff !important;
		}
		.admin-user-pill {
			display: inline-flex !important;
			align-items: center !important;
			gap: 10px !important;
			padding: 5px 12px 5px 6px !important;
			border-radius: 999px !important;
			border: 1px solid #e2e8f0 !important;
			background: #ffffff !important;
			cursor: pointer !important;
			text-decoration: none !important;
			transition: all 0.2s ease !important;
		}
		.admin-user-pill:hover {
			background: #f8fafc !important;
			border-color: #cbd5e1 !important;
		}
		.admin-avatar-wrap {
			position: relative !important;
			width: 32px !important;
			height: 32px !important;
		}
		.admin-avatar-img {
			width: 100% !important;
			height: 100% !important;
			border-radius: 50% !important;
			object-fit: cover !important;
		}
		.admin-online-dot {
			position: absolute !important;
			bottom: -1px !important;
			right: -1px !important;
			width: 9px !important;
			height: 9px !important;
			border-radius: 50% !important;
			background: #10b981 !important;
			border: 2px solid #ffffff !important;
		}
		.admin-user-info {
			display: flex !important;
			flex-direction: column !important;
			line-height: 1.2 !important;
			text-align: left !important;
		}
		.admin-user-name {
			font-size: 0.84rem !important;
			font-weight: 700 !important;
			color: #0f172a !important;
		}
		.admin-user-role {
			font-size: 0.7rem !important;
			color: #ea580c !important;
			font-weight: 600 !important;
		}

		/* Dropdown Menu styling */
		.admin-profile-dropdown .dropdown-menu {
			border: 1px solid #e2e8f0 !important;
			border-radius: 14px !important;
			box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12) !important;
			padding: 8px !important;
			min-width: 210px !important;
			margin-top: 8px !important;
		}
		.admin-profile-dropdown .dropdown-item {
			padding: 9px 12px !important;
			border-radius: 8px !important;
			font-size: 0.85rem !important;
			font-weight: 600 !important;
			color: #334155 !important;
			display: flex !important;
			align-items: center !important;
			gap: 10px !important;
			transition: all 0.15s ease !important;
		}
		.admin-profile-dropdown .dropdown-item:hover {
			background: #f1f5f9 !important;
			color: #ff5722 !important;
		}
		.admin-profile-dropdown .dropdown-divider {
			margin: 6px 0 !important;
			border-top: 1px solid #f1f5f9 !important;
		}

		/* =========================================================
		   MODERN LIGHT HIGH-CONTRAST SIDEBAR (ABSOLUTE OVERRIDES)
		   ========================================================= */

		/* 1. Kill ALL legacy IcoFont arrows completely */
		#sidebar li a::before,
		#sidebar li a::after,
		#sidebar li a[aria-expanded]::before,
		#sidebar li a[aria-expanded]::after,
		.nav-sidebar li a::before,
		.nav-sidebar li a::after,
		.nav-sidebar li a[aria-expanded]::before,
		.nav-sidebar li a[aria-expanded]::after {
			display: none !important;
			content: none !important;
			opacity: 0 !important;
			visibility: hidden !important;
			width: 0 !important;
			height: 0 !important;
		}

		/* 2. Sidebar Root & Container */
		.page #sidebar.nav-sidebar,
		.page .wrapper #sidebar,
		#sidebar {
			background: #ffffff !important;
			border-right: 1px solid #e2e8f0 !important;
			min-height: calc(100vh - 66px) !important;
			width: 260px !important;
			padding: 16px 0 40px !important;
			box-shadow: 2px 0 12px rgba(15, 23, 42, 0.03) !important;
		}
		.page #sidebar.nav-sidebar ul.components,
		.page .wrapper #sidebar ul.components,
		#sidebar ul.components {
			background: transparent !important;
			padding: 0 10px !important;
		}

		/* 3. Section Titles */
		.sidebar-section-title {
			font-size: 0.72rem !important;
			font-weight: 800 !important;
			color: #64748b !important;
			text-transform: uppercase !important;
			letter-spacing: 0.06em !important;
			padding: 18px 14px 6px !important;
			list-style: none !important;
		}

		/* 4. Top Level Menu Links */
		.page #sidebar.nav-sidebar > ul.components > li > a,
		.page .wrapper #sidebar > ul.components > li > a,
		#sidebar > ul.components > li > a {
			color: #1e293b !important;
			font-weight: 600 !important;
			font-size: 0.88rem !important;
			padding: 10px 14px !important;
			border-radius: 10px !important;
			border: 1px solid transparent !important;
			display: flex !important;
			align-items: center !important;
			gap: 12px !important;
			transition: all 0.2s ease !important;
			margin-bottom: 2px !important;
			text-decoration: none !important;
			background: transparent !important;
		}
		.page #sidebar.nav-sidebar > ul.components > li > a:hover,
		.page .wrapper #sidebar > ul.components > li > a:hover,
		#sidebar > ul.components > li > a:hover {
			color: #ea580c !important;
			background: #fff7ed !important;
			transform: translateX(3px) !important;
		}
		.page #sidebar.nav-sidebar > ul.components > li > a:hover i,
		.page #sidebar.nav-sidebar > ul.components > li > a:hover span {
			color: #ea580c !important;
		}

		/* 5. Active / Expanded Parent Accordion (NEVER WHITE TEXT) */
		.page #sidebar.nav-sidebar > ul.components > li > a[aria-expanded="true"],
		.page .wrapper #sidebar > ul.components > li > a[aria-expanded="true"],
		#sidebar > ul.components > li > a[aria-expanded="true"] {
			background: #fff7ed !important;
			color: #ea580c !important;
			border: 1px solid #fed7aa !important;
			font-weight: 700 !important;
		}
		.page #sidebar.nav-sidebar > ul.components > li > a[aria-expanded="true"] span,
		.page #sidebar.nav-sidebar > ul.components > li > a[aria-expanded="true"] i {
			color: #ea580c !important;
		}
		.page #sidebar.nav-sidebar > ul.components > li > a[aria-expanded="true"] .sidebar-chevron {
			transform: rotate(90deg) !important;
			color: #ea580c !important;
		}

		/* 6. Direct Dashboard / Active Non-Accordion Link (NEVER BLUE GRADIENT) */
		.page #sidebar.nav-sidebar > ul.components > li.active > a,
		.page #sidebar.nav-sidebar > ul.components > li > a.is-current,
		.page .wrapper #sidebar > ul.components > li.active > a,
		.page #sidebar.nav-sidebar > ul.components > li:not(:has(ul.collapse)) > a.active,
		.page #sidebar.nav-sidebar > ul.components > li > a.active:not([data-toggle="collapse"]) {
			background: #fff7ed !important;
			color: #ea580c !important;
			border: 1px solid #fed7aa !important;
			font-weight: 700 !important;
		}
		.page #sidebar.nav-sidebar > ul.components > li.active > a i,
		.page #sidebar.nav-sidebar > ul.components > li > a.is-current i,
		.page #sidebar.nav-sidebar > ul.components > li.active > a span,
		.page #sidebar.nav-sidebar > ul.components > li > a.is-current span {
			color: #ea580c !important;
		}

		/* 7. Menu Icons */
		.page #sidebar.nav-sidebar > ul.components > li > a i:first-child,
		.page .wrapper #sidebar > ul.components > li > a i:first-child,
		#sidebar > ul.components > li > a i:first-child {
			width: 22px !important;
			font-size: 1rem !important;
			text-align: center !important;
			color: #ff5722 !important;
			margin-right: 0 !important;
		}

		/* 8. Chevron Icon */
		.sidebar-chevron {
			font-size: 0.72rem !important;
			margin-left: auto !important;
			transition: transform 0.2s ease !important;
			color: #94a3b8 !important;
		}

		/* 9. Submenu Container (CLEAN LIGHT BACKGROUND, NEVER DARK!) */
		.page #sidebar.nav-sidebar ul.collapse,
		.page #sidebar.nav-sidebar ul.collapse.show,
		.page .wrapper #sidebar ul.collapse,
		.page .wrapper #sidebar ul.collapse.show,
		#sidebar ul.collapse,
		#sidebar ul.collapse.show {
			background: #f8fafc !important;
			border: 1px solid #fed7aa !important;
			border-left: 3px solid #ff5722 !important;
			border-radius: 10px !important;
			margin: 4px 4px 10px 14px !important;
			padding: 6px 4px !important;
			box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04) !important;
		}

		/* 10. Submenu Items (Dark High-Contrast Text, Never White!) */
		.page #sidebar.nav-sidebar ul.collapse li > a,
		.page #sidebar.nav-sidebar ul.collapse li > a:visited,
		.page .wrapper #sidebar ul.collapse li > a,
		#sidebar ul.collapse li > a {
			color: #334155 !important;
			font-size: 0.83rem !important;
			font-weight: 500 !important;
			padding: 8px 12px !important;
			border-radius: 8px !important;
			display: flex !important;
			align-items: center !important;
			gap: 10px !important;
			transition: all 0.15s ease !important;
			text-decoration: none !important;
			background: transparent !important;
		}
		.page #sidebar.nav-sidebar ul.collapse li > a span,
		.page .wrapper #sidebar ul.collapse li > a span,
		#sidebar ul.collapse li > a span {
			color: #334155 !important;
		}
		.page #sidebar.nav-sidebar ul.collapse li > a:hover,
		.page .wrapper #sidebar ul.collapse li > a:hover,
		#sidebar ul.collapse li > a:hover {
			color: #ea580c !important;
			background: #fff7ed !important;
			padding-left: 16px !important;
		}
		.page #sidebar.nav-sidebar ul.collapse li > a:hover span,
		.page .wrapper #sidebar ul.collapse li > a:hover span,
		#sidebar ul.collapse li > a:hover span {
			color: #ea580c !important;
		}
		.page #sidebar.nav-sidebar ul.collapse li.active > a,
		.page .wrapper #sidebar ul.collapse li.active > a,
		#sidebar ul.collapse li.active > a {
			color: #ea580c !important;
			background: #fff7ed !important;
			font-weight: 700 !important;
		}
		.page #sidebar.nav-sidebar ul.collapse li.active > a span,
		.page .wrapper #sidebar ul.collapse li.active > a span,
		#sidebar ul.collapse li.active > a span {
			color: #ea580c !important;
		}
		.page #sidebar.nav-sidebar ul.collapse li > a i,
		.page .wrapper #sidebar ul.collapse li > a i,
		#sidebar ul.collapse li > a i {
			font-size: 0.65rem !important;
			color: #ff5722 !important;
		}
		</style>

		@yield('styles')

	</head>
	<body>
		<div class="page">
			<div class="page-main">
				<!-- Header Menu Area Start -->
				<header class="header">
					<div class="container-fluid">
						<div class="admin-topbar-flex">
							{{-- Topbar Left --}}
							<div class="admin-topbar-left">
								<a class="admin-logo-link" href="{{ route('admin.dashboard') }}" title="অ্যাডমিন ড্যাশবোর্ড">
									<img src="{{$gs->logo ? asset_image('assets/images/logo/'.$gs->logo) : asset_image('assets/front/images/logo.png')}}" 
									     alt="{{ $gs->title }}" 
									     class="admin-logo-img">
								</a>
								<button type="button" class="admin-toggle-btn" id="sidebarCollapse" title="মেনু টগল করুন" aria-label="Toggle Sidebar">
									<i class="fas fa-bars"></i>
								</button>
								<a href="{{ route('frontend.index') }}" target="_blank" class="admin-visit-store-btn" title="সরাসরি মূল ওয়েবসাইট ভিজিট করুন">
									<i class="fas fa-globe"></i>
									<span>লাইভ সাইট দেখুন</span>
									<i class="fas fa-arrow-up-right-from-square" style="font-size: 0.72rem;"></i>
								</a>
							</div>

							{{-- Topbar Right --}}
							<div class="admin-topbar-right">
								@php
									$unread_contact_count = \App\Models\Contact::count();
									$adminUser = Auth::guard('admin')->user();
								@endphp

								{{-- Messages Button --}}
								<a href="{{ route('admin.contact.index') }}" class="admin-icon-btn" title="গ্রাহকদের মেসেজসমূহ">
									<i class="far fa-envelope"></i>
									@if($unread_contact_count > 0)
										<span class="admin-icon-badge">{{ $unread_contact_count }}</span>
									@endif
								</a>

								{{-- Clear Cache Button --}}
								<a href="{{ route('admin.cache.clear') }}" class="admin-icon-btn" title="সিস্টেম ক্যাশ ক্লিয়ার করুন">
									<i class="fas fa-rotate"></i>
								</a>

								{{-- Admin Profile Dropdown --}}
								<div class="dropdown admin-profile-dropdown">
									<a class="admin-user-pill dropdown-toggle" href="#" role="button" id="adminUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<div class="admin-avatar-wrap">
											<img class="admin-avatar-img" 
											     src="{{ ($adminUser?->photo) ? asset_image('assets/images/admin/'.$adminUser->photo) : asset_image('assets/images/noimage.png') }}" 
											     alt="{{ $adminUser?->name }}">
											<span class="admin-online-dot"></span>
										</div>
										<div class="admin-user-info d-none d-sm-flex">
											<span class="admin-user-name">{{ $adminUser?->name ?? 'Admin' }}</span>
											<span class="admin-user-role">{{ $adminUser?->IsSuper() ? 'Super Admin' : 'Admin' }}</span>
										</div>
										<i class="fas fa-chevron-down text-muted" style="font-size: 0.7rem; margin-left: 4px;"></i>
									</a>

									<div class="dropdown-menu dropdown-menu-right" aria-labelledby="adminUserMenu">
										<div class="px-3 py-2 border-bottom mb-1 d-sm-none">
											<div class="fw-bold text-dark small">{{ $adminUser?->name }}</div>
											<div class="text-muted x-small">{{ $adminUser?->email }}</div>
										</div>
										<a class="dropdown-item" href="{{ route('admin.profile') }}">
											<i class="fas fa-user-gear text-primary"></i> <span>প্রোফাইল এডিট</span>
										</a>
										<a class="dropdown-item" href="{{ route('admin.password') }}">
											<i class="fas fa-key text-warning"></i> <span>পাসওয়ার্ড পরিবর্তন</span>
										</a>
										<a class="dropdown-item" href="{{ route('admin.generalsettings.websiteContent') }}">
											<i class="fas fa-gear text-info"></i> <span>জেনারেল সেটিংস</span>
										</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
											<i class="fas fa-power-off text-danger"></i> <span>লগআউট</span>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</header>
				<!-- Header Menu Area End -->
				<div class="wrapper">
					<!-- Side Menu Area Start -->
					<nav id="sidebar" class="nav-sidebar">
						<ul class="list-unstyled components" id="accordion">
							<li>
								<a href="{{ route('admin.dashboard') }}" class="wave-effect {{ request()->routeIs('admin.dashboard') ? 'is-current' : '' }}"><i class="fas fa-home"></i> <span>{{ __('Dashboard') }}</span></a>
							</li>
							@if(Auth::guard('admin')->user()?->IsSuper())
								@include('partial.admin-role.super')
							@else
								@include('partial.admin-role.normal')
							@endif
						</ul>

					</nav>
					<!-- Main Content Area Start -->
					@yield('content')
					<!-- Main Content Area End -->
					</div>
				</div>
			</div>

			<script>
				var mainurl = "{{url('/')}}/";
				var gs = '{{$gs}}'
			</script>
		<!-- Dashboard Core -->
		<script src="{{asset_image('assets/admin/js/vendors/jquery-1.12.4.min.js')}}"></script>
        <script src="{{asset_image('assets/admin/js/vendors/vue.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/vendors/bootstrap.min.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/jqueryui.min.js')}}"></script>
		<!-- Fullside-menu Js-->
		<script src="{{asset_image('assets/admin/plugins/fullside-menu/jquery.slimscroll.min.js')}}"></script>
		<script src="{{asset_image('assets/admin/plugins/fullside-menu/waves.min.js')}}"></script>

		<script src="{{asset_image('assets/admin/js/plugin.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/Chart.min.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/tag-it.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/nicEdit.js')}}"></script>
        <script src="{{asset_image('assets/admin/js/bootstrap-colorpicker.min.js') }}"></script>
        <script src="{{asset_image('assets/admin/js/notify.js') }}"></script>

        <script src="{{asset_image('assets/admin/js/jquery.canvasjs.min.js')}}"></script>

		<script src="{{asset_image('assets/admin/js/load.js')}}"></script>
		<!-- Custom Js-->
		<script src="{{asset_image('assets/admin/js/custom.js')}}"></script>
		<!-- AJAX Js-->
		<script src="{{asset_image('assets/admin/js/myscript.js')}}"></script>
		<!--bootstrap-taginput-->
		<script src="{{asset_image('assets/admin/js/bootstrap-tagsinput.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/jquery.validate.min.js')}}"></script>
		<script src="{{asset_image('assets/admin/js/additional-methods.min.js')}}"></script>
		
		{{-- <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
		{!! Toastr::message() !!} --}}
		
		<script src="{{asset_image('assets/admin/js/datetimepicker.js')}}"></script>
	
		<script src="{{asset_image('assets/admin/js/newspaper.js')}}"></script>
		<script>
			if (window.jQuery && $.fn.dataTable) {
				$.extend(true, $.fn.dataTable.defaults, {
					language: { processing: 'Loading...' }
				});
			}
			document.addEventListener('DOMContentLoaded', function () {
				document.querySelectorAll('.content-area:not(.admin-dashboard)').forEach(function (el) {
					el.classList.add('adm-pro');
				});
			});
		</script>
		@yield('scripts')


	</body>

</html>

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
		<title>{{__('NewsPaper')}}</title>
		<!-- favicon -->
		<link rel="shortcut icon" href="{{asset_image('assets/images/'.$gs->favicon)}}" type="image/x-icon">
		<!-- Bootstrap -->
		<link href="{{asset_image('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" />
		<!-- Fontawesome -->
		<link rel="stylesheet" href="{{asset_image('assets/admin/css/fontawesome.css')}}">
		<!-- icofont -->
		<link rel="stylesheet" href="{{asset_image('assets/admin/css/icofont.min.css')}}">
		<link rel="stylesheet" href="{{asset_image('assets/admin/css/jquery-ui.css')}}">
		<!-- Sidemenu Css -->
		<link href="{{asset_image('assets/admin/plugins/fullside-menu/css/dark-side-style.css')}}" rel="stylesheet" />
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

		@yield('styles')

	</head>
	<body>
		{{-- অ্যাডমিন ইমপার্সোনেশন ব্যানার --}}
		@if(session('admin_impersonating'))
		<div style="background: #e74c3c; color: #fff; padding: 10px 20px; text-align: center; font-weight: bold; position: sticky; top: 0; z-index: 9999; display: flex; align-items: center; justify-content: center; gap: 15px; font-size: 14px;">
			<i class="fas fa-user-secret" style="font-size: 18px;"></i>
			<span>আপনি অ্যাডমিন হিসেবে <strong>"{{ auth()->user()->name }}"</strong> এর অ্যাকাউন্ট পরিচালনা করছেন</span>
			<a href="{{ route('admin.return_to_admin') }}"
			   style="background: #fff; color: #e74c3c; padding: 5px 16px; border-radius: 4px; text-decoration: none; font-weight: bold; font-size: 13px; border: none; cursor: pointer;">
				<i class="fas fa-arrow-left"></i> অ্যাডমিন প্যানেলে ফিরুন
			</a>
		</div>
		@endif
		<div class="page">
			<div class="page-main">
				<!-- Header Menu Area Start -->
				<div class="header">
					<div class="container-fluid">
						<div class="d-flex justify-content-between align-self-center">
							<a class="admin-logo" href="{{ route('admin.dashboard') }}">
								<img src="{{$gs->logo ? asset_image('assets/images/logo/'.$gs->logo) : asset_image('assets/front/images/logo.png')}}" alt="">
							</a>
							<div class="menu-toggle-button">
								<a class="nav-link" href="javascript:;" id="sidebarCollapse">
									<div class="my-toggl-icon">
											<span class="bar1"></span>
											<span class="bar2"></span>
											<span class="bar3"></span>
									</div>
								</a>
							</div>

							<div class="right-eliment">
								<ul class="list">

									<li class="bell-area">
										<a id="notf_order" class="dropdown-toggle-1" href="{{route('frontend.index')}}" target="_blank">
											<i class="far fa-newspaper"></i>
											<span data-href="" id="order-notf-count"></span>{{__('View Site')}}
										</a>
										<div class="dropdown-menu">
											<div class="dropdownmenu-wrapper" data-href="" id="order-notf-show">
										</div>
										</div>
									</li>

									<li class="login-profile-area">
										<a class="dropdown-toggle-1" href="javascript:;">
											<div class="user-img">
												@php
													$data = auth()->user();
												@endphp
												<img src="{{ ($data?->photo) ? asset_image('assets/images/admin/'.$data->photo) : asset_image('assets/images/noimage.png') }}" alt="">
											</div>
										</a>
										<div class="dropdown-menu">
											<div class="dropdownmenu-wrapper">
												<ul>
													<h5>{{ __('Welcome!') }}</h5>
													<li>
														<a href="{{route('user.profile')}}"><i class="fas fa-user"></i> {{ __('Edit Profile') }}</a>
													</li>
													<li>
														<a href="{{route('user.password')}}"><i class="fas fa-cog"></i> {{ __('Change Password') }}</a>
													</li>
													<li>
														<a href="{{ route('user.logout') }}"><i class="fas fa-power-off"></i> {{ __('Logout') }}</a>
													</li>
												</ul>
											</div>
										</div>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<!-- Header Menu Area End -->
				<div class="wrapper">
					<!-- Side Menu Area Start -->
					<nav id="sidebar" class="nav-sidebar">
						<ul class="list-unstyled components" id="accordion">
							<li>
								<a href="{{ route('user.dashboard') }}" class="wave-effect"><i class="fa fa-home mr-2"></i>{{ __('Dashboard') }}</a>
							</li>

                            <li>
                                <a href="{{ route('user.post.format') }}" >
                                    <i class="fa fa-file"></i>{{ __('Add Post') }}
                                </a>
                            </li>

                            <li>
								<a href="{{ route('user.post.index') }}" class="wave-effect"><i class="fa fa-bars"></i>{{ __('Posts') }}</a>
							</li>


                            <li>
                                <a href="{{ route('user.schedule.index') }}"><span><i class="fa fa-calendar" aria-hidden="true"></i>{{ __('Schedule Post') }}</span></a>
                            </li>

                            <li>
                                <a href="{{ route('user.draft.index') }}"><span><i class="fab fa-firstdraft"></i>{{ __('Drafts') }}</span></a>
                            </li>

                            <li>
                                <a href="{{ route('user.cache.clear') }}" class=" wave-effect"><i class="fa fa-database"></i>{{ __('Clear Cache') }}</a>
                            </li>
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
		@yield('scripts')


	</body>

</html>

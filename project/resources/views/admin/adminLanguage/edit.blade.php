@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-globe',
        'title' => __('Edit Language'),
        'subtitle' => __('Update admin language keys and values'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Language Settings'), 'url' => null],
            ['label' => __('Edit Language'), 'url' => null],
        ],
        'action' => [
            'type' => 'link',
            'url' => route('admin.admin_language.index'),
            'label' => __('Back'),
            'icon' => 'fas fa-arrow-left',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-edit"></i> {{ __('Language Details') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="add-product-content">

                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('includes.admin.form-error')
                        @include('includes.admin.form-success')
                      <div class="gocover"></div>
                      <form id="geniusform" action="{{route('admin.admin_language.update',$data->id)}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}

                         <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('English') }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="language" placeholder="{{ __('Language') }}" required="" value="{{$data->language}}">
                          </div>
                        </div>
                        
                        
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Language Direction') }} *</h4>

                            </div>
                          </div>
                          <div class="col-lg-7">
                            <select name="rtl" class="input-field" required="">
                              <option value="0" {{ $data->rtl == '0'  ? 'selected' : '' }}>{{ __('Left To Right') }}</option>
                              <option value="1" {{ $data->rtl == '1'  ? 'selected' : '' }}>{{ __('Right To Left') }}</option>
                            </select>
                          </div>
                        </div>


                      <hr>
                        
                        <h4 class="text-center">{{ __('SET LANGUAGE KEYS & VALUES') }}</h4>

                      <hr>

                        <div class="row">
                          <div class="col-lg-2">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-8">
                            <div class="featured-keyword-area">

                              <div class="lang-tag-top-filds" id="lang-section">


                              @foreach($lang as $key => $val)

                                <div class="lang-area">
                                  <span class="remove lang-remove"><i class="fas fa-times"></i></span>
                                  <div class="row">
                                    <div class="col-lg-6">
                                      <textarea name="keys[]" class="input-field" placeholder="{{ __('Enter Language Key') }}" required="">{{ $key }}</textarea>
                                    </div>

                                    <div class="col-lg-6">
                                      <textarea  name="values[]" class="input-field" placeholder="{{ __('Enter Language Value') }}" required="">{{ $val }}</textarea>
                                    </div>
                                  </div>
                                </div>

                              @endforeach

                              </div>

                              <a href="javascript:;" id="lang-btn" class="add-fild-btn"><i class="icofont-plus"></i> {{ __('Add More Field') }}</a>
                            </div>
                          </div>

                          <div class="col-lg-2">
                            <div class="left-area">

                            </div>
                          </div>

                        </div>


                      
                        <div class="row">
                          <div class="col-lg-5">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                          </div>
                        </div>
                      </form>

                      </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
  "use strict";
  var lang = {
    'language_key':'{{ __('Enter Language Key') }}',
    'language_value':'{{ __('Enter Language Key') }}',
  }
</script>
<script src="{{asset_image('assets/admin/js/language.js')}}"></script>
@endsection
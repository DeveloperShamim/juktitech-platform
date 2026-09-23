@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-globe',
        'title' => __('Website Contents'),
        'subtitle' => __('Manage site content and integrations'),
        'breadcrumbs' => [['label' => __('Dashboard'), 'url' => route('admin.dashboard')], ['label' => __('General Settings'), 'url' => null], ['label' => __('Website Contents'), 'url' => null]],
    ])

    @include('includes.admin.form-both')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-globe"></i> {{ __('Website Contents') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product-description">
                            <div class="body-area">
                                <div class="gocover"></div>
                      <form class="uplogo-form" id="geniusform" action="{{ route('admin.generalsettings.update')}}"  method="POST" enctype="multipart/form-data">
                          {{ csrf_field() }}

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Website Title') }} *
                                  </h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <input type="text" class="input-field" placeholder="{{ __('Write Your Site Title Here') }}" name="title" value="{{$data->title}}" required="">
                          </div>
                        </div>
						
						<div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Mobile Number') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <input type="text" class="input-field" placeholder="{{ __('Write Your Mobile Number') }}" name="phone" value="{{$data->phone}}">
                          </div>
                        </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Address') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <textarea class="input-field" placeholder="{{ __('Write Your Address') }}" name="address">{{$data->address}}</textarea>
                          </div>
                        </div>

						
                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Email') }} *
                                  </h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <input type="text" class="input-field" placeholder="{{ __('Write Your Email Address') }}" name="email" value="{{$data->email}}" required="">
                          </div>
                        </div>
						
						
						                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Footer Details') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <textarea class="input-field" placeholder="{{ __('Write Your Details') }}" name="footer_details">{{$data->footer_details}}</textarea>
                          </div>
                        </div>
						
						
						
						
						
						
						
						

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Primary Color') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group">
                                <div class="input-group colorpicker-component cp">
                                <input type="text" class="form-control input-field color-field cp" name="theme_color" value="{{$data->theme_color}}"/>
                                  <span class="input-group-addon"><i></i></span>
                                </div>
                              </div>

                          </div>
                        </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Secondery Color') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group">
                                <div class="input-group colorpicker-component cp">
                                  <input type="text" class="form-control input-field color-field cp" name="footer_color" value="{{$data->footer_color}}" />
                                  <span class="input-group-addon"><i></i></span>
                                </div>
                              </div>
                          </div>
                        </div>

                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Footer Color') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group">
                                <div class="input-group colorpicker-component cp">
                                <input type="text" class="form-control input-field color-field cp" name="copyright_color" value="{{$data->copyright_color}}"/>
                                  <span class="input-group-addon"><i></i></span>
                                </div>
                              </div>

                          </div>
                        </div>
						
						
						
						                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Facebook Page URL') }} *
                                  </h4>
                            </div>
                          </div>
                          <div class="col-lg-6">
                            <input type="text" class="input-field" placeholder="{{ __('Write Your Page URL') }}" name="facebook_page_link" value="{{$data->facebook_page_link}}" required="">
                          </div>
                        </div>
						 
						
						
						<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">{{ __('SMS API Key') }} *</h4>
            <p class="sub-heading">{{ __('Get this from bulksmsbd.net') }}</p>
        </div>
    </div>
    <div class="col-lg-6">
        <input type="text" class="input-field" placeholder="{{ __('Enter Your SMS API Key') }}" name="sms_api_key" value="{{$data->sms_api_key}}">
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">{{ __('SMS Sender ID') }} *</h4>
            <p class="sub-heading">{{ __('Approved Sender ID') }}</p>
        </div>
    </div>
    <div class="col-lg-6">
        <input type="text" class="input-field" placeholder="{{ __('Enter Your SMS Sender ID') }}" name="sms_sender_id" value="{{$data->sms_sender_id}}">
    </div>
</div>

				{{-- CreativePay (নিজস্ব পেমেন্ট সিস্টেম) --}}
<div class="row justify-content-center mt-4">
    <div class="col-lg-9">
        <h4 class="heading mb-3"><i class="fas fa-wallet text-primary"></i> CreativePay সেটিংস</h4>
        <p class="sub-heading mb-4">SMS App Auto Verify অথবা Manual Send Money — একটি মোড বেছে নিন।</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">পেমেন্ট মোড *</h4>
            <p class="sub-heading">SMS = অটো verify · Manual = Admin approve</p>
        </div>
    </div>
    <div class="col-lg-6">
        <select class="input-field" name="payment_mode">
            <option value="sms" {{ ($data->payment_mode ?? 'sms') === 'sms' ? 'selected' : '' }}>SMS App Auto Verify (Recommended)</option>
            <option value="manual" {{ ($data->payment_mode ?? '') === 'manual' ? 'selected' : '' }}>Manual — Admin Approve</option>
        </select>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-lg-9">
        <div class="alert alert-success small mb-0">
            <strong>SMS App Mode:</strong> মোবাইলে <code>creativepay-sms-app</code> ইন্সটল করুন।
            bKash/Nagad SMS background-এ read করে server-এ পাঠাবে। TrxID + amount match হলে auto verify।
        </div>
    </div>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">CreativePay API Key *</h4>
            <p class="sub-heading">Mobile SMS App + Webhook security (যেকোনো র‍্যান্ডম secret key)</p>
        </div>
    </div>
    <div class="col-lg-6">
        <input type="text" class="input-field" placeholder="your-secret-api-key" name="uddoktapay_api_key" value="{{ $data->uddoktapay_api_key }}">
    </div>
</div>

<hr class="col-lg-9 my-4">
<p class="col-lg-9 text-muted small mb-3"><strong>পেমেন্ট নম্বর ও একাউন্ট টাইপ</strong> — Checkout page-এ সঠিক instruction দেখাবে:</p>

<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">bKash নম্বর</h4>
            <p class="sub-heading">Personal / Merchant / Agent</p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-7">
                <input type="text" class="input-field" placeholder="01XXXXXXXXX" name="payment_bkash_number" value="{{ $data->payment_bkash_number ?? '' }}">
            </div>
            <div class="col-md-5">
                <select class="input-field" name="payment_bkash_account_type">
                    <option value="personal" {{ ($data->payment_bkash_account_type ?? 'personal') === 'personal' ? 'selected' : '' }}>Personal (পার্সোনাল)</option>
                    <option value="merchant" {{ ($data->payment_bkash_account_type ?? '') === 'merchant' ? 'selected' : '' }}>Merchant (মার্চেন্ট)</option>
                    <option value="agent" {{ ($data->payment_bkash_account_type ?? '') === 'agent' ? 'selected' : '' }}>Agent (এজেন্ট)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">Nagad নম্বর</h4>
            <p class="sub-heading">Personal / Merchant / Agent</p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-7">
                <input type="text" class="input-field" placeholder="01XXXXXXXXX" name="payment_nagad_number" value="{{ $data->payment_nagad_number ?? '' }}">
            </div>
            <div class="col-md-5">
                <select class="input-field" name="payment_nagad_account_type">
                    <option value="personal" {{ ($data->payment_nagad_account_type ?? 'personal') === 'personal' ? 'selected' : '' }}>Personal (পার্সোনাল)</option>
                    <option value="merchant" {{ ($data->payment_nagad_account_type ?? '') === 'merchant' ? 'selected' : '' }}>Merchant (মার্চেন্ট)</option>
                    <option value="agent" {{ ($data->payment_nagad_account_type ?? '') === 'agent' ? 'selected' : '' }}>Agent (এজেন্ট)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">Rocket নম্বর</h4>
            <p class="sub-heading">Personal / Merchant / Agent</p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="row">
            <div class="col-md-7">
                <input type="text" class="input-field" placeholder="01XXXXXXXXX" name="payment_rocket_number" value="{{ $data->payment_rocket_number ?? '' }}">
            </div>
            <div class="col-md-5">
                <select class="input-field" name="payment_rocket_account_type">
                    <option value="personal" {{ ($data->payment_rocket_account_type ?? 'personal') === 'personal' ? 'selected' : '' }}>Personal (পার্সোনাল)</option>
                    <option value="merchant" {{ ($data->payment_rocket_account_type ?? '') === 'merchant' ? 'selected' : '' }}>Merchant (মার্চেন্ট)</option>
                    <option value="agent" {{ ($data->payment_rocket_account_type ?? '') === 'agent' ? 'selected' : '' }}>Agent (এজেন্ট)</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center" id="autoApproveRow">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">অটো অনুমোদন (Manual mode only)</h4>
            <p class="sub-heading">শুধু <strong>Manual — Admin Approve</strong> মোডে কাজ করে</p>
        </div>
    </div>
    <div class="col-lg-6">
        <label class="switch">
            <input type="checkbox" name="payment_auto_approve" value="1"
                {{ ($data->payment_mode ?? 'sms') === 'manual' && !empty($data->payment_auto_approve) ? 'checked' : '' }}
                id="paymentAutoApprove"
                {{ ($data->payment_mode ?? 'sms') !== 'manual' ? 'disabled' : '' }}>
            <span class="slider round"></span>
        </label>
        <small id="autoApproveHint" class="d-block mt-2 text-muted">
            @if(($data->payment_mode ?? 'sms') !== 'manual')
                Manual মোড সিলেক্ট করলে এই অপশন চালু করা যাবে।
            @else
                Manual পেমেন্টে SMS match হলে অর্ডার অটো Paid হবে।
            @endif
        </small>
    </div>
</div>
<script>
(function () {
    const modeSelect = document.querySelector('select[name="payment_mode"]');
    const row = document.getElementById('autoApproveRow');
    const chk = document.getElementById('paymentAutoApprove');
    const hint = document.getElementById('autoApproveHint');

    function syncAutoApproveUi() {
        if (!modeSelect || !chk) return;

        const isManual = modeSelect.value === 'manual';

        if (row) {
            row.style.opacity = isManual ? '1' : '0.65';
        }

        chk.disabled = !isManual;

        if (!isManual) {
            chk.checked = false;
            if (hint) {
                hint.textContent = 'Manual মোড সিলেক্ট করলে এই অপশন চালু করা যাবে।';
            }
        } else if (hint) {
            hint.textContent = 'Manual পেমেন্টে SMS match হলে অর্ডার অটো Paid হবে।';
        }
    }

    modeSelect?.addEventListener('change', syncAutoApproveUi);
    syncAutoApproveUi();
})();
</script>

<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">পেমেন্ট নির্দেশনা</h4>
            <p class="sub-heading">চেকআউট পেজে দেখাবে</p>
        </div>
    </div>
    <div class="col-lg-6">
        <textarea class="input-field" rows="3" name="payment_instructions" placeholder="Send Money করুন, তারপর TrxID দিন...">{{ $data->payment_instructions ?? '' }}</textarea>
    </div>
</div>

{{-- লাইসেন্স জেনারেশন চার্জ --}}
<div class="row justify-content-center">
    <div class="col-lg-3">
        <div class="left-area">
            <h4 class="heading">লাইসেন্স জেনারেশন চার্জ <i class="fas fa-key text-primary"></i></h4>
            <p class="sub-heading">প্রতি বছরের জন্য লাইসেন্স ইস্যু চার্জ (টাকা)। ইউজার ফ্রি না থাকলে এই পরিমাণ পেমেন্ট করতে হবে।</p>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">৳</span>
            </div>
            <input type="number" step="0.01" min="0" class="input-field" placeholder="1000" name="license_generation_price" value="{{ $data->license_generation_price ?? 1000 }}">
        </div>
        <small class="text-muted">বর্তমান মূল্য: ৳{{ number_format($data->license_generation_price ?? 1000, 2) }} প্রতি বছর</small>
    </div>
</div>



					<div class="row justify-content-center">
                              <div class="col-lg-3">
                                <div class="left-area">
                                    <h4 class="heading">
                                        Search Console Verification Code
                                        <p class="sub-heading">{{(__('In Any Language'))}}</p>
                                    </h4>
                                  
                                </div>
                              </div>
                              <div class="col-lg-6">
                                  <div class="tawk-area">
                                  <textarea name="search_console" required="">{{$data->search_console}}</textarea>
                                  </div>
                              </div>
                            </div>
							
		

                    


                            <div class="row justify-content-center">
                            <div class="col-lg-3">
                              <div class="left-area">
                                  <h4 class="heading">{{ __('TimeZone') }} *
                                    </h4>
                              </div>
                            </div>
                            <div class="col-lg-6">
                              @php
                                $timezone_identifiers =
                                    DateTimeZone::listIdentifiers(DateTimeZone::ALL);

                                echo "<select name='time_zone'>";

                                echo "<option disabled selected>
                                        Please Select Timezone
                                      </option>";

                                $n = 419;
                                for($i = 0; $i < $n; $i++) {
                                  if($data->time_zone == $timezone_identifiers[$i]){
                                        $msg = 'selected';
                                    }else{
                                        $msg = '';
                                    }
                                    echo "<option value='" . $timezone_identifiers[$i] ."' ".$msg.">" . $timezone_identifiers[$i] . "</option>";
                                }

                                echo "</select>";
                              @endphp
                            </div>
                          </div>





                        <div class="row justify-content-center">
                          <div class="col-lg-3">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-6">
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
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset_image('assets/admin/js/notify.js')}}"></script>
<script src="{{asset_image('assets/admin/js/distawk.js')}}"></script>
@endsection


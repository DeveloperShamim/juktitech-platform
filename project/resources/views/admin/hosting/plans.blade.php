@extends('layouts.admin')

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-box-open',
        'title' => __('Hosting Plans'),
        'subtitle' => __('Manage hosting packages and pricing'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Hosting Management'), 'url' => null],
            ['label' => __('Plans'), 'url' => null],
        ],
        'action' => [
            'type' => 'modal',
            'label' => __('Create New Package'),
            'icon' => 'fas fa-plus',
            'modal_target' => '#addPlanModal',
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    <div class="adm-panel">
        <div class="adm-panel__head">
            <h3 class="adm-panel__title"><i class="fas fa-list"></i> {{ __('Package List') }}</h3>
        </div>
        <div class="adm-panel__body">
            <div class="table-responsiv px-3 pb-3">
                <table id="geniustable" class="table table-hover dt-responsive w-100" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('Package Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Free Domain') }}</th>
                            <th>{{ __('Price') }}</th>
                            <th>{{ __('Server') }}</th>
                            <th class="text-right">{{ __('Options') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($plans as $plan)
                        @php
                            $rawExtensions = $plan->free_domain_extensions;
                            $allowedIds = [];

                            if (is_array($rawExtensions)) {
                                $allowedIds = $rawExtensions;
                            } elseif (is_string($rawExtensions)) {
                                $decoded = json_decode($rawExtensions, true);
                                if (is_array($decoded)) {
                                    $allowedIds = $decoded;
                                }
                            }
                        @endphp
                        <tr>
                            <td>
                                <div class="font-weight-bold text-primary">{{ $plan->name }}</div>
                                <small class="text-muted"><i class="fas fa-cube"></i> WHM: {{ $plan->whm_package_name }}</small>
                            </td>
                            <td>
                                <span class="badge badge-info px-2 py-1">{{ $plan->category }}</span>
                            </td>
                            <td>
                                @if($plan->free_domain)
                                    @php
                                        $names = $extensions->whereIn('id', $allowedIds)->pluck('extension')->implode(', ');
                                    @endphp
                                    <span class="badge badge-success" data-toggle="tooltip" title="Allowed: {{ $names }}">
                                        <i class="fas fa-check"></i> Yes
                                    </span>
                                @else
                                    <span class="badge badge-secondary"><i class="fas fa-times"></i> No</span>
                                @endif
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark">৳ {{ number_format((float) $plan->price_yearly, 0) }} <small class="text-muted">/yr</small></div>
                                @if((float) $plan->price_monthly > 0)
                                    <small class="text-primary">৳ {{ number_format((float) $plan->price_monthly, 0) }} /mo</small>
                                @else
                                    <small class="text-muted">মাসিক সেট নেই</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-light border">{{ $plan->server->name ?? 'N/A' }}</span>
                            </td>
                            <td class="text-right">
                                <div class="action-btns">
                                    <a href="javascript:;" data-toggle="modal" data-target="#editPlan{{ $plan->id }}" class="btn-action edit" title="{{ __('Edit') }}">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#deleteModal{{ $plan->id }}" class="btn-action delete" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editPlan{{ $plan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-warning text-white">
                                        <h5 class="modal-title"><i class="fas fa-edit mr-2"></i>{{ __('Edit Package') }}: {{ $plan->name }}</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('admin.hosting.plans.update', $plan->id) }}" method="POST">
                                        @csrf
                                        <div class="modal-body p-4 text-left">
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">প্যাকেজের নাম</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $plan->name }}" required>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">WHM প্যাকেজ নাম</label>
                                                    <input type="text" name="whm_package_name" class="form-control" value="{{ $plan->whm_package_name }}" readonly>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">সার্ভার</label>
                                                    <select name="server_id" class="form-control">
                                                        @foreach($servers as $srv)
                                                            <option value="{{ $srv->id }}" {{ $srv->id == $plan->server_id ? 'selected' : '' }}>{{ $srv->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <label class="font-weight-bold">ক্যাটাগরি</label>
                                                    <select name="category" class="form-control">
                                                        <option value="Shared" {{ $plan->category == 'Shared' ? 'selected' : '' }}>Shared Hosting</option>
                                                        <option value="Reseller" {{ $plan->category == 'Reseller' ? 'selected' : '' }}>Reseller Hosting</option>
                                                        <option value="VPS" {{ $plan->category == 'VPS' ? 'selected' : '' }}>VPS Hosting</option>
                                                        <option value="Dedicated" {{ $plan->category == 'Dedicated' ? 'selected' : '' }}>Dedicated Server</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="card bg-light border p-3 my-3">
                                                <div class="custom-control custom-checkbox mb-2">
                                                    <input type="checkbox" class="custom-control-input free-domain-check" id="editFreeDomain{{ $plan->id }}" name="free_domain" value="1" {{ $plan->free_domain ? 'checked' : '' }} data-target="#editExtSection{{ $plan->id }}">
                                                    <label class="custom-control-label font-weight-bold text-success" for="editFreeDomain{{ $plan->id }}">
                                                        <i class="fas fa-gift mr-1"></i> এই প্যাকেজের সাথে ফ্রি ডোমেইন অফার আছে?
                                                    </label>
                                                </div>
                                                <div id="editExtSection{{ $plan->id }}" class="free-domain-ext-section {{ $plan->free_domain ? '' : 'd-none' }}">
                                                    <label class="font-weight-bold small">কোন এক্সটেনশনগুলো ফ্রি? (Ctrl চেপে একাধিক সিলেক্ট করুন)</label>
                                                    <select name="free_domain_extensions[]" class="form-control free-domain-select" multiple>
                                                        @foreach($extensions as $ext)
                                                            <option value="{{ $ext->id }}" {{ in_array($ext->id, $allowedIds) ? 'selected' : '' }}>
                                                                {{ $ext->extension }} ({{ $ext->registration_price }} ৳)
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6 form-group">
                                                    <label class="font-weight-bold">মাসিক দাম</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                                        <input type="number" name="price_monthly" class="form-control" value="{{ $plan->price_monthly }}" min="0" step="1" placeholder="মাসিক প্ল্যানের জন্য দাম দিন">
                                                    </div>
                                                    <small class="text-muted">মাসিক অপশন চালু করতে ০ এর বেশি দাম দিন</small>
                                                </div>
                                                <div class="col-6 form-group">
                                                    <label class="font-weight-bold">বাৎসরিক দাম <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                                        <input type="number" name="price_yearly" class="form-control" value="{{ $plan->price_yearly }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="font-weight-bold">বিবরণ</label>
                                                <textarea name="description" class="form-control" rows="3">{{ $plan->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer bg-light">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                            <button type="submit" class="btn btn-primary px-4">{{ __('Update Package') }}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="deleteModal{{ $plan->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header border-0">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body text-center pb-5">
                                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                                        <h4 class="mb-3">{{ __('Are you sure?') }}</h4>
                                        <p class="text-muted">{{ __('You are about to delete') }} <strong>{{ $plan->name }}</strong>.</p>
                                    </div>
                                    <div class="modal-footer justify-content-center border-0">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                                        <a href="{{ route('admin.hosting.plans.delete', $plan->id) }}" class="btn btn-danger">{{ __('Confirm Delete') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addPlanModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle mr-2"></i>{{ __('Add New Package') }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.hosting.plans.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">প্যাকেজের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Starter Plan" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">WHM প্যাকেজ নাম <span class="text-danger">*</span></label>
                            <input type="text" name="whm_package_name" class="form-control" placeholder="e.g. myuser_starter" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">কোন সার্ভারের প্যাকেজ? <span class="text-danger">*</span></label>
                            <select name="server_id" class="form-control">
                                @foreach($servers as $server)
                                    <option value="{{ $server->id }}">{{ $server->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">প্যাকেজ ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category" class="form-control">
                                <option value="Shared">Shared Hosting</option>
                                <option value="Reseller">Reseller Hosting</option>
                                <option value="VPS">VPS Hosting</option>
                                <option value="Dedicated">Dedicated Server</option>
                            </select>
                        </div>
                    </div>
                    <div class="card bg-light border p-3 my-3">
                        <div class="custom-control custom-checkbox mb-2">
                            <input type="checkbox" class="custom-control-input free-domain-check" id="addFreeDomain" name="free_domain" value="1" data-target="#addExtSection">
                            <label class="custom-control-label font-weight-bold text-success" for="addFreeDomain">
                                <i class="fas fa-gift mr-1"></i> এই প্যাকেজের সাথে ফ্রি ডোমেইন অফার আছে?
                            </label>
                        </div>
                        <div id="addExtSection" class="free-domain-ext-section d-none">
                            <label class="font-weight-bold small">কোন এক্সটেনশনগুলো ফ্রি? (Ctrl চেপে একাধিক সিলেক্ট করুন)</label>
                            <select name="free_domain_extensions[]" class="form-control free-domain-select" multiple>
                                @foreach($extensions as $ext)
                                    <option value="{{ $ext->id }}">{{ $ext->extension }} ({{ $ext->registration_price }} ৳)</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 form-group">
                            <label class="font-weight-bold">মাসিক দাম</label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                <input type="number" name="price_monthly" class="form-control" placeholder="মাসিক প্ল্যানের জন্য দাম দিন" min="0" step="1">
                            </div>
                            <small class="text-muted">মাসিক অপশন চালু করতে ০ এর বেশি দাম দিন</small>
                        </div>
                        <div class="col-6 form-group">
                            <label class="font-weight-bold">বাৎসরিক দাম <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">৳</span></div>
                                <input type="number" name="price_yearly" class="form-control" placeholder="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="font-weight-bold">বিবরণ</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="প্যাকেজের বিস্তারিত বিবরণ লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="submit" class="btn btn-primary px-4">{{ __('Save Package') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .adm-pro .free-domain-select { height: 100px; }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.free-domain-check').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const targetDiv = document.querySelector(this.getAttribute('data-target'));
            if (!targetDiv) return;
            if (this.checked) {
                targetDiv.classList.remove('d-none');
            } else {
                targetDiv.classList.add('d-none');
            }
        });
    });
});
</script>
@endsection

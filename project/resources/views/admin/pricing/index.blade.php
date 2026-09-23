@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">
    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-tags', 'title' => __('Pricing Plans'),
        'subtitle' => __('প্রাইসিং প্ল্যান ও প্যাকেজ ম্যানেজ করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Pricing'), 'url' => null],
        ],
    ])
    @include('includes.admin.form-error')
    @include('includes.admin.form-success')
    @include('admin.partials.frontend-setup-nav')

    <div class="fe-pricing-layout">
        <div class="fe-card">
            <div class="fe-card__head"><h3><i class="fas fa-plus"></i> {{ __('Add New Plan') }}</h3></div>
            <div class="fe-card__body">
                <form action="{{ route('admin.pricing.store') }}" method="POST" class="adm-form">
                    @csrf
                    <div class="adm-form__field"><label>{{ __('Plan Title') }} *</label><input type="text" class="input-field" name="title" placeholder="স্টার্টার" required></div>
                    <div class="adm-form__field"><label>{{ __('Price') }} *</label><input type="text" class="input-field" name="price" placeholder="৩,৫০০" required></div>
                    <div class="adm-form__field"><label>{{ __('Features') }} *</label><textarea class="input-field" name="features" rows="4" required placeholder="ফিচার ১, ফিচার ২"></textarea><small>কমা (,) দিয়ে আলাদা করুন</small></div>
                    <div class="adm-form__field"><label>{{ __('Order Link') }}</label><input type="url" class="input-field" name="order_link" placeholder="https://"></div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="is_featured" name="is_featured" value="1">
                        <label class="custom-control-label font-weight-bold" for="is_featured">{{ __('Best Value') }}</label>
                    </div>
                    <button class="fe-save w-100" type="submit" style="justify-content:center;"><i class="fas fa-plus"></i> {{ __('Create Plan') }}</button>
                </form>
            </div>
        </div>

        <div class="fe-card">
            <div class="fe-card__head">
                <h3><i class="fas fa-list"></i> {{ __('All Plans') }}</h3>
                <span class="fe-badge-count">{{ count($plans) }} {{ __('plans') }}</span>
            </div>
            <div class="fe-card__body" style="padding:0 16px 16px;">
                <div class="table-responsiv">
                    <table class="table table-hover dt-responsive w-100">
                        <thead><tr><th>{{ __('Title') }}</th><th>{{ __('Price') }}</th><th>{{ __('Featured') }}</th><th class="text-right">{{ __('Options') }}</th></tr></thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td class="font-weight-bold">{{ $plan->title }}</td>
                                <td>{{ $plan->currency }}{{ $plan->price }}</td>
                                <td>{!! $plan->is_featured ? '<span class="badge badge-success">BEST</span>' : '<span class="badge badge-secondary">Normal</span>' !!}</td>
                                <td class="text-right">
                                    <div class="action-btns">
                                        <a href="{{ route('admin.pricing.edit', $plan->id) }}" class="btn-action edit"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete-{{ $plan->id }}" class="btn-action delete"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                    @include('admin.partials.delete-row-modal', ['id' => $plan->id, 'action' => route('admin.pricing.destroy', $plan->id), 'title' => __('Delete Plan?')])
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4"><div class="fe-empty"><i class="fas fa-tags d-block"></i>{{ __('No plans yet') }}</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

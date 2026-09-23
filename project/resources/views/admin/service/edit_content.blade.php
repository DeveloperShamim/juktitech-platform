@extends('layouts.admin')

@section('styles')
@include('admin.partials.frontend-setup-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-align-left',
        'title' => __('Edit Section Text'),
        'subtitle' => __('সার্ভিস সেকশনের হেডিং টেক্সট আপডেট করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('Section Text'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.partials.frontend-setup-nav')

    <div class="fe-layout fe-layout--single">
        <div class="fe-card">
            <div class="fe-card__head">
                <h3><i class="fas fa-edit"></i> সার্ভিস সেকশন টেক্সট</h3>
            </div>
            <div class="fe-card__body">
                <p class="fe-hint">
                    <i class="fas fa-info-circle"></i>
                    বিস্তারিত সেকশন এডিটের জন্য <a href="{{ route('admin.homepage.sections') }}">Homepage Sections</a> ব্যবহার করুন।
                </p>
                <form action="{{ route('admin.service.update_text') }}" method="POST" class="adm-form">
                    @csrf
                    <div class="adm-form__grid">
                        <div class="adm-form__field adm-form__field--full">
                            <label>সাব-টাইটেল (উপরে ছোট লেখা)</label>
                            <input type="text" class="input-field" name="subtitle" value="{{ old('subtitle', $content->subtitle ?? '') }}" placeholder="আমাদের সেবা">
                        </div>
                        <div class="adm-form__field adm-form__field--full">
                            <label>মূল টাইটেল (বড় লেখা)</label>
                            <input type="text" class="input-field" name="title" value="{{ old('title', $content->title ?? '') }}" placeholder="আমরা কিভাবে সাহায্য করি?">
                        </div>
                    </div>
                    <div class="fe-actions">
                        <button class="fe-save" type="submit"><i class="fas fa-save"></i> আপডেট করুন</button>
                        <a href="{{ route('admin.homepage.sections') }}" class="fe-link-btn"><i class="fas fa-th-large"></i> সব সেকশন</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

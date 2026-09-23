@extends('layouts.admin')

@section('styles')
@include('admin.staff.partials.user-mgmt-styles')
@endsection

@section('content')
<div class="content-area adm-pro">

    @include('admin.partials.pro-hero', [
        'icon' => 'fas fa-user-plus',
        'title' => __('Add New User'),
        'subtitle' => __('নতুন ইউজার/ক্লায়েন্ট অ্যাকাউন্ট তৈরি করুন'),
        'breadcrumbs' => [
            ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
            ['label' => __('User Management'), 'url' => route('admin.staff.index')],
            ['label' => __('Add New User'), 'url' => null],
        ],
    ])

    @include('includes.admin.form-error')
    @include('includes.admin.form-success')

    @include('admin.staff.partials.user-nav')

    <div class="um-card">
        <div class="um-card__head">
            <h3><i class="fas fa-user-plus"></i> {{ __('New User Account') }}</h3>
            <a href="{{ route('admin.staff.index') }}" class="um-link">
                <i class="fas fa-arrow-left"></i> {{ __('All Users List') }}
            </a>
        </div>
        <div class="um-card__body">
            <form id="geniusform" action="{{ route('admin.staff.store') }}" method="POST" enctype="multipart/form-data" class="adm-form">
                @csrf
                @include('admin.staff.partials.user-form', ['mode' => 'create'])
            </form>
        </div>
    </div>
</div>
@endsection

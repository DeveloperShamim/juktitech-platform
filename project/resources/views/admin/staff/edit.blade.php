@extends('layouts.load')

@section('styles')
@include('admin.staff.partials.user-mgmt-styles')
@endsection

@section('content')
<div class="adm-form adm-form--modal">
    @include('includes.admin.form-both')

    <form id="geniusformdata" action="{{ route('admin.staff.update', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('admin.staff.partials.user-form', ['mode' => 'edit', 'data' => $data])
    </form>
</div>
@endsection

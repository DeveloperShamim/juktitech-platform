@extends('layouts.front')
@section('meta')
    <title>{{ __('Login') }} - {{ $gs->title }}</title>
    <meta property="og:title" content="{{ __('Login') }}" />
@endsection
@section('contents')
@include('partial.front.bn.log-reg-page')
@endsection

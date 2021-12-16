@extends('user.layouts.app')
@section('title', 'Truyện Hay - ' . \App\Models\Option::getvalue('sitename'))
@section('seo')
    <meta name="description" content="{{\App\Models\Option::getvalue('description')}}">
    <meta name="keywords" content="{{\App\Models\Option::getvalue('keyword')}}">
@endsection
@section('breadcrumb')
    {!! showBreadcrumb() !!}
@endsection
@section('content')
    @if (\Auth::guard('nd')->check())
        @include('user.templates.history', compact('historys'))
    @endif
@endsection


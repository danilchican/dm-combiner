@extends('layouts.backend.general', ['title' => ' | Dashboard'])

@section('sidebar_header')
    @include('partials.dashboard.common.sidebar')
@endsection

@section('top_dropdown_menu')
    <li><a href="{{ route('dashboard.index') }}">Админ-панель</a></li>
@endsection

@section('sidebar_menu')
    // dashboard menu
@endsection
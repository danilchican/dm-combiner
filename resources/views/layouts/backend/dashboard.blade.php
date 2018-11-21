@extends('layouts.backend.general', ['title' => ' | Dashboard'])

@section('sidebar_header')
    <div class="navbar nav_title" style="border: 0;">
        <a href="{{ route('dashboard.index') }}" class="site_title">
            <i class="fa fa-archive"></i> <span>@lang('sidebar.dashboard.title')</span>
        </a>
    </div>
@endsection

@section('top_dropdown_menu')
    <li><a href="{{ route('dashboard.index') }}">Админ-панель</a></li>
@endsection

@section('sidebar_menu')
    // dashboard menu
@endsection
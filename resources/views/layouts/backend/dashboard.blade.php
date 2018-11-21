@extends('layouts.backend.general', ['title' => ' | ' . trans('sidebar.dashboard.title')]) {{--TODO change title--}}

@section('top_dropdown_menu')
    <li><a href="{{ route('dashboard.index') }}">@lang('general.dashboard.section.top_dropdown_menu.home')</a></li>
@endsection

@section('sidebar_header')
    <div class="navbar nav_title" style="border: 0;">
        <a href="{{ route('dashboard.index') }}" class="site_title">
            <i class="fa fa-archive"></i> <span>@lang('sidebar.dashboard.title')</span>
        </a>
    </div>
@endsection

@section('sidebar_menu')
    <li>
        <a href="{{ route('dashboard.index') }}">
            <i class="fa fa-home"></i> @lang('sidebar.dashboard.menu.home')
        </a>
    </li>
@endsection
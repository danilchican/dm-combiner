@extends('layouts.backend.general', ['title' => ' | ' . trans('sidebar.account.title')]) {{--TODO change title--}}

@section('top_dropdown_menu')
    <li><a href="{{ route('account.index') }}">@lang('general.account.section.top_dropdown_menu.home')</a></li>
@endsection

@section('sidebar_header')
    <div class="navbar nav_title" style="border: 0;">
        <a href="{{ route('account.index') }}" class="site_title">
            <i class="fa fa-archive"></i> <span>@lang('sidebar.account.title')</span>
        </a>
    </div>
@endsection

@section('sidebar_menu')
    <li>
        <a href="{{ route('account.index') }}">
            <i class="fa fa-home"></i> @lang('sidebar.account.menu.home')
        </a>
    </li>
@endsection
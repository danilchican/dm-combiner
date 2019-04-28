@extends('layouts.backend.general')

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
    <li>
        <a href="{{ route('dashboard.users.index') }}">
            <i class="fa fa-user-circle-o"></i> @lang('sidebar.dashboard.menu.users')
        </a>
    </li>
    <li>
        <a>
            <i class="fa fa-sitemap"></i> @lang('sidebar.account.menu.projects.title')
            &nbsp<span class="label label-success">{{ App\Models\Project::count() }}</span>
            <span class="fa fa-chevron-down"></span>
        </a>

        <ul class="nav child_menu">
            <li>
                <a href="{{ route('dashboard.projects.index') }}">
                    @lang('sidebar.account.menu.projects.view_all')
                </a>
            </li>
            <li>
                <a href="{{ route('account.projects.create') }}">@lang('sidebar.account.menu.projects.create')</a>
            </li>
        </ul>
    </li>
@endsection
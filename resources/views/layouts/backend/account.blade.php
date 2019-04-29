@extends('layouts.backend.general')

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
    @if(Auth::user()->isAdministrator())
        <li>
            <a href="{{ route('account.users.index') }}">
                <i class="fa fa-user-circle-o"></i> @lang('sidebar.dashboard.menu.users')
            </a>
        </li>
    @endif
    <li>
        <a>
            <i class="fa fa-sitemap"></i> @lang('sidebar.account.menu.projects.title')
            <span class="label label-success">
                {{ Auth::user()->isAdministrator() ? App\Models\Project::count() : Auth::user()->projects()->count() }}
            </span>
            <span class="fa fa-chevron-down"></span>
        </a>

        <ul class="nav child_menu">
            <li>
                <a href="{{ route('account.projects.index') }}">
                    @lang('sidebar.account.menu.projects.view_all')
                </a>
            </li>
            <li>
                <a href="{{ route('account.projects.create') }}">@lang('sidebar.account.menu.projects.create')</a>
            </li>
        </ul>
    </li>
@endsection
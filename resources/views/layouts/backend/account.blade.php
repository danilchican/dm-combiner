@extends('layouts.general', ['title' => ' | Account'])

@section('sidebar_header')
    <div class="navbar nav_title" style="border: 0;">
        <a href="{{ route('account.index') }}" class="site_title">
            <i class="fa fa-archive"></i> <span>@lang('sidebar.account.title')</span>
        </a>
    </div>
@endsection

@section('top_dropdown_menu')
    <li><a href="{{ route('account.index') }}">Мой аккаунт</a></li>
@endsection

@section('sidebar_menu')
    // account menu
@endsection
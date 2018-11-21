@extends('layouts.general', ['title' => ' | Account'])

@section('sidebar_header')
    @include('partials.account.common.sidebar')
@endsection

@section('top_dropdown_menu')
    <li><a href="{{ route('account.index') }}">Мой аккаунт</a></li>
@endsection

@section('sidebar_menu')
    // account menu
@endsection
@extends('layouts.general', ['title' => ' | Account'])

@section('sidebar')
    @include('partials.account.common.sidebar')
@endsection

@push('top_dropdown_menu')
    <li><a href="{{ route('account.index') }}">Мой аккаунт</a></li>
@endpush
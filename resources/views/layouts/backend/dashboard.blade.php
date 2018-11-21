@extends('layouts.backend.general', ['title' => ' | Dashboard'])

@section('sidebar')
    @include('partials.dashboard.common.sidebar')
@endsection

@push('top_dropdown_menu')
    <li><a href="{{ route('dashboard.index') }}">Админ-панель</a></li>
@endpush
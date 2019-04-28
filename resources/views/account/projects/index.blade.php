@extends('layouts.backend.account', ['title' => ' | ' . (Auth::user()->isAdministrator() ? 'Projects' : 'My Projects') ])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <a href="{{ route('account.projects.create') }}" class="btn btn-primary">Create new</a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ Auth::user()->isAdministrator() ? 'Projects' : 'My Projects' }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @include('partials.common.projects.list')
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ $projects->links() }}
        </div>
    </div>
@endsection
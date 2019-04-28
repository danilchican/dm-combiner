@extends('layouts.backend.account', ['title' => ' | ' . trans('sidebar.dashboard.title')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>@lang('dashboard.home.section.users.title')</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        @if(count($users))
                            @include('partials.dashboard.home.users')
                        @else
                            @lang('dashboard.home.section.users.messages.empty_collection')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{ $users->links() }}
        </div>
    </div>
@endsection
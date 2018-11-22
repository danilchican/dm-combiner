@extends('layouts.backend.dashboard', ['title' => ' | ' . trans('sidebar.dashboard.users.view')])

@section('content')
    <div class="">
        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2 style="padding-bottom: 5px; width: 100%;">
                            @lang('general.dashboard.section.user_view.title')
                        </h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                            <div class="profile_img">
                                <div id="crop-avatar">
                                    <img class="img-responsive avatar-view"
                                         src="{{ asset('/images/default-user-image.jpg') }}"
                                         alt="User Photo" title="User Photo">
                                </div>
                            </div>
                            <h3>{{ $user->getName() }}</h3>

                            <ul class="list-unstyled user_data">
                                <li>
                                    <i class="fa fa-envelope-o"></i> {{ $user->getEmail() }}
                                </li>
                                <li class="m-top-xs">
                                    <i class="fa fa-user user-profile-icon"></i>
                                    @lang('general.roles.'.$user->role->getSlug())
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-9 col-sm-9 col-xs-12">

                            <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                    <li role="presentation" class="active">
                                        <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab"
                                           aria-expanded="true">
                                            @lang('general.dashboard.section.user_view.tabs.settings')
                                        </a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#tab_content2" role="tab" id="profile-tab2" data-toggle="tab"
                                           aria-expanded="false">
                                            @lang('general.dashboard.section.user_view.tabs.projects')
                                        </a>
                                    </li>
                                </ul>
                                <div id="myTabContent" class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                         aria-labelledby="home-tab">
                                        {{--TODO settings here--}}
                                        Settings here...
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="tab_content2"
                                         aria-labelledby="profile-tab">
                                        {{--TODO list of user's projects here--}}
                                        List of user's projects here
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
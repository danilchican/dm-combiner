@extends('layouts.backend.account', ['title' => ' | ' . trans('sidebar.account.title')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>My Profile</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
                        <div class="profile_img">
                            <div id="crop-avatar">
                                <img class="img-responsive avatar-view"
                                     src="/images/default-user-image.jpg" alt="Avatar">
                            </div>
                        </div>
                        <h3>{{ Auth::user()->getName() }}</h3>

                        <ul class="list-unstyled user_data">
                            <li><b>Group:</b> {{ Auth::user()->role->getTitle() }}</li>
                            <li>
                                <i class="fa fa-envelope-o user-profile-icon"></i> {{ Auth::user()->getEmail() }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <div role="tabpanel" data-example-id="togglable-tabs">
                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab_content1" id="home-tab" role="tab" data-toggle="tab"
                                       aria-expanded="true">About</a>
                                </li>
                            </ul>
                            <div id="myTabContent" class="tab-content">
                                <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                     aria-labelledby="home-tab">

                                    @include('partials.messages')

                                    <form method="post" action="{{ route('profile.update') }}"
                                          id="update-user-form" class="form-horizontal form-label-left">
                                        {{ csrf_field() }}

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                                                Name <span class="required">*</span>
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="text" required="required" name="name"
                                                       placeholder="Введите ваше имя..."
                                                       class="form-control col-md-7 col-xs-12"
                                                       value="{{ Auth::user()->getName() }}">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
                                                Password
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="password" id="password" name="password"
                                                       autocomplete="new-password"
                                                       class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12"
                                                   for="password_confirmation">
                                                Confirm Password
                                            </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input type="password" id="password_confirmation"
                                                       name="password_confirmation"
                                                       class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>

                                        <div class="ln_solid"></div>

                                        <div class="form-group">
                                            <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
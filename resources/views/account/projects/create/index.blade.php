@extends('layouts.backend.account', ['title' => ' | ' . trans('sidebar.account.menu.projects.create')])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Create project</h2>
                    <div class="clearfix"></div>
                    <form class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Title
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="first-name" required="required"
                                       class="form-control col-md-7 col-xs-12">
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                    <br/>
                </div>
                <div class="x_content">
                    <div id="wizard" class="form_wizard wizard_horizontal">
                        <ul class="wizard_steps">
                            <li>
                                <a href="#step-1">
                                    <span class="step_no">1</span>
                                    <span class="step_descr">Step 1<br/><small>Load data</small></span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-2">
                                    <span class="step_no">2</span>
                                    <span class="step_descr">
                                        Step 2<br/><small>Select & configure algorithms</small>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a href="#step-3">
                                    <span class="step_no">3</span>
                                    <span class="step_descr">Step 3<br/><small>Execution results</small></span>
                                </a>
                            </li>
                        </ul>
                        <div id="step-1">
                            @include('account.projects.create.tabs.1_step')
                        </div>
                        <div id="step-2">
                            <project-configuration></project-configuration>
                        </div>
                        <div id="step-3">
                            @include('account.projects.create.tabs.3_step')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
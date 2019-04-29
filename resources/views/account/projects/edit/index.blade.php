@extends('layouts.backend.account', ['title' => ' | Projects | Edit Project #' .  $project->id])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Edit project #{{ $project->getId() }}
                        <small>created at {{ $project->getCreatedDate()->format('m.d.Y H:i') }}</small>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                    <form class="form-horizontal form-label-left">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="project-title">Title
                                <span class="required">*</span>
                            </label>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" id="project-title" required="required"
                                       value="{{ $project->getTitle() }}"
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
                            @include('partials.common.projects.tabs_header')
                        </ul>
                        <div id="step-1">
                            @include('account.projects.edit.tabs.1_step')
                        </div>
                        <div id="step-2">
                            <project-configuration></project-configuration>
                        </div>
                        <div id="step-3">
                            {{--@include('account.projects.create.tabs.3_step')--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
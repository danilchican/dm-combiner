@extends('layouts.backend.account', ['title' => ' | View #' . $project->getId() ])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>
                        View Project #{{ $project->getId() }}
                        <small>created at {{ $project->getCreatedDate()->format('m.d.Y H:i') }}</small>
                    </h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p><b>Title:</b> {{ $project->getTitle() }}</p>
                    <p><b>Created by:</b>
                        @if(Auth::user()->isAdministrator())
                            <a href="{{ route('account.users.view', ['id' => $project->user->id]) }}"
                               target="_blank">{{ $project->user->getName() }}</a>
                        @else
                            {{ $project->user->getName() }}
                        @endif
                    </p>
                    <p><b>Status:</b> {{ $project->getStatus() }}</p>
                    @if($project->getTaskId() !== null)
                        <p><b>Task ID:</b> {{ $project->getTaskId() }}</p>
                    @endif
                    <p><b>Last updates at:</b> {{ $project->getLastUpdatedTime()->diffForHumans() }}</p>

                    <div class="ln_solid"></div>

                    <p><b>Checked options:</b></p>
                    <ul>
                        @if($project->getNormalize())
                            <li>Normalize</li>
                        @endif
                        @if($project->getScale())
                            <li>Scale</li>
                        @endif
                    </ul>

                    <p><b>Data url:</b> {{ $project->getDataUrl() }}</p>
                    <p><b>Columns:</b> {{ $project->getCheckedColumns() }}</p>

                    <div class="ln_solid"></div>

                    <p><b>Configuration:</b></p>
                    {{--TODO add configuration showing--}}

                    @if($project->getResult() !== null)
                        <div class="ln_solid"></div>
                        <p><b>Result:</b></p>
                        <p>
                            {{--// TODO limit length and put download results button--}}
                            <i>{{ $project->getResult() }}</i>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
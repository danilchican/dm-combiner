@extends('layouts.backend.account', ['title' => ' | ' . trans('sidebar.account.menu.projects.title')])

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
                    <h2>My Projects</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped no-margin">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Created at</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->getId() }}</td>
                                    <td>{{ $project->getTitle() }}</td>
                                    <td>{{ $project->getStatus() }}</td>
                                    <td>{{ $project->getCreatedDate() }}</td>
                                    <td>
                                        <a href="" class="btn btn-info btn-xs">
                                            Редактировать
                                        </a>
                                        <a href="" class="btn btn-danger btn-xs">Удалить</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
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
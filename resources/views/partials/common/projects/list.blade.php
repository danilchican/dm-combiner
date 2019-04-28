@if(count($projects))
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
                        <a href="{{ route('account.projects.view', ['id' => $project->getId()]) }}"
                           class="btn btn-primary btn-xs">View</a>
                        {{--TODO add links--}}
                        <a href="" class="btn btn-warning btn-xs">Edit</a>
                        <a href="" class="btn btn-danger btn-xs">Delete</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No projects found.</p>
@endif
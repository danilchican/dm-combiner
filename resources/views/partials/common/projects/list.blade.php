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
                    <td style="display: flex;">
                        <a href="{{ route('account.projects.view', ['id' => $project->id]) }}"
                           class="btn btn-primary btn-xs">View</a>

                        <a href="{{ route('account.projects.edit', ['id' => $project->id]) }}"
                           class="btn btn-warning btn-xs">Edit</a>

                        <form action="{{ route('account.projects.remove') }}" method="POST">
                            {{ csrf_field() }}

                            <input type="hidden" name="id" value="{{ $project->id }}">
                            <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <p>No projects found.</p>
@endif
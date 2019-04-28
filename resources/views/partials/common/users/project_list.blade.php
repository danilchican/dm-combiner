@if(count($projects))
    <table class="table table-striped no-margin">
        <thead>
        <tr>
            <th class="col-md-1">#</th>
            <th class="col-md-7">Title</th>
            <th class="col-md-3">Created at</th>
            <th class="col-md-1">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ str_limit($project->getTitle(), 60) }}</td>
                <td>{{ $project->getCreatedDate()->format('d.m.Y H:i') }}</td>
                <td>
                    <a href="{{ route('account.projects.view', ['id' => $project->id]) }}" class="btn-xs btn-primary">
                        View
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>User haven't any projects.</p>
@endif
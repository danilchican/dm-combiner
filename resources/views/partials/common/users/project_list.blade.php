@if(count($projects))
    <table class="table table-striped no-margin">
        <thead>
        <tr>
            <th class="col-md-1">#</th>
            <th class="col-md-7">Title</th>
            <th class="col-md-2">Created at</th>
            <th class="col-md-2">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ str_limit($project->getTitle(), 60) }}</td>
                <td>{{ $project->getCreatedDate()->format('d.m.Y H:i') }}</td>
                <td style="display: flex;">
                    <a href="{{ route('account.projects.view', ['id' => $project->id]) }}" class="btn btn-xs btn-primary">
                        View
                    </a>
                    <a href="{{ route('account.projects.edit', ['id' => $project->id]) }}" class="btn btn-xs btn-warning">
                        Edit
                    </a>
                    <form action="{{ route('account.projects.remove') }}" method="POST">
                        {{ csrf_field() }}

                        <input type="hidden" name="id" value="{{ $project->id }}">
                        <button type="submit" class="btn btn-xs btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>User haven't any projects.</p>
@endif
@if(count($projects))
    <table class="table table-striped no-margin">
        <thead>
        <tr>
            <th class="col-md-1">#</th>
            <th class="col-md-8">Title</th>
            <th class="col-md-3">Created at</th>
        </tr>
        </thead>
        <tbody>

        @foreach($projects as $project)
            <tr>
                <td>{{ $project->id }}</td>
                <td>{{ $project->getTitle() }}</td>
                <td>{{ $project->getCreatedDate()->format('d.m.Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <p>User haven't any projects.</p>
@endif
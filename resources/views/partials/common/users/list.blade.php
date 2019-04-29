<table class="table table-striped no-margin">
    <thead>
    <tr>
        <th>#</th>
        <th>@lang('models.user.name')</th>
        <th>@lang('models.user.email')</th>
        <th>@lang('models.user.projects_count')</th>
        <th>@lang('models.user.created_at')</th>
        <th>@lang('general.common.headers.action')</th>
    </tr>
    </thead>
    <tbody>

    @foreach($users as $user)
        <tr>
            <td>{{ $user->getId() }}</td>
            <td>{{ $user->getName() }}</td>
            <td>{{ $user->getEmail() }}</td>
            <td>{{ $user->projects_count }}</td>
            <td>{{ $user->getRegistrationDate() }}</td>
            <td>
                <a href="{{ route('account.users.view', ['id' => $user->getId()]) }}"
                   class="btn btn-primary btn-xs">
                    <i class="fa fa-edit"></i> @lang('buttons.dashboard.view')
                </a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

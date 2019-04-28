<form method="post" action="{{ route('dashboard.users.update') }}"
      id="update-user-form" class="form-horizontal form-label-left">
    {{ csrf_field() }}

    <input type="hidden" name="user-id" value="{{ $user->id }}">

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
            Name <span class="required">*</span>
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="text" required="required" name="name"
                   placeholder="Введите ваше имя..."
                   class="form-control col-md-7 col-xs-12"
                   value="{{ $user->getName() }}">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">
            Password
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="password" id="password" name="password"
                   autocomplete="new-password"
                   class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"
               for="password_confirmation">
            Confirm Password
        </label>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <input type="password" id="password_confirmation"
                   name="password_confirmation"
                   class="form-control col-md-7 col-xs-12">
        </div>
    </div>

    <div class="ln_solid"></div>

    <div class="form-group">
        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
            <button type="submit" class="btn btn-success">Save</button>
        </div>
    </div>
</form>
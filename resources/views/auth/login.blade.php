<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'ФКСиС') }} @isset($subtitle) - {{$subtitle}} @endisset</title>

    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.min.css') }}" rel="stylesheet">
</head>

<body class="login">
<div>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
        <div class="animate form login_form">
            @if(session('successRequestMessage'))
                <div class="alert alert-success">
                    {{ session('successRequestMessage') }}
                </div>
            @endif

            <section class="login_content">
                <form method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <h1>@lang('pages.login.title')</h1>
                    <div>
                        <input type="text" value="{{ old('email') }}" class="form-control"
                               placeholder="Username" name="email" required/>
                    </div>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <div>
                        <input type="password" class="form-control" placeholder="Password"
                               name="password" required/>
                    </div>
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                    <div>
                        <button type="submit" class="btn btn-default submit">
                            @lang('buttons.common.login')
                        </button>
                        <a class="reset_pass" href="{{ route('password.request') }}">
                            @lang('buttons.common.forgot_password')
                        </a>
                    </div>
                    <div class="clearfix"></div>
                    <div class="separator">
                        <div><p>&copy;2018 {{ config('app.name', 'Datamining Combiner') }}</p></div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</div>
</body>
</html>
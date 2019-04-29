@if (session('success'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        @lang(session('success'))
    </div>
@endif

@if(session('error'))
    <ul class="alert alert-danger">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>
        @lang(session('error'))
    </ul>
@endif

@if ($errors->any())
    <ul class="alert alert-danger">
        @foreach($errors->all() as $error)
            <p>
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Error:</span>
                {{ $error }}
            </p>
        @endforeach
    </ul>
@endif

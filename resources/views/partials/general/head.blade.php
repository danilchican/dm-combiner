<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }}{{ $title or '' }}</title>

<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.min.css') }}" rel="stylesheet">
@yield('styles')
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ Admin::title() }}</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{ $favicon }}">
    @endif

    {!! Admin::css() !!}
    {!! Admin::headerJs() !!}
    {!! Admin::js() !!}
    {!! Admin::js_trans() !!}

</head>

<body class="bg-gray-50 {{ $body_classes }}">

    @if($alert = config('admin.top_alert'))
        <div class="bg-yellow-50 border-b border-yellow-200 text-yellow-800 text-sm px-4 py-2">
            {!! $alert !!}
        </div>
    @endif

    @include('admin::partials.header')

    <div class="flex">
        @include('admin::partials.sidebar')
        <main id="main" class="flex-1 min-w-0 p-4 min-h-screen">

            <div id="pjax-container">
            <!--start-pjax-container-->
                {!! Admin::style() !!}
                <div id="app">
                    @yield('content')
                </div>
                {!! Admin::html() !!}
                {!! Admin::script() !!}
            <!--end-pjax-container-->
            </div>

        </main>
    </div>

    <script>
        function LA() {}
        LA.token = "{{ csrf_token() }}";
        LA.user = @json($_user_);
    </script>

</body>
</html>

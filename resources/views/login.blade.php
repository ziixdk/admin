<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{config('admin.title')}} | {{ __('admin.login') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if(!is_null($favicon = Admin::favicon()))
    <link rel="shortcut icon" href="{{$favicon}}">
    @endif

    <link rel="stylesheet" href="{{ Admin::asset('ziix-admin/dist/css/app.css') }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center"
    @if(config('admin.login_background_image'))
    style="background: url({{config('admin.login_background_image')}}) no-repeat center center; background-size: cover;"
    @endif>
    <div class="w-full max-w-sm px-4">
        <h1 class="text-center mb-6 text-2xl font-bold text-gray-800">
            <a class="text-gray-800 no-underline hover:text-gray-900" href="{{ admin_url('/') }}">{{config('admin.name')}}</a>
        </h1>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            @if($errors->has('attempts'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm text-center">
                    {{$errors->first('attempts')}}
                </div>
            @else

            <form action="{{ admin_url('auth/login') }}" method="post" class="space-y-4">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div>
                    @if($errors->has('username'))
                        <div class="mb-2 px-3 py-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">{{$errors->first('username')}}</div>
                    @endif
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.username') }}</label>
                    <div class="admin-input-group">
                        <span class="inline-flex items-center px-3 bg-gray-50 border border-gray-300 border-e-0 rounded-s-lg text-gray-500">
                            <i class="icon-user text-sm"></i>
                        </span>
                        <input type="text"
                               class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-e-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="{{ __('admin.username') }}"
                               name="username" id="username"
                               value="{{ old('username') }}" required>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">{{ __('admin.password') }}</label>
                    <div class="admin-input-group">
                        <span class="inline-flex items-center px-3 bg-gray-50 border border-gray-300 border-e-0 rounded-s-lg text-gray-500">
                            <i class="icon-eye text-sm"></i>
                        </span>
                        <input type="password"
                               class="flex-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-e-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                               placeholder="{{ __('admin.password') }}"
                               name="password" id="password" required>
                    </div>
                    @if($errors->has('password'))
                        <div class="mt-1 px-3 py-2 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">{{$errors->first('password')}}</div>
                    @endif
                </div>

                @if(config('admin.auth.remember'))
                <div class="flex items-center gap-2">
                    <input type="checkbox"
                           class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                           name="remember" id="remember" value="1"
                           {{ (old('remember')) ? 'checked' : '' }}>
                    <label class="text-sm text-gray-600" for="remember">{{ __('admin.remember_me') }}</label>
                </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                        {{ __('admin.login') }}
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</body>
</html>

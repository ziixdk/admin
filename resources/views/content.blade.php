@extends('admin::index', ['header' => strip_tags($header)])

@section('content')

    @foreach ($css_files as $css_file)
        <link rel="stylesheet" href="{{ $css_file }}">
    @endforeach
    @isset($css)
        <style type="text/css">{{ $css }}</style>
    @endisset

    <section class="content-header mb-4">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">
                    {!! $header ?: trans('admin.title') !!}
                </h1>
                @if($description ?? null)
                <p class="text-sm text-gray-500 mt-0.5">{!! $description !!}</p>
                @endif
            </div>
        </div>

        @include('admin::partials.breadcrumb')

    </section>

    <section class="content">

        @include('admin::partials.alerts')
        @include('admin::partials.exception')
        @include('admin::partials.toastr')

        @if($_view_)
            @include($_view_['view'], $_view_['data'])
        @else
            {!! $_content_ !!}
        @endif

    </section>
@endsection

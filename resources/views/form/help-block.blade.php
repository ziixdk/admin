@if($help)
<p class="mt-1.5 text-xs text-gray-500">
    <i class="{{ \Illuminate\Support\Arr::get($help, 'icon') }}"></i>&nbsp;{!! \Illuminate\Support\Arr::get($help, 'text') !!}
</p>
@endif

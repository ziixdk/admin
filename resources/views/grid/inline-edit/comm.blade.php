{{--inline edit popover--}}

<span class="ie-wrap">
    <a
        id="{{ $trigger }}"
        class="ie cursor-pointer"
        data-target="{{ $target }}"
        data-value="{{ $value }}"
        data-original="{{ $value }}"
        data-key="{{ $key }}"
        data-name="{{ $name }}"
        data-resource="{{ $resource }}"
        @isset($type)
        data-type="{{ $type }}"
        @endisset
        data-init="0"
    >
        <span class="ie-display">{{ $display }}</span>
        <i class="icon-edit text-gray-300 hover:text-gray-500 ms-1 text-xs"></i>
    </a>
</span>

<template id="{{ $target }}">
    <div class="ie-content ie-content-{{ $name }}">
        <div class="ie-container">
            @yield('field')
            <div class="error text-red-600 text-xs mt-1"></div>
        </div>
        <div class="ie-action flex gap-1 mt-2">
            <button class="px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 ie-submit">{{ __('admin.submit') }}</button>
            <button class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded hover:bg-gray-200 ie-cancel">{{ __('admin.cancel') }}</button>
        </div>
    </div>
</template>

<script>
    admin.grid.inline_edit.init_popover("{{$trigger}}","{{$target}}");
</script>

@yield('assert')

@include("admin::form._header")

    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 {{ $class }}"
        name="{{ $name }}[]" data-placeholder="{{ $placeholder }}" {!! $attributes !!} value="{{ implode(',', $value) }}" />

@include("admin::form._footer")

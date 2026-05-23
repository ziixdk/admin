@include("admin::form._header")

    <textarea name="{{ $name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 {{ $class }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}" {!! $attributes !!}>{{ old($column, $value) }}</textarea>

    {!! $append !!}

@include("admin::form._footer")

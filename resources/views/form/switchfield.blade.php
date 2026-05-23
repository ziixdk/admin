@include("admin::form._header")

    <label class="inline-flex items-center cursor-pointer pt-1">
        <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ old($column, $value) }}" />
        <input class="sr-only peer {{ $class }}" name="{{ $name }}_cb" type="checkbox" id="{{ $name }}_cb"
            {{ !empty(old($column, $value)) ? 'checked' : '' }} {!! $attributes !!}
            onchange="document.querySelector('#{{ $id }}').value = (this.checked ? 'on' : 'off')" />
        <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
    </label>

@include("admin::form._footer")

@include("admin::form._header")

    <div class="flex flex-wrap gap-x-4 gap-y-2 pt-1">
        @foreach($options as $option => $label)
        <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer @if($stacked)w-full @endif">
            <input class="w-4 h-4 text-blue-600 border-gray-300 {{ $class }}" type="radio"
                id="{{ $name }}-{{ $option }}" name="{{ $name }}" value="{{ $option }}"
                {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ? 'checked' : '' }}
                {!! $attributes !!} />
            {{ $label }}
        </label>
        @endforeach
    </div>

@include("admin::form._footer")

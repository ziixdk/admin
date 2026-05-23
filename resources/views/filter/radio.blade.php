@foreach($options as $option => $label)
    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mb-1">
        <input type="radio" class="w-4 h-4 text-blue-600 border-gray-300"
            id="{{ $id }}-{{ $option }}" name="{{ $name }}" value="{{ $option }}"
            {{ ((string)$option === request($name, is_null($value) ? '' : $value)) ? 'checked' : '' }} />
        {{ $label }}
    </label>
@endforeach

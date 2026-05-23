@foreach($options as $option => $label)
    <label class="flex items-center gap-2 text-sm text-gray-700 cursor-pointer mb-1 @if($inline) inline-flex me-3 @endif">
        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded"
            id="{{ $id }}-{{ $option }}" name="{{ $name }}[]" value="{{ $option }}"
            {{ in_array((string)$option, request($name, is_null($value) ? [] : $value)) ? 'checked' : '' }} />
        {{ $label }}
    </label>
@endforeach

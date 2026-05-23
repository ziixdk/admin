<select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 {{ $class }}"
    name="{{ $name }}[]" multiple='multiple' style="width: 100%;">
    <option></option>
    @foreach($options as $select => $option)
        <option value="{{ $select }}" {{ in_array((string)$select, (array)$value) ? 'selected' : '' }}>{{ $option }}</option>
    @endforeach
</select>

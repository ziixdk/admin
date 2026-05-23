@include("admin::form._header")

        <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 {{ $class }}"
            style="width: 100%;" name="{{ $name }}[]" multiple="multiple" data-placeholder="{{ $placeholder }}" {!! $attributes !!}>
            @foreach($options as $select => $option)
                <option value="{{ $select }}" {{ in_array($select, (array)old($column, $value)) ? 'selected' : '' }}>{{ $option }}</option>
            @endforeach
        </select>
        <input type="hidden" name="{{ $name }}[]" />

@include("admin::form._footer")

@include("admin::form._header")

        @if (!empty($attributes_obj['readonly']))
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
        @endif

        <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 {{ $class }}" style="width: 100%;" name="{{ $name }}@if (!empty($attributes_obj['readonly']))-disabled @endif" {!! $attributes !!}>
            @if($groups)
                @foreach($groups as $group)
                    <optgroup label="{{ $group['label'] }}">
                        @foreach($group['options'] as $select => $option)
                            <option value="{{ $select }}" {{ $select == old($column, $value) ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            @else
                <option value=""></option>
                @foreach($options as $select => $option)
                    <option value="{{ $select }}" {{ $select == old($column, $value) ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            @endif
        </select>

@include("admin::form._footer")

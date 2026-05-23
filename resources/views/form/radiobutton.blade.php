@include("admin::form._header")

        <div class="flex flex-wrap gap-1 radio-group-toggle">
            @foreach($options as $option => $label)
            @php $isChecked = ($option == old($column, $value)) || ($value === null && in_array($label, $checked)); @endphp
                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border cursor-pointer transition-colors {{ $isChecked ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50' }}">
                    <input type="radio" name="{{$name}}" value="{{$option}}" class="sr-only {{$class}}" {{ $isChecked ? 'checked' : '' }} {!! $attributes !!} />
                    {{$label}}
                </label>
            @endforeach
        </div>

@include("admin::form._footer")

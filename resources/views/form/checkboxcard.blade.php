@include("admin::form._header")

        <div class="flex flex-wrap gap-1">
        @foreach($options as $option => $label)
            <input type="checkbox" name="{{$name}}" value="{{$option}}" id="{{$name}}-{{$option}}" class="sr-only peer/opt {{$class}}" {{ ($option == old($column, $value)) || ($value === null && in_array($label, $checked)) ?'checked':'' }} {!! $attributes !!} />
            <label class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-lg border cursor-pointer transition-colors text-gray-700 border-gray-300 hover:bg-gray-50 peer-checked/opt:bg-blue-600 peer-checked/opt:text-white peer-checked/opt:border-blue-600" for="{{$name}}-{{$option}}">{{$label}}</label>
        @endforeach
        </div>

        <input type="hidden" name="{{$name}}[]">

@include("admin::form._footer")

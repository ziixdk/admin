@include("admin::form._header")

        <div class="admin-input-group" style="max-width: 150px;">
            <input type="text" id="{{$id}}" name="{{$name}}" value="{{ old($column, $value) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-s-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-right {{$class}}" placeholder="0" {!! $attributes !!} />
            <span class="inline-flex items-center px-3 py-2 text-sm text-gray-600 bg-gray-50 border border-gray-300 border-s-0 rounded-e-lg">%</span>
        </div>

@include("admin::form._footer")

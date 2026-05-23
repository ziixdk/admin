@include("admin::form._header")

        <div class="admin-input-group">
                <input type="file" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2 {{ $class }}"
                    name="{{ $name }}[]" {!! $attributes !!} />
                @isset($btn)
                <div class="shrink-0">{!! $btn !!}</div>
                @endisset
        </div>
        @isset($sortable)
        <input type="hidden" class="{{ $class }}_sort" name="{{ $sort_flag.'['.$name.']' }}"/>
        @endisset

@include("admin::form._footer")


@php($listErrorKey = "$column")
@include("admin::form._header")

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm text-gray-700 table-with-fields">
                <tbody class="list-{{ $column }}-table divide-y divide-gray-100">

                @foreach(old("{$column}", ($value ?: [])) as $k => $v)

                    @php($itemErrorKey = "{$column}.{$loop->index}")

                    <tr>
                        @if(!empty($options['sortable']))
                            <td class="px-2 py-2 w-8">
                                <span class="icon-arrows-alt-v text-gray-400 cursor-move handle"></span>
                            </td>
                        @endif
                        <td class="px-3 py-2">
                            <div class="{{ $errors->has($itemErrorKey) ? 'has-error' : '' }}">
                                <input name="{{ $column }}[]" value="{{ old("{$column}.{$k}", $v) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" />
                                @if($errors->has($itemErrorKey))
                                    <p class="text-xs text-red-600 mt-1">
                                        @foreach($errors->get($itemErrorKey) as $message){{ $message }}@endforeach
                                    </p>
                                @endif
                            </div>
                        </td>

                        <td class="px-3 py-2 w-20">
                            <button type="button" class="{{ $column }}-remove inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                <i class="icon-trash"></i> {{ __('admin.remove') }}
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-2">
            <button type="button" class="{{ $column }}-add inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                <i class="icon-plus"></i> {{ __('admin.new') }}
            </button>
        </div>

        <template class="{{ $column }}-tpl">
            <tr>
                @if(!empty($options['sortable']))
                    <td class="px-2 py-2 w-8">
                        <span class="icon-arrows-alt-v text-gray-400 cursor-move handle"></span>
                    </td>
                @endif
                <td class="px-3 py-2">
                    <input name="{{ $column }}[]"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" />
                </td>

                <td class="px-3 py-2 w-20">
                    <button type="button" class="{{ $column }}-remove inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                        <i class="icon-trash"></i> {{ __('admin.remove') }}
                    </button>
                </td>
            </tr>
        </template>

@include("admin::form._footer")

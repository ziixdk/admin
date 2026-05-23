<div class="{{ $viewClass['form-group'] }}">

    <label class="{{ $viewClass['label'] }} text-sm font-medium text-gray-700 pt-2.5">{{ $label }}</label>

    <div class="{{ $viewClass['field'] }}">
        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="w-full text-sm text-gray-700 table-with-fields">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    @if(!empty($options['sortable']))
                        <th class="px-2 py-2 w-8"></th>
                    @endif
                    <th class="px-3 py-2">{{ __('Key') }}</th>
                    <th class="px-3 py-2">{{ __('Value') }}</th>
                    <th class="px-3 py-2 w-20"></th>
                </tr>
                </thead>
                <tbody class="kv-{{ $column }}-table divide-y divide-gray-100">

                @foreach(old("{$column}.keys", ($value ?: [])) as $k => $v)

                    @php($keysErrorKey = "{$column}.keys.{$loop->index}")
                    @php($valsErrorKey = "{$column}.values.{$loop->index}")

                    <tr>
                        @if(!empty($options['sortable']))
                            <td class="px-2 py-2 w-8">
                                <span class="icon-arrows-alt-v text-gray-400 cursor-move handle"></span>
                            </td>
                        @endif
                        <td class="px-3 py-2">
                            <div class="{{ $errors->has($keysErrorKey) ? 'has-error' : '' }}">
                                <input name="{{ $name }}[keys][]" value="{{ old("{$column}.keys.{$k}", $k) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" required/>
                                @if($errors->has($keysErrorKey))
                                    <p class="text-xs text-red-600 mt-1">
                                        @foreach($errors->get($keysErrorKey) as $message){{ $message }}@endforeach
                                    </p>
                                @endif
                            </div>
                        </td>
                        <td class="px-3 py-2">
                            <div class="{{ $errors->has($valsErrorKey) ? 'has-error' : '' }}">
                                <input name="{{ $name }}[values][]" value="{{ old("{$column}.values.{$k}", $v) }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" />
                                @if($errors->has($valsErrorKey))
                                    <p class="text-xs text-red-600 mt-1">
                                        @foreach($errors->get($valsErrorKey) as $message){{ $message }}@endforeach
                                    </p>
                                @endif
                            </div>
                        </td>

                        <td class="px-3 py-2">
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

    </div>
    <template class="{{ $column }}-tpl">
        <tr>
            @if(!empty($options['sortable']))
                <td class="px-2 py-2 w-8">
                    <span class="icon-arrows-alt-v text-gray-400 cursor-move handle"></span>
                </td>
            @endif
            <td class="px-3 py-2">
                <input name="{{ $name }}[keys][]"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" required/>
            </td>
            <td class="px-3 py-2">
                <input name="{{ $name }}[values][]"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2" />
            </td>

            <td class="px-3 py-2">
                <button type="button" class="{{ $column }}-remove inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                    <i class="icon-trash"></i> {{ __('admin.remove') }}
                </button>
            </td>
        </tr>
    </template>
</div>

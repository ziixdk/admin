<footer class="border-t border-gray-200 bg-white py-3 px-4 @if (!empty($fixedFooter)) sticky bottom-0 z-10 shadow-sm @endif">
    {{ csrf_field() }}

    <div class="grid grid-cols-12 gap-x-4">
        <div class="col-span-{{ $width['label'] ?? 2 }}"></div>
        <div class="col-span-{{ $width['field'] ?? 10 }} flex items-center gap-3 flex-wrap">

            @if(in_array('reset', $buttons))
            <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                {{ trans('admin.reset') }}
            </button>
            @endif

            @if(in_array('submit', $buttons))
            <div class="flex items-center gap-3 flex-wrap">
                @foreach($submit_redirects as $value => $redirect)
                    @if(in_array($redirect, $checkboxes))
                    <label class="flex items-center gap-1.5 text-sm text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded after-submit"
                            id="after-save-{{ $redirect }}" name="after-save" value="{{ $value }}"
                            {{ ($default_check == $redirect) ? 'checked' : '' }}>
                        {{ trans("admin.{$redirect}") }}
                    </label>
                    @endif
                @endforeach
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                    {{ trans('admin.submit') }}
                </button>
            </div>
            @endif

        </div>
    </div>
</footer>

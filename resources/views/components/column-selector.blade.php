<div x-data="{ open: false }" class="relative grid-column-selector" id="grid-column-selector" data-defaults='{{ implode(",",$defaults) }}'>
    <button type="button" @click="open = !open" @click.outside="open = false"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
        <i class="icon-table"></i>
        <i class="icon-chevron-down text-xs"></i>
    </button>
    <div x-show="open" x-transition class="absolute end-0 top-full mt-1 w-52 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1" style="display:none;">
        @foreach($columns as $key => $label)
        @php
        if (empty($visible)) {
            $checked = 'checked';
        } else {
            $checked = in_array($key, $visible) ? 'checked' : '';
        }
        @endphp
        <label class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 cursor-pointer" for="column-select-{{ $key }}">
            <input type="checkbox" class="rounded border-gray-300 text-blue-600 column-selector" id="column-select-{{ $key }}" value="{{ $key }}" {{ $checked }} />
            {{ $label }}
        </label>
        @endforeach
        <div class="border-t border-gray-100 px-3 py-2 flex justify-end gap-2">
            <button class="px-2 py-1 text-xs font-medium text-gray-600 bg-gray-100 rounded hover:bg-gray-200 column-select-all" onclick="admin.grid.columns.all()">{{ __('admin.all') }}</button>
            <button class="px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 column-select-submit" onclick="admin.grid.columns.submit()">{{ __('admin.submit') }}</button>
        </div>
    </div>
</div>

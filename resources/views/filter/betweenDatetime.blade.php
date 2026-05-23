<div class="grid grid-cols-12 gap-x-4 mb-4 items-start">
    <label class="col-span-2 text-sm font-medium text-gray-700 pt-2.5">{{ $label }}</label>
    <div class="col-span-10">
        <div class="admin-input-group">
            <div class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-e-0 border-gray-300 rounded-s-lg">
                <i class="icon-calendar text-xs"></i>
            </div>
            <input type="text"
                   class="bg-gray-50 border border-s-0 border-e-0 border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   id="{{ $id['start'] }}" placeholder="{{ $label }}" name="{{ $name['start'] }}"
                   value="{{ request()->input("{$column}.start", \Illuminate\Support\Arr::get($value, 'start')) }}"
                   autocomplete="off"/>
            <span class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-s-0 border-e-0 border-gray-300">-</span>
            <input type="text"
                   class="bg-gray-50 border border-s-0 border-gray-300 text-gray-900 text-sm rounded-e-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                   id="{{ $id['end'] }}" placeholder="{{ $label }}" name="{{ $name['end'] }}"
                   value="{{ request()->input("{$column}.end", \Illuminate\Support\Arr::get($value, 'end')) }}"
                   autocomplete="off"/>
        </div>
    </div>
</div>

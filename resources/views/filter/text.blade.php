<div class="admin-input-group">

    @if($group)
    <div x-data="{ open: false }" class="relative shrink-0">
        <input type="hidden" name="{{ $id }}_group" class="{{ $group_name }}-operation" value="0"/>
        <button type="button" @click="open = !open" @click.outside="open = false"
            class="h-full inline-flex items-center gap-1 px-3 text-sm text-gray-600 bg-gray-50 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 min-w-[3rem]">
            <span class="{{ $group_name }}-label">{{ $default['label'] }}</span>
            <i class="icon-chevron-down text-xs"></i>
        </button>
        <ul x-show="open" x-transition
            class="absolute start-0 top-full mt-1 w-36 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1 {{ $group_name }}"
            style="display: none;">
            @foreach($group as $index => $item)
            <li>
                <a class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100" href="#" data-index="{{ $index }}">{{ $item['label'] }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-gray-300 @if($group) border-e-0 @else rounded-s-lg @endif">
        <i class="icon-{{ $icon }} text-xs"></i>
    </div>

    <input type="{{ $type }}" class="bg-gray-50 border border-s-0 border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block rounded-e-lg w-full p-2.5 {{ $id }}" placeholder="{{ $placeholder }}" name="{{ $name }}" value="{{ request($name, $value) }}">
</div>

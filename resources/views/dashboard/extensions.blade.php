<div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800">Available extensions</h3>
        <button type="button" @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 rounded">
            <i class="text-xs" :class="open ? 'icon-minus' : 'icon-plus'"></i>
        </button>
    </div>
    <div x-show="open" x-collapse>
        <ul class="divide-y divide-gray-100">
            @foreach($extensions as $extension)
            <li class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50">
                <i class="icon-{{$extension['icon']}} text-xl text-gray-400"></i>
                <a href="{{ $extension['link'] }}" target="_blank" class="flex-1 text-sm font-medium text-gray-700 hover:text-blue-600">
                    {{ $extension['name'] }}
                </a>
                @if($extension['installed'])
                    <i class="icon-check text-green-500"></i>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
    <div class="px-4 py-3 border-t border-gray-100 text-center">
        <a href="https://github.com/open-admin-org" target="_blank" class="text-sm text-blue-600 hover:text-blue-700 uppercase font-medium">View All Extensions</a>
    </div>
</div>

<div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800">Dependencies</h3>
        <button type="button" @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 rounded">
            <i class="icon-minus text-xs" :class="open ? 'icon-minus' : 'icon-plus'"></i>
        </button>
    </div>
    <div x-show="open" x-collapse class="overflow-x-auto">
        <table class="w-full text-sm">
            @foreach($dependencies as $dependency => $version)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                <td class="px-4 py-2.5 w-60 text-gray-700">{{ $dependency }}</td>
                <td class="px-4 py-2.5">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">{{ $version }}</span>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

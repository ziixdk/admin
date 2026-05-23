<div x-data="{ open: true }" class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800">Environment</h3>
        <button type="button" @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 rounded">
            <i class="text-xs" :class="open ? 'icon-minus' : 'icon-plus'"></i>
        </button>
    </div>
    <div x-show="open" x-collapse class="overflow-x-auto">
        <table class="w-full text-sm">
            @foreach($envs as $env)
            <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                <td class="px-4 py-2.5 w-36 text-gray-500 font-medium">{{ $env['name'] }}</td>
                <td class="px-4 py-2.5 text-gray-700">{{ $env['value'] }}</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

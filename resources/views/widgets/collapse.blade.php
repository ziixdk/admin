<div {!! $attributes !!} class="space-y-px">
    @foreach($items as $key => $item)
    <div x-data="{ open: {{ $key == 0 ? 'true' : 'false' }} }" class="bg-white border border-gray-200 {{ $key == 0 ? 'rounded-t-lg' : '' }} {{ $key == count($items)-1 ? 'rounded-b-lg' : '' }}">
        <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 text-left text-sm font-medium text-gray-800 hover:bg-gray-50">
            <span>{{ $item['title'] }}</span>
            <i class="icon-chevron-down text-xs transition-transform" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" x-collapse class="border-t border-gray-100 p-4 text-sm text-gray-700">
            {!! $item['content'] !!}
        </div>
    </div>
    @endforeach
</div>

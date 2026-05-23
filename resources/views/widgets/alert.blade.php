@php
$style_map = [
    'danger'  => 'bg-red-50 border-red-200 text-red-800',
    'success' => 'bg-green-50 border-green-200 text-green-800',
    'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-800',
    'info'    => 'bg-blue-50 border-blue-200 text-blue-800',
    'primary' => 'bg-blue-50 border-blue-200 text-blue-800',
    'dark'    => 'bg-gray-100 border-gray-300 text-gray-800',
];
$cls = $style_map[$style] ?? 'bg-gray-50 border-gray-200 text-gray-800';
@endphp
<div {!! $attributes !!} x-data="{ show: true }" x-show="show" class="{{ $cls }} border rounded-lg p-4 mb-4">
    <div class="flex items-start gap-3">
        <i class="icon icon-{{ $icon }} mt-0.5"></i>
        <div class="flex-1">
            <h4 class="font-semibold text-sm mb-1">{{ $title }}</h4>
            <div class="text-sm">{!! $content !!}</div>
        </div>
        <button type="button" @click="show = false" class="text-current opacity-50 hover:opacity-100">
            <i class="icon-times text-xs"></i>
        </button>
    </div>
</div>

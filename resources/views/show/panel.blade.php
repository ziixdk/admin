@php
$border_map = [
    'primary' => 'border-t-blue-500',
    'info'    => 'border-t-cyan-500',
    'success' => 'border-t-green-500',
    'warning' => 'border-t-yellow-500',
    'danger'  => 'border-t-red-500',
    'default' => 'border-t-gray-300',
];
$top_border = ($style !== 'none') ? ($border_map[$style] ?? 'border-t-gray-300') : '';
@endphp
<div class="bg-white rounded-lg shadow-sm border border-gray-200 {{ $top_border }} {{ $style !== 'none' ? 'border-t-2' : '' }}">
    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
        <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
        <div class="flex items-center gap-2">
            {!! $tools !!}
        </div>
    </div>
    <div class="p-4">
        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach
    </div>
</div>

@php
$color_map = [
    'primary'   => ['bg' => 'bg-blue-50 border-blue-200',   'icon' => 'text-blue-600',  'link' => 'text-blue-600 hover:text-blue-700'],
    'success'   => ['bg' => 'bg-green-50 border-green-200',  'icon' => 'text-green-600', 'link' => 'text-green-600 hover:text-green-700'],
    'danger'    => ['bg' => 'bg-red-50 border-red-200',      'icon' => 'text-red-600',   'link' => 'text-red-600 hover:text-red-700'],
    'warning'   => ['bg' => 'bg-yellow-50 border-yellow-200','icon' => 'text-yellow-600','link' => 'text-yellow-600 hover:text-yellow-700'],
    'info'      => ['bg' => 'bg-cyan-50 border-cyan-200',    'icon' => 'text-cyan-600',  'link' => 'text-cyan-600 hover:text-cyan-700'],
    'secondary' => ['bg' => 'bg-gray-50 border-gray-200',    'icon' => 'text-gray-600',  'link' => 'text-gray-600 hover:text-gray-700'],
];
$c = $color_map[$color] ?? $color_map['primary'];
@endphp
<div {!! $attributes !!} class="rounded-lg border {{ $c['bg'] }} overflow-hidden">
    <div class="flex items-center gap-4 px-4 py-4">
        <div class="{{ $c['icon'] }} text-3xl">
            <i class="icon-{{ $icon }}"></i>
        </div>
        <div>
            <div class="text-2xl font-bold text-gray-800">{{ $info }}</div>
            <div class="text-sm text-gray-600">{{ $name }}</div>
        </div>
    </div>
    <a href="{{ $link }}" class="flex items-center justify-between px-4 py-2 border-t border-current border-opacity-20 text-sm {{ $c['link'] }}">
        <span>{{ $link_text }}</span>
        <i class="icon-arrow-circle-right"></i>
    </a>
</div>

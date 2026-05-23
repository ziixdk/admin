<div class="grid grid-cols-12 gap-4 py-2.5 border-b border-gray-100 last:border-0">
    <label class="col-span-{{ $width['label'] }} text-sm font-medium text-gray-500 pt-0.5">{{ $label }}</label>
    <div class="col-span-{{ $width['field'] }} show-value text-sm text-gray-900">
        @if($wrapped)
        <div class="bg-gray-50 rounded-lg border border-gray-200 px-3 py-2">
            @if($escape)
                {{ $content }}&nbsp;
            @else
                {!! $content !!}&nbsp;
            @endif
        </div>
        @else
            @if($escape)
                {{ $content }}
            @else
                {!! $content !!}
            @endif
        @endif
    </div>
</div>

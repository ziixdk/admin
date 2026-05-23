<div {!! $attributes !!}>
    @if($title || $tools)
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
            <div class="flex items-center gap-1">
                @foreach($tools as $tool)
                    {!! $tool !!}
                @endforeach
            </div>
        </div>
    @endif
    <div id="{{$id}}-body" class="p-4">
        {!! $content !!}
    </div>
    @if($footer)
        <div class="px-4 py-3 border-t border-gray-100">
            {!! $footer !!}
        </div>
    @endif
</div>
<script>
    {!! $script !!}
</script>

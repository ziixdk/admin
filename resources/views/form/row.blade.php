<div class="grid grid-cols-12 gap-4">
    @foreach($fields as $field)
    <div class="col-span-{{ $field['width'] }}">
        {!! $field['element']->render() !!}
    </div>
    @endforeach
</div>

<div class="space-y-4">
    <div>
        {!! $panel !!}
    </div>

    <div class="space-y-4">
        @foreach($relations as $relation)
            {!! $relation->render() !!}
        @endforeach
    </div>
</div>

@if(is_array($errorKey))

    @foreach($errorKey as $key => $col)
        @if($errors->has($col.$key))
        <div class="mb-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
            <ul class="m-0 list-disc ps-4">
            @foreach($errors->get($col.$key) as $message)
                <li>{{ $message }}</li>
            @endforeach
            </ul>
        </div>
        @endif
    @endforeach

@else

    @if($errors->has($errorKey))
    <div class="mb-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
        <ul class="m-0 list-disc ps-4">
        @foreach($errors->get($errorKey) as $message)
            <li>{{ $message }}</li>
        @endforeach
        </ul>
    </div>
    @endif

@endif

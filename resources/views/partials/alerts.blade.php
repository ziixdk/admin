@if($error = session()->get('error'))
    <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 p-4 mb-4 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i class="icon-ban mt-0.5 shrink-0"></i>
        <div class="flex-1">
            <p class="font-medium">{{ \Illuminate\Support\Arr::get($error->get('title'), 0) }}</p>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($error->get('message'), 0) !!}</p>
        </div>
        <button @click="show = false" class="text-red-400 hover:text-red-600 p-0.5"><i class="icon-times text-xs"></i></button>
    </div>
@elseif ($errors = session()->get('errors'))
    @if ($errors->hasBag('error'))
    <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 p-4 mb-4 text-red-800 bg-red-50 border border-red-200 rounded-lg">
        <i class="icon-ban mt-0.5 shrink-0"></i>
        <div class="flex-1 text-sm">
            @foreach($errors->getBag("error")->toArray() as $message)
                <p>{!! \Illuminate\Support\Arr::get($message, 0) !!}</p>
            @endforeach
        </div>
        <button @click="show = false" class="text-red-400 hover:text-red-600 p-0.5"><i class="icon-times text-xs"></i></button>
    </div>
    @endif
@endif

@if($success = session()->get('success'))
    <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 p-4 mb-4 text-green-800 bg-green-50 border border-green-200 rounded-lg">
        <i class="icon-check-circle mt-0.5 shrink-0"></i>
        <div class="flex-1">
            <p class="font-medium">{{ \Illuminate\Support\Arr::get($success->get('title'), 0) }}</p>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($success->get('message'), 0) !!}</p>
        </div>
        <button @click="show = false" class="text-green-400 hover:text-green-600 p-0.5"><i class="icon-times text-xs"></i></button>
    </div>
@endif

@if($info = session()->get('info'))
    <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 p-4 mb-4 text-blue-800 bg-blue-50 border border-blue-200 rounded-lg">
        <i class="icon-info-circle mt-0.5 shrink-0"></i>
        <div class="flex-1">
            <p class="font-medium">{{ \Illuminate\Support\Arr::get($info->get('title'), 0) }}</p>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($info->get('message'), 0) !!}</p>
        </div>
        <button @click="show = false" class="text-blue-400 hover:text-blue-600 p-0.5"><i class="icon-times text-xs"></i></button>
    </div>
@endif

@if($warning = session()->get('warning'))
    <div x-data="{ show: true }" x-show="show" class="flex items-start gap-3 p-4 mb-4 text-yellow-800 bg-yellow-50 border border-yellow-200 rounded-lg">
        <i class="icon-exclamation-triangle mt-0.5 shrink-0"></i>
        <div class="flex-1">
            <p class="font-medium">{{ \Illuminate\Support\Arr::get($warning->get('title'), 0) }}</p>
            <p class="text-sm mt-1">{!! \Illuminate\Support\Arr::get($warning->get('message'), 0) !!}</p>
        </div>
        <button @click="show = false" class="text-yellow-400 hover:text-yellow-600 p-0.5"><i class="icon-times text-xs"></i></button>
    </div>
@endif

<div class="flex items-center gap-1">
    <button type="button" class="btn-filter inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 {{ $expand ? 'active' : '' }}"
        data-filter-target="#{{ $filter_id }}" title="{{ trans('admin.filter') }}">
        <i class="icon-filter"></i>
        <span class="hidden sm:inline">{{ trans('admin.filter') }}</span>
        @if($scopes->isEmpty())
        <i class="icon-angle-down text-xs"></i>
        @endif
    </button>

    @if($scopes->isNotEmpty())
    <div x-data="{ open: false }" class="relative">
        <button type="button" @click="open = !open" @click.outside="open = false"
            class="inline-flex items-center gap-1 px-2 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <span>{{ $label }}</span>
            <i class="icon-chevron-down text-xs"></i>
        </button>
        <ul x-show="open" x-transition class="absolute start-0 top-full mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1"
            style="display: none;">
            @foreach($scopes as $scope)
                <li class="px-1">{!! $scope->render() !!}</li>
            @endforeach
            <li class="px-2 py-1"><hr class="border-gray-200"></li>
            <li><a class="block px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-100 rounded" href="{{ $cancel }}">{{ trans('admin.cancel') }}</a></li>
        </ul>
    </div>
    @endif
</div>

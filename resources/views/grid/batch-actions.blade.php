@if(!$holdAll)
    <div x-data="{ open: false }" class="{{ $all }}-holder show-on-rows-selected hidden me-1 relative" style="display: none;">
        <button type="button" @click="open = !open" @click.outside="open = false"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
            <span class="selected" data="{{ trans('admin.grid_items_selected') }}"></span>
            <i class="icon-chevron-down text-xs"></i>
        </button>
        @if(!$actions->isEmpty())
        <ul x-show="open" x-transition class="absolute start-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1"
            style="display: none;" role="menu">
            @foreach($actions as $action)
                <li class="px-1">{!! $action->render() !!}</li>
            @endforeach
        </ul>
        @endif
    </div>
@endif

<div x-data="{ open: false }" class="grid-dropdown-actions relative">
    <button type="button" @click="open = !open" @click.outside="open = false"
        class="grid-actions-dropdown p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg">
        <i class="icon-ellipsis-v"></i>
    </button>
    <ul x-show="open" x-transition class="absolute end-0 top-full mt-1 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1 grid-actions-menu"
        style="display: none;" role="menu">

        @foreach($default as $action)
            <li class="px-1">{!! $action->render() !!}</li>
        @endforeach

        @if(!empty($custom))

            @if(!empty($default))
            <li class="px-2 py-1"><hr class="border-gray-200"></li>
            @endif

            @foreach($custom as $action)
                <li class="px-1">{!! $action->render() !!}</li>
            @endforeach
        @endif
    </ul>
</div>

@yield('child')

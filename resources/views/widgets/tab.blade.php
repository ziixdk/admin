<div {!! $attributes !!}>
    <div class="flex items-center justify-between border-b border-gray-200 mb-4">
        <ul class="flex flex-wrap -mb-px">
            @foreach ($tabs as $i => $tab)
                @if ($tab['type'] == \OpenAdmin\Admin\Widgets\Tab::TYPE_CONTENT)
                    <li class="nav-item">
                        <a class="nav-link inline-flex items-center px-4 py-2.5 text-sm font-medium border-b-2 -mb-px {{ $active === $i ? 'text-blue-600 border-blue-600 active' : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' }}"
                           href="#{{ $tab['ref'] }}">{{ $tab['title'] }}</a>
                    </li>
                @elseif($tab['type'] == \OpenAdmin\Admin\Widgets\Tab::TYPE_LINK)
                    <li class="nav-item">
                        <a class="nav-link inline-flex items-center px-4 py-2.5 text-sm font-medium border-b-2 -mb-px {{ $active === $i ? 'text-blue-600 border-blue-600 active' : 'text-gray-500 border-transparent hover:text-gray-700 hover:border-gray-300' }}"
                           href="{{ $tab['href'] }}">{{ $tab['title'] }}</a>
                    </li>
                @endif
            @endforeach

            @if (!empty($dropDown))
                <li x-data="{ open: false }" class="relative nav-item">
                    <a @click="open = !open" @click.outside="open = false" href="#"
                       class="nav-link inline-flex items-center gap-1 px-4 py-2.5 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700">
                        Dropdown <i class="icon-chevron-down text-xs"></i>
                    </a>
                    <ul x-show="open" class="absolute start-0 top-full mt-1 w-40 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1" style="display:none;">
                        @foreach ($dropDown as $link)
                            <li><a href="{{ $link['href'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">{{ $link['name'] }}</a></li>
                        @endforeach
                    </ul>
                </li>
            @endif
        </ul>
        @if($title)
            <span class="text-sm font-medium text-gray-500 pe-2">{{ $title }}</span>
        @endif
    </div>

    <div class="tab-content">
        @foreach ($tabs as $i => $tab)
            <div class="tab-pane {{ $active === $i ? 'active' : '' }}" id="{{ $tab['ref'] }}">
                @php($content = \Illuminate\Support\Arr::get($tab, 'content'))
                @if ($content instanceof \Illuminate\Contracts\Support\Renderable)
                    {!! $content->render() !!}
                @else
                    {!! $content !!}
                @endif
            </div>
        @endforeach
    </div>
</div>

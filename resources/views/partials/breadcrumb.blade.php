<!-- breadcrumb start -->
@if ($breadcrumb || config('admin.enable_default_breadcrumb'))
<nav aria-label="breadcrumb" class="breadcrumb-nav mb-4">
    <ol class="flex flex-wrap items-center gap-1 text-sm text-gray-500">
        <li class="flex items-center">
            <a href="{{ admin_url('/') }}" class="flex items-center gap-1 text-gray-500 hover:text-blue-600">
                <i class="icon-home text-xs"></i>
                <span>{{ __('Home') }}</span>
            </a>
        </li>
        @if ($breadcrumb)
            @foreach($breadcrumb as $item)
                <li class="flex items-center gap-1">
                    <i class="icon-chevron-right text-xs text-gray-400"></i>
                    @if($loop->last)
                        <span class="text-gray-700 font-medium">
                            @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                <i class="icon-{{ $item['icon'] }}"></i>
                            @endif
                            {{ $item['text'] }}
                        </span>
                    @else
                        @if (\Illuminate\Support\Arr::has($item, 'url'))
                            <a href="{{ admin_url(\Illuminate\Support\Arr::get($item, 'url')) }}" class="text-gray-500 hover:text-blue-600">
                                @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                    <i class="icon-{{ $item['icon'] }}"></i>
                                @endif
                                {{ $item['text'] }}
                            </a>
                        @else
                            <span>
                                @if (\Illuminate\Support\Arr::has($item, 'icon'))
                                    <i class="icon-{{ $item['icon'] }}"></i>
                                @endif
                                {{ $item['text'] }}
                            </span>
                        @endif
                    @endif
                </li>
            @endforeach
        @else
            @for($i = 2; $i <= count(Request::segments()); $i++)
            <li class="flex items-center gap-1">
                <i class="icon-chevron-right text-xs text-gray-400"></i>
                <a href="{{ admin_url(implode('/', array_slice(Request::segments(), 1, $i - 1))) }}"
                    class="text-gray-500 hover:text-blue-600">
                    {{ ucfirst(Request::segment($i)) }}
                </a>
            </li>
            @endfor
        @endif
    </ol>
</nav>
@endif
<!-- breadcrumb end -->

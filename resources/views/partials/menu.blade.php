@if(Admin::user()->visible(\Illuminate\Support\Arr::get($item, 'roles', [])) && Admin::user()->can(\Illuminate\Support\Arr::get($item, 'permission')))
    @if(!isset($item['children']))
        <li>
            @if(url()->isValidUrl($item['uri']))
                <a href="{{ $item['uri'] }}" target="_blank"
            @else
                <a href="{{ admin_url($item['uri']) }}"
            @endif
                class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors">
                <i class="{{ $item['icon'] }} w-4 text-center shrink-0"></i>
                <span class="sidebar-long truncate">
                    @if (Lang::has($titleTranslation = 'admin.menu_titles.' . trim(str_replace(' ', '_', strtolower($item['title'])))))
                        {{ __($titleTranslation) }}
                    @else
                        {{ admin_trans($item['title']) }}
                    @endif
                </span>
            </a>
        </li>
    @else
        <li x-data="{ open: false }" class="treeview">
            <a href="#" class="has-subs flex items-center gap-3 px-4 py-2.5 text-sm text-gray-300 hover:bg-gray-700 hover:text-white transition-colors cursor-pointer"
                @click.prevent="open = !open">
                <i class="{{ $item['icon'] }} w-4 text-center shrink-0"></i>
                <span class="sidebar-long flex-1 truncate">
                    @if (Lang::has($titleTranslation = 'admin.menu_titles.' . trim(str_replace(' ', '_', strtolower($item['title'])))))
                        {{ __($titleTranslation) }}
                    @else
                        {{ admin_trans($item['title']) }}
                    @endif
                </span>
                <i class="sidebar-long icon-chevron-down text-xs transition-transform duration-200 shrink-0" :class="open ? 'rotate-180' : ''"></i>
            </a>
            <ul x-show="open" x-collapse class="list-none ps-0 m-0 bg-gray-800/50">
                @foreach($item['children'] as $item)
                    @include('admin::partials.menu', $item)
                @endforeach
            </ul>
        </li>
    @endif
@endif

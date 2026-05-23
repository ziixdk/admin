<aside id="sidebar" class="fixed top-16 start-0 h-[calc(100vh-4rem)] bg-gray-900 flex flex-col z-30 overflow-y-auto overflow-x-hidden transition-[width] duration-300 shrink-0">

    @if(config('admin.enable_user_panel'))
    <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-700">
        <img src="{{ Admin::user()->avatar }}" class="w-9 h-9 rounded-full object-cover shrink-0" alt="{{ Admin::user()->name }}">
        <div class="sidebar-long overflow-hidden">
            <p class="text-white text-sm font-medium truncate">{{ Admin::user()->name }}</p>
            <p class="text-green-400 text-xs">{{ trans('admin.online') }}</p>
        </div>
    </div>
    @endif

    @if(config('admin.enable_menu_search'))
    <form class="sidebar-form sidebar-long px-3 py-2 border-b border-gray-700" style="overflow: initial;" onsubmit="return false;">
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <i class="icon-search text-gray-400 text-xs"></i>
            </div>
            <input type="text" autocomplete="off"
                class="autocomplete w-full bg-gray-800 text-gray-200 text-sm rounded-lg ps-8 pe-3 py-2 placeholder-gray-500 border border-gray-700 focus:outline-none focus:border-gray-500"
                placeholder="Search...">
            <div class="absolute start-0 top-full mt-1 w-full bg-gray-800 border border-gray-700 rounded-lg shadow-lg z-50 max-h-60 overflow-auto hidden" role="menu">
                @foreach(Admin::menuLinks() as $link)
                <a href="{{ admin_url($link['uri']) }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-300 hover:bg-gray-700 hover:text-white">
                    <i class="{{ $link['icon'] }} text-xs"></i>{{ admin_trans($link['title']) }}
                </a>
                @endforeach
            </div>
        </div>
    </form>
    @endif

    <nav class="flex-1 py-2">
        <div class="custom-menu">
            <ul class="list-none ps-0 m-0 root" id="menu">
                @each('admin::partials.menu', Admin::menu(), 'item')
            </ul>
        </div>
    </nav>

</aside>

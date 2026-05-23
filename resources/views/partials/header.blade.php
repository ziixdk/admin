<!-- Main Header -->
<header class="custom-navbar fixed top-0 start-0 end-0 z-40 h-16 bg-white border-b border-gray-200 flex items-stretch">

    <a class="navbar-brand flex items-center justify-center w-64 bg-gray-900 text-white px-4 shrink-0 transition-[width] duration-300 no-ajax" href="{{ admin_url('/') }}">
        <span class="sidebar-short hidden font-bold text-lg">{!! config('admin.logo-mini', config('admin.name')) !!}</span>
        <span class="sidebar-long font-bold text-lg truncate">{!! config('admin.logo', config('admin.name')) !!}</span>
    </a>

    <div class="flex flex-1 items-center px-2">

        <button type="button" id="menu-toggle" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation"
            class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 me-2">
            <i class="icon-bars text-lg"></i>
        </button>

        <ul class="flex items-center hidden lg:flex">
            {!! Admin::getNavbar()->render('left') !!}
        </ul>

        <ul class="flex items-center ms-auto gap-1">

            {!! Admin::getNavbar()->render() !!}

            <li x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 text-sm text-gray-700">
                    <img src="{{ Admin::user()->avatar }}" alt="{{ Admin::user()->name }}"
                        class="w-8 h-8 rounded-full object-cover">
                    <span class="hidden md:block">{{ Admin::user()->name }}</span>
                    <i class="icon-chevron-down text-xs text-gray-400"></i>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute end-0 mt-1 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50 overflow-hidden"
                    style="display: none;">
                    <div class="bg-gray-900 px-4 py-3 text-center">
                        <img src="{{ Admin::user()->avatar }}" alt="{{ Admin::user()->name }}"
                            class="w-14 h-14 rounded-full object-cover mx-auto mb-2">
                        <p class="text-white font-medium text-sm">{{ Admin::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ __('admin.online') }}</p>
                    </div>
                    <div class="p-2 flex gap-2">
                        <a href="{{ admin_url('auth/setting') }}"
                            class="flex-1 text-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            {{ __('admin.setting') }}
                        </a>
                        <a href="{{ admin_url('auth/logout') }}"
                            class="flex-1 text-center px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 no-ajax">
                            {{ __('admin.logout') }}
                        </a>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</header>
<!-- /Main Header -->

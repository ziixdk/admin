<footer class="flex items-center justify-between px-4 py-3 mt-4 border-t border-gray-200 text-sm text-gray-500">
    <span>Powered by <a href="https://github.com/wishbone-productions/open-admin" target="_blank" class="text-blue-600 hover:text-blue-700">open-admin</a></span>
    <div class="flex items-center gap-4">
        @if(config('admin.show_environment'))
            <span><strong class="font-medium text-gray-700">Env</strong> {!! config('app.env') !!}</span>
        @endif
        @if(config('admin.show_version'))
            <span><strong class="font-medium text-gray-700">Version</strong> {!! \OpenAdmin\Admin\Admin::VERSION !!}</span>
        @endif
    </div>
</footer>

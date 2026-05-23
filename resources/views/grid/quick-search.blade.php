<form action="{!! $action !!}" pjax-container class="inline-flex">
    <div class="admin-input-group">
        <div class="inline-flex items-center px-3 text-sm text-gray-500 bg-gray-50 border border-e-0 border-gray-300 rounded-s-lg">
            <i class="icon-search text-xs"></i>
        </div>
        <input type="text" name="{{ $key }}" class="bg-gray-50 border border-s-0 border-e-0 border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-48 p-2 grid-quick-search" value="{{ $value }}" placeholder="{{ $placeholder }}">
        <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 border border-s-0 border-gray-300 rounded-e-lg hover:bg-gray-100">
            <i class="icon-search text-xs"></i>
        </button>
    </div>
</form>

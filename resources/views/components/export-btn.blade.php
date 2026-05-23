<div x-data="{ open: false }" class="relative flex me-1">
    <a href="{{$grid->getExportUrl('all')}}" target="_blank"
       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-s-lg hover:bg-blue-700"
       title="{{trans('admin.export')}}">
        <i class="icon-download"></i>
        <span class="hidden sm:inline">{{trans('admin.export')}}</span>
    </a>
    <button type="button" @click="open = !open" @click.outside="open = false"
        class="inline-flex items-center px-2 py-1.5 text-sm font-medium text-white bg-blue-600 border-s border-blue-500 rounded-e-lg hover:bg-blue-700">
        <i class="icon-chevron-down text-xs"></i>
    </button>
    <ul x-show="open" x-transition class="absolute end-0 top-full mt-1 w-44 bg-white rounded-lg shadow-lg border border-gray-200 z-50 py-1" style="display:none;" role="menu">
        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{$grid->getExportUrl('all')}}" target="_blank">{{trans('admin.all')}}</a></li>
        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{$grid->getExportUrl('page', $page)}}" target="_blank">{{trans('admin.current_page')}}</a></li>
        <li><a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" href="{{$grid->getExportUrl('selected', '__rows__')}}" target="_blank" onclick="admin.grid.export_selected_row(event);" data-no_rows_selected="{{__('admin.no_rows_selected')}}">{{trans('admin.selected_rows')}}</a></li>
    </ul>
</div>

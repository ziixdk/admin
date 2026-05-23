<div class="filter-box border-b border-gray-200 {{ $expand ? 'show' : '' }} filter-box" id="{{ $filterID }}">
    <form action="{!! $action !!}" class="px-4 py-4 form-horizontal" pjax-container method="get" autocomplete="off">

        <div class="grid grid-cols-12 gap-4 mb-4">
            @foreach($layout->columns() as $column)
            <div class="col-span-{{ $column->width() }}">
                <div class="fields-group">
                    @foreach($column->filters() as $filter)
                        {!! $filter->render() !!}
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>

        <div class="flex items-center gap-2 pt-2 border-t border-gray-100">
            <button type="submit" class="submit inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                <i class="icon-search"></i> {{ trans('admin.search') }}
            </button>
            <a href="{!! $action !!}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="icon-undo"></i> {{ trans('admin.reset') }}
            </a>
        </div>

    </form>
</div>

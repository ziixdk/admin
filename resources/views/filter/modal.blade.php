<div class="flex items-center gap-2 me-2">
    <button type="button" data-modal-target="{{ $modalID }}" data-modal-toggle="{{ $modalID }}"
        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
        <i class="icon-filter"></i> {{ trans('admin.filter') }}
    </button>
    <a href="{!! $action !!}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
        <i class="icon-undo"></i> {{ trans('admin.reset') }}
    </a>
</div>

<!-- Filter Modal -->
<div id="{{ $modalID }}" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-lg shadow">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                <h3 class="text-lg font-semibold text-gray-900">{{ trans('admin.filter') }}</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center"
                    data-modal-hide="{{ $modalID }}">
                    <i class="icon-times"></i>
                </button>
            </div>
            <form action="{!! $action !!}" method="get" pjax-container>
                <div class="p-4 md:p-5 space-y-4">
                    @foreach($filters as $filter)
                        <div>{!! $filter->render() !!}</div>
                    @endforeach
                </div>
                <div class="flex items-center gap-3 p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button type="submit" class="submit px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        {{ trans('admin.submit') }}
                    </button>
                    <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        {{ trans('admin.reset') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

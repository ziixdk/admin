<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="flex flex-wrap items-center gap-2 px-4 py-3 border-b border-gray-100">
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 {{ $id }}-tree-tools" data-action="expand" title="{{ trans('admin.expand') }}" onclick="admin.tree.expand(); return false;" href="#">
            <i class="icon-plus-square"></i>&nbsp;{{ trans('admin.expand') }}
        </a>
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 {{ $id }}-tree-tools" data-action="collapse" title="{{ trans('admin.collapse') }}" onclick="admin.tree.collapse(); return false;" href="#">
            <i class="icon-minus-square"></i>&nbsp;{{ trans('admin.collapse') }}
        </a>

        @if($useSave)
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 {{ $id }}-save" title="{{ trans('admin.save') }}" onclick="admin.tree.save(); return false;" href="#">
            <i class="icon-save"></i>&nbsp;{{ trans('admin.save') }}
        </a>
        @endif

        @if($useRefresh)
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 {{ $id }}-refresh" title="{{ trans('admin.refresh') }}" onclick="admin.ajax.reload(); return false;" href="#">
            <i class="icon-refresh"></i>&nbsp;{{ trans('admin.refresh') }}
        </a>
        @endif

        {!! $tools !!}

        @if($useCreate)
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 ms-auto" href="{{ url($path) }}/create">
            <i class="icon-plus"></i>&nbsp;{{ trans('admin.new') }}
        </a>
        @endif
    </div>

    <div class="p-4 overflow-x-auto">
        <div class="dd" id="{{ $id }}">
            <ol class="dd-list">
                @each($branchView, $items, 'branch')
            </ol>
        </div>
    </div>
</div>

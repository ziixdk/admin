
<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    @if(isset($title))
        <div class="px-4 py-3 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-800">{{ $title }}</h3>
        </div>
    @endif

    <div class="px-4 py-3">
        @if ($grid->showTools() || $grid->showExportBtn() || $grid->showCreateBtn())
        <div class="flex flex-wrap items-center gap-2 justify-between">
            <div class="flex flex-wrap items-center gap-2">
                {!! $grid->renderCreateButton() !!}
                @if ($grid->showTools())
                {!! $grid->renderHeaderTools() !!}
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-2">
                {!! $grid->renderExportButton() !!}
                {!! $grid->renderColumnSelector() !!}
            </div>
        </div>
        @endif
    </div>
    {!! $grid->renderFilter() !!}
    {!! $grid->renderHeader() !!}

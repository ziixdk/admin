<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-100">
        {!! $grid->renderFilter() !!}
    </div>

    <div class="p-4">
        <ul class="flex flex-wrap gap-3">
            @foreach($grid->rows() as $row)
            <li class="relative list-none">
                <label class="cursor-pointer block">
                    {!! $row->column($key) !!}
                    {!! $row->column('__modal_selector__') !!}
                </label>
            </li>
            @endforeach
        </ul>
    </div>

    <div class="px-4 py-3 border-t border-gray-100">
        {!! $grid->paginator() !!}
    </div>
</div>

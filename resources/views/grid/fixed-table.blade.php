@include("admin::grid.table-header")

    <div class="overflow-x-auto">
        <div class="tables-container relative">
            <div class="table-wrap table-main overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-700 grid-table select-table" id="{{ $grid->tableID }}">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            @foreach($grid->visibleColumns() as $column)
                            <th class="px-4 py-3 font-medium whitespace-nowrap" {!! $column->formatHtmlAttributes() !!}>{{ $column->getLabel() }}{!! $column->renderHeader() !!}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @foreach($grid->rows() as $row)
                        <tr class="hover:bg-gray-50 transition-colors" {!! $row->getRowAttributes() !!}>
                            @foreach($grid->visibleColumnNames() as $name)
                            <td class="px-4 py-3 whitespace-nowrap column-{{ $name }}" {!! $row->getColumnAttributes($name) !!}>
                                {!! $row->column($name) !!}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow() !!}

                </table>
            </div>

            @if($grid->leftVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-left absolute top-0 start-0 z-10 bg-white shadow-[4px_0_6px_-2px_rgba(0,0,0,0.1)]">
                <table class="text-sm text-left text-gray-700 grid-table select-table">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        @foreach($grid->leftVisibleColumns() as $column)
                            <th class="px-4 py-3 font-medium whitespace-nowrap" {!! $column->formatHtmlAttributes() !!}>{{ $column->getLabel() }}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">

                    @foreach($grid->rows() as $row)
                        <tr class="hover:bg-gray-50 transition-colors" {!! $row->getRowAttributes() !!}>
                            @foreach($grid->leftVisibleColumns() as $column)
                                @php $name = $column->getName() @endphp
                                <td class="px-4 py-3 whitespace-nowrap column-{{ $name }}" {!! $row->getColumnAttributes($name) !!}>
                                    {!! $row->column($name) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow($grid->leftVisibleColumns()) !!}

                </table>
            </div>
            @endif

            @if($grid->rightVisibleColumns()->isNotEmpty())
            <div class="table-wrap table-fixed table-fixed-right absolute top-0 end-0 z-10 bg-white shadow-[-4px_0_6px_-2px_rgba(0,0,0,0.1)]">
                <table class="text-sm text-left text-gray-700 grid-table select-table">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        @foreach($grid->rightVisibleColumns() as $column)
                            <th class="px-4 py-3 font-medium whitespace-nowrap" {!! $column->formatHtmlAttributes() !!}>{{ $column->getLabel() }}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                    @foreach($grid->rows() as $row)
                        <tr class="hover:bg-gray-50 transition-colors" {!! $row->getRowAttributes() !!}>
                            @foreach($grid->rightVisibleColumns() as $column)
                                @php $name = $column->getName() @endphp
                                <td class="px-4 py-3 whitespace-nowrap column-{{ $name }}" {!! $row->getColumnAttributes($name) !!}>
                                    {!! $row->column($name) !!}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                    </tbody>

                    {!! $grid->renderTotalRow($grid->rightVisibleColumns()) !!}

                </table>
            </div>
            @endif
        </div>
    </div>

    {!! $grid->renderFooter() !!}

    {!! $grid->paginator() !!}

    <!-- /.box-body -->
</div>

<script>
    var tableMain = document.querySelector('.table-main');
    var theadHeight = tableMain.querySelector('thead tr').clientHeight;
    document.querySelectorAll('.table-fixed thead tr').forEach(tr=>{
        tr.style.height = theadHeight+"px";
    })

    let tfoot = tableMain.querySelector('tfoot tr');
    if (tfoot){
        var tfootHeight = tfoot.clientHeight;
        document.querySelectorAll('.table-fixed tfoot tr').forEach(tr=>{
            tr.style.height = tfootHeight+"px";
        })
    }

    let left_trs = document.querySelectorAll('.table-fixed-left tbody tr');
    let right_trs = document.querySelectorAll('.table-fixed-right tbody tr');
    tableMain.querySelectorAll('tbody tr').forEach((tr,i)=>{
        var height = tr.clientHeight;
        if (left_trs[i]) left_trs[i].style.height = height+"px";
        if (right_trs[i]) right_trs[i].style.height = height+"px";
    });

    if (tableMain.clientWidth >= tableMain.scrollWidth) {
        hide(document.querySelectorAll('.table-fixed'));
    }
</script>

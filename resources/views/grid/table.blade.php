@include("admin::grid.table-header")

        <div class="overflow-x-auto" autocomplete="off">
            <table class="w-full text-sm text-left text-gray-700 grid-table select-table" id="{{ $grid->tableID }}">

                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        @foreach($grid->visibleColumns() as $column)
                        <th class="px-4 py-3 font-medium" {!! $column->formatHtmlAttributes() !!}>{!! $column->getLabel() !!}{!! $column->renderHeader() !!}</th>
                        @endforeach
                    </tr>
                </thead>

                @if ($grid->hasQuickCreate())
                    {!! $grid->renderQuickCreate() !!}
                @endif

                <tbody class="divide-y divide-gray-100">

                    @if($grid->rows()->isEmpty() && $grid->showDefineEmptyPage())
                        @include('admin::grid.empty-grid')
                    @endif

                    @foreach($grid->rows() as $row)
                    <tr class="hover:bg-gray-50 transition-colors" {!! $row->getRowAttributes() !!}>
                        @foreach($grid->visibleColumnNames() as $name)
                        <td class="px-4 py-3" {!! $row->getColumnAttributes($name) !!}>
                            {!! $row->column($name) !!}
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>

                {!! $grid->renderTotalRow() !!}

            </table>

        </div>

        {!! $grid->renderFooter() !!}

        {!! $grid->paginator() !!}

    </div>
        <!-- /.box-body -->
</div>

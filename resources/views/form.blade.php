<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-800">{{ $form->title() }}</h3>
        <div class="flex items-center gap-2">
            {!! $form->renderTools() !!}
        </div>
    </div>

    {!! $form->open() !!}

    <div class="p-0">

        @if(!$tabObj->isEmpty())
            @include('admin::form.tab', compact('tabObj'))
        @else
            <div class="fields-group p-4">

                @if($form->hasRows())
                    @foreach($form->getRows() as $row)
                        {!! $row->render() !!}
                    @endforeach
                @else
                    <div class="grid grid-cols-12 gap-4">
                        @foreach($layout->columns() as $column)
                            <div class="col-span-{{ $column->width() }}">
                                @foreach($column->fields() as $field)
                                    {!! $field->render() !!}
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

    </div>

    {!! $form->renderFooter() !!}

    @foreach($form->getHiddenFields() as $field)
        {!! $field->render() !!}
    @endforeach

    {!! $form->close() !!}

</div>

@include("admin::form._header")

        <div id="has-many-{{ $column }}" class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700 table-with-fields has-many-{{ $column }} vertical-align-{{ $verticalAlign }}">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    @if(!empty($options['sortable']))
                        <th class="px-2 py-2 w-8"></th>
                    @endif

                    @foreach($headers as $header)
                        <th class="px-3 py-2">{{ $header }}</th>
                    @endforeach

                    <th class="hidden"></th>

                    @if($options['allowDelete'])
                        <th class="px-3 py-2 w-24"></th>
                    @endif
                </tr>
                </thead>
                <tbody class="has-many-{{ $column }}-forms divide-y divide-gray-100">
                @foreach($forms as $pk => $form)
                    <tr class="has-many-{{ $column }}-form fields-group">

                        @if(!empty($options['sortable']))
                           <td class="px-2 py-2 w-8">
                               <span class="icon-arrows-alt-v text-gray-400 cursor-move handle"></span>
                           </td>
                        @endif

                        <?php $hidden = ''; ?>

                        @foreach($form->fields() as $field)

                            @if (is_a($field, \ZiiX\Admin\Form\Field\Hidden::class))
                                <?php $hidden .= $field->render(); ?>
                                @continue
                            @endif

                            <td class="px-3 py-2">{!! $field->setLabelClass(['hidden'])->setWidth(12, 0)->render() !!}</td>
                        @endforeach

                        <td class="hidden">{!! $hidden !!}</td>

                        @if($options['allowDelete'])
                            <td class="px-3 py-2">
                                <button type="button" class="remove inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                    <i class="icon-trash"></i> {{ trans('admin.remove') }}
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>

            <template class="{{ $column }}-tpl">
                <tr class="has-many-{{ $column }}-form fields-group">

                    @if(!empty($options['sortable']))
                        <td class="px-2 py-2 w-8">
                            <span class="icon-arrows-alt-v text-gray-400 cursor-move handle"></span>
                        </td>
                    @endif

                    {!! $template !!}

                    <td class="px-3 py-2">
                        <button type="button" class="remove inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                            <i class="icon-trash"></i> {{ trans('admin.remove') }}
                        </button>
                    </td>
                </tr>
            </template>

            @if($options['allowCreate'])
                <div class="mt-3">
                    <button type="button" class="add inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                        <i class="icon-plus"></i> {{ trans('admin.new') }}
                    </button>
                </div>
            @endif
        </div>
@include("admin::form._footer")

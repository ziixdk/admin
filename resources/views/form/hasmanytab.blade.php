<div id="has-many-{{ $column }}" class="nav-tabs-custom has-many-{{ $column }}">
    <div class="mb-2">
        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide has-many-head">{{ $label }}</h4>
    </div>

    <hr class="border-gray-200 mb-0">

    <ul class="nav nav-tabs flex flex-wrap border-b border-gray-200 px-2 pt-2 gap-0.5">
        @foreach($forms as $pk => $form)
            <li id="tab_{{ $relationName . '_' . $pk }}" class="nav-item">
                <a class="nav-link inline-block px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors {{ $form == reset($forms) ? 'border-blue-600 text-blue-600 active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                    href="#{{ $relationName . '_' . $pk }}">
                    {{ $pk }} <i class="icon-exclamation-circle text-red-500 hide ms-1"></i>
                </a>
            </li>
        @endforeach
        <li class="nav-item add-tab ms-1">
            <button type="button" class="add inline-flex items-center px-2 py-1.5 text-sm text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg">
                <i class="icon-plus-circle text-lg"></i>
            </button>
        </li>
    </ul>

    <div class="tab-content has-many-{{ $column }}-forms p-4">

        @foreach($forms as $pk => $form)
            <div class="tab-pane fields-group has-many-{{ $column }}-form {{ $form == reset($forms) ? 'active' : '' }}" id="{{ $relationName . '_' . $pk }}">
                @foreach($form->fields() as $field)
                    {!! $field->render() !!}
                @endforeach

                @if($options['allowDelete'])
                <div class="{{ $viewClass['form-group'] }} form-delete-group">
                    <label class="{{ $viewClass['label'] }}"></label>
                    <div class="{{ $viewClass['field'] }} flex justify-end">
                        <button type="button" class="remove inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                            <i class="icon-trash"></i> {{ trans('admin.remove') }}
                        </button>
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    </div>

    <template class="{{ $column }}-tab-tpl">
        <li class="new nav-item" id="tab_{{ $relationName . '_new_' . \ZiiX\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            <a class="nav-link inline-block px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                href="#{{ $relationName . '_new_' . \ZiiX\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
                &nbsp;New {{ \ZiiX\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}
                <i class="icon-exclamation-circle text-red-500 hide ms-1"></i>
            </a>
        </li>
    </template>
    <template class="{{ $column }}-tpl">
        <div class="tab-pane fields-group new" id="{{ $relationName . '_new_' . \ZiiX\Admin\Form\NestedForm::DEFAULT_KEY_NAME }}">
            {!! $template !!}
            @if($options['allowDelete'])
            <div class="{{ $viewClass['form-group'] }} form-delete-group">
                <label class="{{ $viewClass['label'] }}"></label>
                <div class="{{ $viewClass['field'] }} flex justify-end">
                    <button type="button" class="remove inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i class="icon-trash"></i> {{ trans('admin.remove') }}
                    </button>
                </div>
            </div>
            @endif
        </div>
    </template>

</div>

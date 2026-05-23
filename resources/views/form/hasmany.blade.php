
<div class="mb-4">
    <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide has-many-head {{ $column }}">{{ $label }}</h4>
    <hr class="border-gray-200 mt-2 mb-4">
</div>

<div id="has-many-{{ $column }}" class="has-many-body has-many-{{ $column }}">

    <div class="has-many-{{ $column }}-forms">

        @foreach($forms as $pk => $form)
            <div class="has-many-{{ $column }}-form fields-group">

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

                <hr class="border-gray-200 my-4">
            </div>
        @endforeach
    </div>

    <template class="{{ $column }}-tpl">
        <div class="has-many-{{ $column }}-form fields-group">

            {!! $template !!}

            <div class="{{ $viewClass['form-group'] }} form-delete-group">
                <label class="{{ $viewClass['label'] }}"></label>
                <div class="{{ $viewClass['field'] }} flex justify-end">
                    <button type="button" class="remove inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                        <i class="icon-trash"></i> {{ trans('admin.remove') }}
                    </button>
                </div>
            </div>
            <hr class="border-gray-200 my-4">

        </div>
    </template>

    @if($options['allowCreate'])
    <div class="{{ $viewClass['form-group'] }} has-many-footer">
        <label class="{{ $viewClass['label'] }}"></label>
        <div class="{{ $viewClass['field'] }}">
            <button type="button" class="add inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                <i class="icon-plus"></i> {{ trans('admin.new') }}
            </button>
        </div>
    </div>
    @endif

</div>

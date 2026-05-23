@if(!empty($inline))
<div class="col-auto">
@else
@if (!empty($showAsSection))
    <div class="mb-2">
        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">{{ $label }}</h4>
    </div>
    <hr class="border-gray-200 mb-4">
@endif

<div class="{{ $viewClass['form-group'] }} {!! $errors->has($errorKey) ? 'has-error' : '' !!}">
    <label for="{{ $id }}" class="{{ $viewClass['label'] }} text-sm font-medium text-gray-700 pt-2.5">
        @if (empty($showAsSection)){{ $label }}@endif
    </label>
    <div class="{{ $viewClass['field'] }}">
        @include('admin::form.error')
@endif

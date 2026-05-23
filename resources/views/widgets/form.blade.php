<form {!! $attributes !!}>
    <div class="p-4 fields-group space-y-1">
        @foreach($fields as $field)
            {!! $field->render() !!}
        @endforeach
    </div>

    @if ($method != 'GET')
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
    @endif

    @if(count($buttons) > 0)
    <div class="px-4 py-3 border-t border-gray-100 flex justify-end gap-2">
        @if(in_array('reset', $buttons))
            <button type="reset" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">{{ trans('admin.reset') }}</button>
        @endif
        @if(in_array('submit', $buttons))
            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">{{ trans('admin.submit') }}</button>
        @endif
    </div>
    @endif
</form>

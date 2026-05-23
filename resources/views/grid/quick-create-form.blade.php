<thead>
<tr class="quick-create">
    <td colspan="{{ $columnCount }}" class="px-4 py-3 bg-gray-50 border-b border-gray-200">

        <span class="create flex items-center gap-1.5 text-sm text-gray-400 cursor-pointer hover:text-gray-600">
             <i class="icon-plus text-xs"></i>&nbsp;{{ __('admin.quick_create') }}
        </span>

        <form class="flex flex-wrap items-center gap-3 create-form" autocomplete="off" style="display: none;" method="post" action='{{ $url }}'>
            @foreach($fields as $field)
                {!! $field->render() !!}
            @endforeach

            <div class="flex items-center gap-2">
                <button class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">{{ __('admin.submit') }}</button>
                <a href="javascript:void(0);" class="cancel text-sm text-gray-500 hover:text-gray-700">{{ __('admin.cancel') }}</a>
            </div>
            {{ csrf_field() }}

        </form>
    </td>
</tr>
</thead>

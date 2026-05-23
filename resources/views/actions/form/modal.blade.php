@php $sizeClass = $modal_size === 'modal-lg' ? 'max-w-2xl' : ($modal_size === 'modal-sm' ? 'max-w-sm' : 'max-w-lg'); @endphp
<div class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
     id="{{ $modal_id }}" tabindex="-1" aria-hidden="true">
    <div class="relative w-full {{ $sizeClass }} bg-white rounded-xl shadow-xl">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h4 class="text-base font-semibold text-gray-900">{{ $title }}</h4>
            <button type="button" data-modal-close="{{ $modal_id }}" class="p-1 text-gray-400 hover:text-gray-600 rounded" aria-label="Close">
                <i class="icon-times text-sm"></i>
            </button>
        </div>
        <form class="form form-horizontal" method="{{$method}}" action="{{$url}}" autocomplete="off" @if(!empty($multipart))enctype="multipart/form-data"@endif>
            <input type="hidden" name="_action" value="{{$_action}}">
            <input type="hidden" name="_model" value="{{$_model}}">
            <input type="hidden" name="_key" value="{{$_key}}">
            <div class="modal-body p-5">
                {!! $field_html !!}
            </div>
            <div class="modal-footer flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100">
                <button type="button" data-modal-close="{{ $modal_id }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">{{ __('admin.close') }}</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">{{ __('admin.submit') }}</button>
            </div>
        </form>
    </div>
</div>

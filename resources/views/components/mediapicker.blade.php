
<template render="true">
    <div class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center p-4 picker" id="{{ $modal }}" tabindex="-1" aria-hidden="true">
        <div class="relative w-full max-w-3xl bg-white rounded-xl shadow-xl max-h-full flex flex-col">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h4 class="text-base font-semibold text-gray-900">{{ admin_trans('admin.choose') }}</h4>
                <button type="button" data-modal-close="{{ $modal }}" class="p-1 text-gray-400 hover:text-gray-600 rounded" aria-label="Close">
                    <i class="icon-times text-sm"></i>
                </button>
            </div>
            <div class="modal-body overflow-y-auto p-0 flex-1">
                <div class="loading flex items-center justify-center py-16">
                    <i class="icon-spinner icon-pulse text-3xl text-gray-400"></i>
                </div>
            </div>
            <div class="modal-footer hidden flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100">
                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 btn-cancel" data-modal-close="{{ $modal }}">{{ admin_trans('admin.cancel') }}</button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 submit">{{ admin_trans('admin.submit') }}</button>
            </div>
        </div>
    </div>
</template>

<script>

    window.setFile{{$selector}} = function (url,fileName){
        FileUpload_{{$name}}.addFileFromUrl(url);

        @if (empty($multiple))
            admin.selectable.hideModal();
        @else
            admin.toastr.info("File added");
        @endif
    }

    var url = "/admin/media?select=true&fn=setFile{{$selector}}{!!$picker_path!!}";
    var config = {
        url : url,
        modal_elm : document.querySelector('#{{$modal}}'),
    }
    admin.selectable.init(config);

</script>

<style>
    #{{$modal}} .card-header.navbar{
        display:none;
    }
</style>


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
            <div class="modal-footer flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100">
                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 btn-cancel" data-modal-close="{{ $modal }}">{{ admin_trans('admin.cancel') }}</button>
                <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 submit">{{ admin_trans('admin.submit') }}</button>
            </div>
        </div>
    </div>
</template>

<script>

var pickInput = document.querySelector("{{ $selector }}");
var separator = '{{ $separator }}';
var value;
var refresh = function () {};

@if($multiple)

    var getValue = function () {
        let value = (new String(pickInput.value)).split(separator).filter(function (val) {
            return val != '';
        });
        return value;

    };
    var setValue = function (values,rows) {
        pickInput.value = values.join(separator);
    };

@else

    var getValue = function () {
        value = pickInput.value;
    };
    var setValue = function (values,rows) {
        console.log(values);
        pickInput.value = values[0];
    };

@endif

var config = {
    modal_elm : document.querySelector('#{{ $modal }}'),
    url : "{!! $url !!}",
    update : setValue,
    value : getValue
}

admin.selectable.init(config);

getValue();

@if($is_file)
refresh = function () {

    var values = (typeof value == 'string') ? [value] : value;
    var previewEl = pickInput.parentElement.parentElement.querySelector('.picker-file-preview');
    var url_tpl = '{{ $url_tpl }}';

    @if($is_image)
    var templateEl = document.querySelector('template#image-preview');
    @else
    var templateEl = document.querySelector('template#file-preview');
    @endif

    if (previewEl && templateEl) {
        previewEl.innerHTML = '';
        values.forEach(function (item) {
            var url = url_tpl.replace('__URL__', item);
            var clone = templateEl.cloneNode(true);
            var html = clone.innerHTML
                .replace(/_url_/g, url)
                .replace(/_val_/g, item)
                .replace(/_name_/g, url.split('/').pop());
            var div = document.createElement('div');
            div.innerHTML = html;
            previewEl.appendChild(div.firstChild);
        });
        if (values.length > 0) {
            previewEl.classList.remove('hidden');
        }
    }
};
@endif

</script>

<style>
    .picker tr {
        cursor: pointer;
    }

    .picker .box {
        border-top: none;
        margin-bottom: 0;
        box-shadow: none;
    }
</style>

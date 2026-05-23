<span class="grid-selector" id="{{ $display_field }}-{{$key}}" key="{{ $key }}" data-display_field="{{ $display_field }}" data-val="{{ $original }}">
   <a href="#" class="text-gray-500 hover:text-blue-600">
       <i class="icon-check-square"></i>&nbsp;
       <span class="text">{!! $value !!}</span>
   </a>
</span>

<style>
    .belongsto tr {
        cursor: pointer;
    }
    .belongsto .box {
        border-top: none;
        margin-bottom: 0;
        box-shadow: none;
    }
    .belongsto .loading {
        margin: 50px;
    }
</style>

<template render="true">
    <div class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center p-4 belongsto" id="{{ $modal }}" tabindex="-1" aria-hidden="true">
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

    var related;
    var rows;
    var values;
    var labelClass = "{{ $labelClass }}";
    var seperator = "{{ $seperator }}";

    var update = function (callback) {
        var url = "{{ $resource }}/" + related.getAttribute('key');
        @if($relation == \OpenAdmin\Admin\Grid\Displayers\BelongsTo::class)
            var value = values.length ? values[0] : '';
        @else
            var value = values.length ? values : ['']
        @endif
        var data = {
                '{{ $name }}': value,
                _method: 'PUT',
                'after-save': 'exit'
            };
        admin.ajax.post(url,data,callback);
    };

    var updateFunction = function(setValues,setRows,setRelated){

        rows = setRows;
        related = setRelated;
        values = setValues;
        update(resultFunction);
    }

    var resultFunction = function(data){

        admin.toastr.success(data.data);

        var text = related.querySelector(".text");
        var labels = [];
        var sep = "";

        for(i in values){
            var value = values[i];
            if (!text.querySelector('span[data-key="'+value+'"]')){
                var row = rows[value];
                var key = row.dataset.key;
                var value = row.querySelector(".column-"+related.dataset.display_field).innerText;
                var label = sep+"<span data-key=\""+key+"\" class=\""+labelClass+"\">"+value+"</span>";
                text.innerHTML += label;
            }
            sep = seperator;
        }
        text.querySelectorAll("span").forEach(span=>{
            var check = (new String(span.dataset.key));
            if (!arr_includes(values,check)){
                span.remove();
            }
        })

        @if($relation == \OpenAdmin\Admin\Grid\Displayers\BelongsTo::class)
            related.dataset.val = values[0];
        @else
            related.dataset.val = JSON.stringify(values);
        @endif

        text.classList.add("text-green-600");

        setTimeout(function () {
            var text = related.querySelector(".text");
            text.classList.remove("text-green-600");
        }, 2000);
    }

    var valueFunction = function(related){
        @if($relation == \OpenAdmin\Admin\Grid\Displayers\BelongsTo::class)
        return related.dataset.val;
        @else
        return JSON.parse(related.dataset.val);
        @endif
    }

    var config = {
        modal_elm : document.querySelector('#{{$modal}}'),
        url : "{!! $url !!}",
        trigger: '#{{ $display_field }}-{{$key}}',
        update : updateFunction,
        value : valueFunction
    }

    admin.selectable.init(config);

</script>

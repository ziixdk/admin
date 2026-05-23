@include("admin::form._header")

    <div class="admin-input-group">
        <button type="button" id="{{ $id }}-button-min"
            class="inline-flex items-center px-3 text-sm text-gray-600 bg-gray-50 border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 minus">
            <i class="icon-minus"></i>
        </button>
        <input {!! $attributes !!} />
        <button type="button" id="{{ $id }}-button-plus"
            class="inline-flex items-center px-3 text-sm text-gray-600 bg-gray-50 border border-s-0 border-gray-300 rounded-e-lg hover:bg-gray-100 plus">
            <i class="icon-plus"></i>
        </button>
    </div>

@include("admin::form._footer")

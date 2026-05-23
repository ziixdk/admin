@include("admin::form._header")

        <div class="admin-input-group">

            @if ($prepend)
            <span class="inline-flex items-center px-3 text-sm text-gray-600 bg-gray-50 border border-gray-300 rounded-s-lg">{!! $prepend !!}</span>
            @endif

            <input {!! $attributes !!} />

            @if ($append)
                <span class="inline-flex items-center px-3 text-sm text-gray-600 bg-gray-50 border border-gray-300 rounded-e-lg">{!! $append !!}</span>
            @endif

            @isset($btn)
                <div class="shrink-0">{!! $btn !!}</div>
            @endisset

        </div>

@include("admin::form._footer")

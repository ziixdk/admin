@include("admin::form._header")

        <div class="admin-input-group" style="max-width: 250px;">
            <input {!! $attributes !!} />
            <span class="inline-flex items-center px-2 bg-gray-50 border border-gray-300 border-s-0 rounded-e-lg" style="padding: 1px;">
                <img id="{{$column}}-captcha" src="{{ captcha_src() }}" style="height:30px;cursor: pointer;" title="Click to refresh"/>
            </span>
        </div>

@include("admin::form._footer")

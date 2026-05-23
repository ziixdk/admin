<div class="{{$viewClass['form-group']}} {!! ($errors->has($errorKey['start'].'start') || $errors->has($errorKey['end'].'end')) ? 'has-error' : ''  !!}">

    <label for="{{$id['start']}}" class="{{$viewClass['label']}} text-sm font-medium text-gray-700 pt-2.5">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <div class="grid grid-cols-2 gap-3">
            <div>
                <div class="admin-input-group">
                    <span class="inline-flex items-center px-3 bg-gray-50 border border-gray-300 border-e-0 rounded-s-lg text-gray-500"><i class="icon-calendar text-sm"></i></span>
                    <input type="text" name="{{$name['start']}}" id="{{$id['start']}}" value="{{ old($column['start'], $value['start'] ?? null) }}" class="flatpickr-input {{$class['start']}}" autocomplete="off" {!! $attributes !!} />
                </div>
            </div>

            <div>
                <div class="admin-input-group">
                    <span class="inline-flex items-center px-3 bg-gray-50 border border-gray-300 border-e-0 rounded-s-lg text-gray-500"><i class="icon-calendar text-sm"></i></span>
                    <input type="text" name="{{$name['end']}}" id="{{$id['end']}}" value="{{ old($column['end'], $value['end'] ?? null) }}" class="flatpickr-input {{$class['end']}}" autocomplete="off" {!! $attributes !!} />
                </div>
            </div>
        </div>

@include("admin::form._footer")

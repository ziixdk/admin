<div class="{{$viewClass['form-group']}} {!! !$errors->has($errorKey) ? '' : 'has-error' !!}">

    <label for="{{$id}}" class="{{$viewClass['label']}} form-label">{{$label}}</label>

    <div class="{{$viewClass['field']}} picker-{{ $column }}">

        @include('admin::form.error')

        <div class="picker-file-preview flex flex-wrap gap-2 mb-2 {{ empty($preview) ? 'hidden' : '' }}">
            @foreach($preview as $item)
            <div class="file-preview-frame relative w-36 border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm" data-val="{!! $item['value'] !!}">
                <div class="file-content flex items-center justify-center h-28 bg-gray-50">
                    @if($item['is_file'])
                        <i class="icon-file-text-o text-4xl text-gray-400"></i>
                    @else
                        <img src="{{ $item['url'] }}" class="max-w-full max-h-full object-contain"/>
                    @endif
                </div>
                <div class="file-caption-info px-2 py-1 text-xs text-gray-500 truncate border-t border-gray-100">{{ basename($item['url']) }}</div>
                <div class="file-actions flex justify-end gap-1 px-2 py-1 border-t border-gray-100">
                    <a class="p-1 text-gray-400 hover:text-red-500 rounded remove cursor-pointer">
                        <i class="icon-trash text-xs"></i>
                    </a>
                    <a class="p-1 text-gray-400 hover:text-blue-500 rounded" target="_blank" download="{{ basename($item['url']) }}" href="{{ $item['url'] }}">
                        <i class="icon-download text-xs"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="admin-input-group">
            <input {!! $attributes !!} />
            {!! $btn !!}
        </div>
@include("admin::form._footer")

<template>
    <template id="file-preview">
        <div class="file-preview-frame relative w-36 border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm" data-val="_val_">
            <div class="file-content flex items-center justify-center h-28 bg-gray-50">
                <i class="icon-file-text-o text-4xl text-gray-400"></i>
            </div>
            <div class="file-caption-info px-2 py-1 text-xs text-gray-500 truncate border-t border-gray-100">_name_</div>
            <div class="file-actions flex justify-end gap-1 px-2 py-1 border-t border-gray-100">
                <a class="p-1 text-gray-400 hover:text-red-500 rounded remove cursor-pointer">
                    <i class="icon-trash text-xs"></i>
                </a>
                <a class="p-1 text-gray-400 hover:text-blue-500 rounded" target="_blank" download="_name_" href="_url_">
                    <i class="icon-download text-xs"></i>
                </a>
            </div>
        </div>
    </template>
    <template id="image-preview">
        <div class="file-preview-frame relative w-36 border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm" data-val="_val_">
            <div class="file-content flex items-center justify-center h-28 bg-gray-50">
                <img src="_url_" class="max-w-full max-h-full object-contain">
            </div>
            <div class="file-caption-info px-2 py-1 text-xs text-gray-500 truncate border-t border-gray-100">_name_</div>
            <div class="file-actions flex justify-end gap-1 px-2 py-1 border-t border-gray-100">
                <a class="p-1 text-gray-400 hover:text-red-500 rounded remove cursor-pointer">
                    <i class="icon-trash text-xs"></i>
                </a>
                <a class="p-1 text-gray-400 hover:text-blue-500 rounded" target="_blank" download="_name_" href="_url_">
                    <i class="icon-download text-xs"></i>
                </a>
            </div>
        </div>
    </template>
</template>

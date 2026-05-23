<div class="grid grid-cols-12 gap-x-4 mb-4 items-start">
    <label class="col-span-2 text-sm font-medium text-gray-700 pt-2.5">{{ $label }}</label>
    <div class="col-span-10">
        @include($presenter->view())
    </div>
</div>

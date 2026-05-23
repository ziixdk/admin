@if($errors->hasBag('exception') && config('app.debug') == true)
    <?php $error = $errors->getBag('exception');?>
    <div x-data="{ show: true, trace: false }" x-show="show" class="flex gap-3 bg-yellow-50 border border-yellow-200 text-yellow-900 rounded-lg p-4 mb-4">
        <i class="icon icon-exclamation-triangle mt-0.5 text-yellow-600"></i>
        <div class="flex-1 text-sm">
            <h4 class="font-semibold mb-1">
                <span style="border-bottom: 1px dotted currentColor; cursor: pointer;" title="{{ $error->first('type') }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ class_basename($error->first('type')) }}</span>
                &mdash;
                <span style="border-bottom: 1px dotted currentColor; cursor: pointer;" title="{{ $error->first('file') }} line {{ $error->first('line') }}" ondblclick="var f=this.innerHTML;this.innerHTML=this.title;this.title=f;">{{ basename($error->first('file')) }} line {{ $error->first('line') }}</span>
            </h4>
            <p>
                <a href="#" @click.prevent="trace = !trace" class="inline-flex items-center gap-1 text-yellow-800 hover:text-yellow-900">
                    <i class="icon-angle-double-down text-xs" :class="trace ? 'icon-angle-double-up' : 'icon-angle-double-down'"></i>
                    {!! $error->first('message') !!}
                </a>
            </p>
            <p x-show="trace" class="mt-2 text-xs font-mono whitespace-pre-wrap">{!! nl2br($error->first('trace')) !!}</p>
        </div>
        <button type="button" @click="show = false" class="text-yellow-600 hover:text-yellow-800">
            <i class="icon-times text-xs"></i>
        </button>
    </div>
@endif

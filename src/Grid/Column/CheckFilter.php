<?php

namespace ZiiX\Admin\Grid\Column;

use ZiiX\Admin\Admin;
use ZiiX\Admin\Grid\Model;

class CheckFilter extends Filter
{
    /**
     * @var array
     */
    protected $options;

    /**
     * CheckFilter constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;

        $this->class = [
            'all'  => uniqid('column-filter-all-'),
            'item' => uniqid('column-filter-item-'),
        ];
    }

    /**
     * Add a binding to the query.
     *
     * @param array $value
     * @param Model $model
     */
    public function addBinding($value, Model $model)
    {
        if (empty($value)) {
            return;
        }

        $model->whereIn($this->getColumnName(), $value);
    }

    /**
     * Add script to page.
     *
     * @return void
     */
    protected function addScript()
    {
        $script = <<<SCRIPT

document.querySelector('.{$this->class['all']}').addEventListener("change",function(e) {
    var setTo = (this.checked) ? true : false;
    document.querySelectorAll('.{$this->class['item']}').forEach(el=>{
        el.checked = setTo;
    })
    return false;
});


SCRIPT;

        Admin::script($script);
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $value = $this->getFilterValue([]);

        $lists = collect($this->options)->map(function ($label, $key) use ($value) {
            $checked = in_array($key, $value) ? 'checked' : '';

            return <<<HTML
<li class="" style="margin: 0;">
    <label style="width: 100%;padding: 3px;">
        <input type="checkbox" class="{$this->class['item']}" name="{$this->getColumnName()}[]" value="{$key}" {$checked}/>&nbsp;&nbsp;&nbsp;{$label}
    </label>
</li>
HTML;
        })->implode("\r\n");

        $this->addScript();

        $allCheck = (count($value) == count($this->options)) ? 'checked' : '';
        $activeClass = empty($value) ? 'text-gray-400 hover:text-gray-600' : 'text-yellow-500 hover:text-yellow-600';

        return <<<EOT
<span x-data="{ open: false }" class="relative inline-block">
<form action="{$this->getFormAction()}" pjax-container method="get" style="display: inline-block;">
    {$this->getPreservedHiddenInputs()}
    <a href="javascript:void(0);" class="column-filter-toggle {$activeClass}" @click.prevent="open = !open">
        <i class="icon-filter"></i>
    </a>
    <div x-show="open" @click.outside="open = false" x-transition
         class="absolute z-20 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-3 min-w-[180px]" style="left:-70px;">
        <label class="flex items-center gap-2 py-1 text-sm text-gray-700 cursor-pointer">
            <input type="checkbox" class="w-4 h-4 text-blue-600 rounded border-gray-300 {$this->class['all']}" {$allCheck}/>
            {$this->trans('all')}
        </label>
        <hr class="my-1 border-gray-200">
        {$lists}
        <hr class="my-2 border-gray-200">
        <div class="flex justify-between gap-2">
            <button class="flex-1 px-2 py-1.5 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                <i class="icon-search"></i> {$this->trans('search')}
            </button>
            <a href="{$this->getFormAction()}" class="px-2 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                <i class="icon-undo"></i>
            </a>
        </div>
    </div>
</form>
</span>
EOT;
    }
}

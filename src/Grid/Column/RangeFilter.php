<?php

namespace OpenAdmin\Admin\Grid\Column;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Model;

class RangeFilter extends Filter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * RangeFilter constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->class = [
            'start' => uniqid('column-filter-start-'),
            'end'   => uniqid('column-filter-end-'),
        ];
    }

    /**
     * Add a binding to the query.
     *
     * @param mixed $value
     * @param Model $model
     */
    public function addBinding($value, Model $model)
    {
        $value = array_filter((array) $value);

        if (empty($value)) {
            return;
        }

        if (!isset($value['start'])) {
            return $model->where($this->getColumnName(), '<', $value['end']);
        } elseif (!isset($value['end'])) {
            return $model->where($this->getColumnName(), '>', $value['start']);
        } else {
            return $model->whereBetween($this->getColumnName(), array_values($value));
        }
    }

    protected function addScript()
    {
        if ($this->type == 'time') {
            Admin::script("Inputmask({'mask':'99:99:99'}).mask(document.querySelectorAll('.{$this->class['start']},{$this->class['end']}'));");
        } else {
            $options = [
                'locale'           => config('app.locale'),
                'allowInputToggle' => true,
                'allowInput'       => true,
            ];

            if ($this->type == 'date') {
                $options['format'] = 'YYYY-MM-DD';
            } elseif ($this->type == 'datetime') {
                $options['format'] = 'YYYY-MM-DD HH:mm:ss';
                $options['enableSeconds'] = true;
                $options['enableTime'] = true;
            } else {
                return;
            }

            $options = json_encode($options);
            Admin::script("flatpickr('.{$this->class['start']}',{$options});flatpickr('.{$this->class['end']}',{$options});");
        }
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $script = <<<'SCRIPT'

document.querySelectorAll('.dropdown-menu input').forEach(el =>{
    el.addEventListener("click",function(e) {
        e.stopPropagation();
    })
});
SCRIPT;

        Admin::script($script);

        $this->addScript();

        $value = array_merge(['start' => '', 'end' => ''], $this->getFilterValue([]));
        $activeClass = empty(array_filter($value)) ? 'text-gray-400 hover:text-gray-600' : 'text-yellow-500 hover:text-yellow-600';

        return <<<EOT
<span x-data="{ open: false }" class="relative inline-block">
<form action="{$this->getFormAction()}" pjax-container method="get" style="display: inline-block;">
    <a href="javascript:void(0);" class="column-filter-toggle {$activeClass}" @click.prevent="open = !open">
        <i class="icon-filter"></i>
    </a>
    <div x-show="open" @click.outside="open = false" x-transition
         class="absolute z-20 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-3 min-w-[200px]" style="left:-70px;">
        <input type="text" class="block w-full text-sm bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2 mb-2 {$this->class['start']}" name="{$this->getColumnName()}[start]" value="{$value['start']}" autocomplete="off"/>
        <input type="text" class="block w-full text-sm bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2 mb-2 {$this->class['end']}" name="{$this->getColumnName()}[end]" value="{$value['end']}" autocomplete="off"/>
        <hr class="my-2 border-gray-200">
        <div class="flex justify-between gap-2">
            <button class="column-filter-submit flex-1 px-2 py-1.5 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700">
                <i class="icon-search"></i> {$this->trans('search')}
            </button>
            <a href="{$this->getFormAction()}" class="column-filter-all px-2 py-1.5 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                <i class="icon-undo"></i>
            </a>
        </div>
    </div>
    </form>
</span>
EOT;
    }
}

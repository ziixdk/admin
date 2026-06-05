<?php

namespace ZiiX\Admin\Grid\Column;

use ZiiX\Admin\Admin;
use ZiiX\Admin\Grid\Model;

class InputFilter extends Filter
{
    /**
     * @var string
     */
    protected $type;

    /**
     * InputFilter constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
        $this->class = uniqid('column-filter-');
        $this->addition_classes = '';
    }

    /**
     * Add a binding to the query.
     *
     * @param string     $value
     * @param Model|null $model
     */
    public function addBinding($value, Model $model)
    {
        if (empty($value)) {
            return;
        }

        if ($this->type == 'like') {
            $model->where($this->getColumnName(), 'like', "%{$value}%");

            return;
        }

        if (in_array($this->type, ['date', 'time'])) {
            $method = 'where'.ucfirst($this->type);
            $model->{$method}($this->getColumnName(), $value);

            return;
        }

        $model->where($this->getColumnName(), $value);
    }

    /**
     * Add script to page.
     *
     * @return void
     */
    protected function addDateTimeScript()
    {
        $options = [
            'locale'           => config('app.locale'),
            'inline'           => true,
            'allowInputToggle' => true,
            'allowInput'       => true,
            'time_24hr'        => true,
        ];

        if ($this->type == 'date') {
            $options['format'] = 'YYYY-MM-DD';
        } elseif ($this->type == 'datetime') {
            $options['format'] = 'YYYY-MM-DD HH:mm:ss';
            $options['enableSeconds'] = true;
            $options['enableTime'] = true;
        } elseif ($this->type == 'time') {
            $options['format'] = 'HH:mm:ss';
            $options['enableSeconds'] = true;
            $options['enableTime'] = true;
            $options['noCalendar'] = true;
        } else {
            return;
        }

        $options = json_encode($options);

        Admin::script("flatpickr('.{$this->class}',{$options});");
    }

    /**
     * Render this filter.
     *
     * @return string
     */
    public function render()
    {
        $script = <<<'SCRIPT'
document.querySelectorAll('.dropdown-menu input, .flatpickr-month').forEach(el =>{
    el.addEventListener("click",function(e) {
        e.stopPropagation();
    })
});
SCRIPT;
        Admin::script($script);

        if ($this->type == 'date' || $this->type == 'datetime' || $this->type == 'time') {
            $this->addDateTimeScript();
            $this->addition_classes .= 'd-none';
        }

        $value = $this->getFilterValue();
        $activeClass = empty($value) ? 'text-gray-400 hover:text-gray-600' : 'text-yellow-500 hover:text-yellow-600';

        return <<<EOT
<span x-data="{ open: false }" class="relative inline-block">
    <form action="{$this->getFormAction()}" pjax-container="true" method="get" style="display: inline-block;">
    {$this->getPreservedHiddenInputs()}
    <a href="javascript:void(0);" class="column-filter-toggle {$activeClass}" @click.prevent="open = !open">
        <i class="icon-filter"></i>
    </a>
    <div x-show="open" @click.outside="open = false" x-transition
         class="absolute z-20 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg p-3 min-w-[180px]" style="left:-70px;">
        <input type="text" name="{$this->getColumnName()}" value="{$this->getFilterValue()}"
               class="block w-full text-sm bg-gray-50 border border-gray-300 text-gray-900 rounded-lg p-2 mb-2 {$this->class} {$this->addition_classes}" autocomplete="off"/>
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

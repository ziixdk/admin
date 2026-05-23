<?php

namespace ZiiX\Admin\Form\Field;

use Illuminate\Support\Arr;
use ZiiX\Admin\Admin;
use ZiiX\Admin\Form\Field;

class ValuePicker
{
    /**
     * @var string
     */
    protected $modal;

    /**
     * @var Text|File
     */
    protected $field;

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $selecteable;

    /**
     * @var string
     */
    protected $separator;

    /**
     * @var bool
     */
    protected $multiple = false;

    /**
     * ValuePicker constructor.
     *
     * @param string $selecteable
     * @param string $column
     * @param bool   $multiple
     * @param string $separator
     */
    public function __construct($selecteable, $column = '', $multiple = false, $separator = ',')
    {
        $this->selecteable = $selecteable;
        $this->column = $column;
        $this->multiple = $multiple;
        $this->separator = $separator;
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $selectable = str_replace('\\', '_', $this->selecteable);

        $args = [$this->multiple, $this->column];

        return route('admin.handle-selectable', compact('selectable', 'args'));
    }

    /**
     * @param Field         $field
     * @param \Closure|null $callback
     */
    public function mount(Field $field, ?\Closure $callback = null)
    {
        $this->field = $field;
        $this->modal = sprintf('picker-modal-%s', $field->getElementClassString());

        $this->addPickBtn($callback);

        Admin::component('admin::components.valuepicker', [
            'url'       => $this->getLoadUrl(),
            'modal'     => $this->modal,
            'selector'  => $this->field->getElementClassSelector(),
            'separator' => $this->separator,
            'multiple'  => $this->multiple,
            'is_file'   => $this->field instanceof File,
            'is_image'  => $this->field instanceof Image,
            'url_tpl'   => $this->field instanceof File ? $this->field->objectUrl('__URL__') : '',
        ]);
    }

    /**
     * @param \Closure|null $callback
     */
    protected function addPickBtn(?\Closure $callback = null)
    {
        $text = admin_trans('admin.choose');

        $btn = <<<HTML
<button type="button" data-modal-target="{$this->modal}" data-modal-toggle="{$this->modal}"
    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-300 focus:outline-none">
    <i class="icon-folder-open"></i> {$text}
</button>
HTML;

        if ($callback) {
            $callback($btn);
        } else {
            $this->field->addVariables(compact('btn'));
        }
    }

    /**
     * @param string $field
     *
     * @return array|\Illuminate\Support\Collection
     */
    public function getPreview(string $field)
    {
        if (empty($value = $this->field->value())) {
            return [];
        }

        if ($this->multiple) {
            $value = explode($this->separator, $value);
        }

        return collect(Arr::wrap($value))->map(function ($item) use ($field) {
            return [
                'url'     => $this->field->objectUrl($item),
                'value'   => $item,
                'is_file' => $field == File::class,
            ];
        });
    }
}

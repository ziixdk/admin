<?php

namespace ZiiX\Admin\Form\Field\Traits;

use ZiiX\Admin\Admin;
use ZiiX\Admin\Form\Field;

/**
 * @mixin Field
 */
trait HasMediaPicker
{
    public $picker = false;
    public $picker_path = '';

    /**
     * @param string $picker
     * @param string $column
     *
     * @return $this
     */
    public function pick($path = '')
    {
        if ($path != '') {
            $this->picker_path = '&path='.$path;
        }
        $this->picker = 'one';
        $this->retainable(true);

        return $this;
    }

    /**
     * @param \Closure|null $callback
     */
    protected function addPickBtn(\Closure $callback = null)
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
            $this->addVariables(compact('btn'));
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    protected function renderMediaPicker()
    {
        if (!class_exists("ZiiX\Admin\Media\MediaManager")) {
            throw new \Exception(
                '[Media Manager extension not installed yet.<br> Install using: <b>composer require ziix-admin-ext/media-manager</b><br><br>'
            );
        }

        $this->modal = sprintf('media-picker-modal-%s', $this->getElementClassString());
        $this->addVariables([
            'modal'       => $this->modal,
            'selector'    => $this->getElementClassString(),
            'name'        => $this->formatName($this->column),
            'multiple'    => !empty($this->multiple),
            'picker_path' => $this->picker_path,
            'trans'       => [
                'choose' => admin_trans('admin.choose'),
                'cancal' => admin_trans('admin.cancel'),
                'submit' => admin_trans('admin.submit'),
            ],
        ]);

        $this->addPickBtn();

        return Admin::component('admin::components.mediapicker', $this->variables());
    }
}

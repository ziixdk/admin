<?php

namespace ZiiX\Admin\Form\Field;

class Fieldset
{
    protected $name = '';

    public function __construct()
    {
        $this->name = uniqid('fieldset-');
    }

    public function start($title)
    {
        return <<<HTML
<div x-data="{ open: true }">
    <div class="fieldset mb-2">
        <button type="button" @click="open = !open" class="{$this->name}-title fieldset-link inline-flex items-center gap-2 text-sm font-medium text-gray-700 hover:text-gray-900">
            <i class="icon-angle-up transition-transform duration-200" :class="{ 'rotate-180': !open }"></i> {$title}
        </button>
    </div>
    <div x-show="open" x-collapse id="{$this->name}">
HTML;
    }

    public function end()
    {
        return '</div></div>';
    }
}

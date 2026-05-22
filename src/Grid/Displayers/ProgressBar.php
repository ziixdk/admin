<?php

namespace OpenAdmin\Admin\Grid\Displayers;

class ProgressBar extends AbstractDisplayer
{
    public function display($style = 'primary', $size = 'sm', $max = 100)
    {
        $style = collect((array) $style)->map(function ($style) {
            return 'progress-bar-'.$style;
        })->implode(' ');

        $this->value = (int) $this->value;

        return <<<EOT
<div class="flex items-center gap-2" style="min-width:120px;">
    <span class="text-xs text-gray-500 w-8 shrink-0">{$this->value}%</span>
    <div class="flex-1 bg-gray-200 rounded-full h-2">
        <div class="bg-blue-600 h-2 rounded-full" role="progressbar" aria-valuenow="{$this->value}" aria-valuemin="0" aria-valuemax="$max" style="width:{$this->value}%"></div>
    </div>
</div>
EOT;
    }
}

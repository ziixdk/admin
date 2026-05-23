<?php

namespace ZiiX\Admin\Grid\Displayers;

/**
 * Class QRCode.
 */
class QRCode extends AbstractDisplayer
{
    public function display($formatter = null, $width = 150, $height = 150)
    {
        $content = $this->getColumn()->getOriginal();

        if ($formatter instanceof \Closure) {
            $content = call_user_func($formatter, $content, $this->row);
        }

        $img = sprintf(
            "<img src='https://api.qrserver.com/v1/create-qr-code/?size=%sx%s&data=%s' style='height:%spx;width:%spx;'/>",
            $width,
            $height,
            $content,
            $height,
            $width
        );
        $value = $this->getValue();
        if (empty($value)) {
            return '';
        }

        return <<<HTML
<span x-data="{ open: false }" class="relative inline-block">
    <a href="javascript:void(0);" class="grid-column-qrcode text-gray-400 hover:text-gray-600" @click="open = !open" @click.outside="open = false">
        <i class="icon-qrcode"></i>
    </a>
    <div x-show="open" x-transition class="absolute z-10 p-2 bg-white border border-gray-200 rounded-lg shadow-lg" style="min-width:160px;">
        {$img}
    </div>
</span>&nbsp;{$value}
HTML;
    }
}

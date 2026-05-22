<?php

namespace OpenAdmin\Admin\Grid\Column;

use Illuminate\Contracts\Support\Renderable;

class Help implements Renderable
{
    /**
     * @var string
     */
    protected $message = '';

    /**
     * Help constructor.
     *
     * @param string $message
     */
    public function __construct($message = '')
    {
        $this->message = $message;
    }

    /**
     * Render help  header.
     *
     * @return string
     */
    public function render()
    {
        $id = 'tooltip-help-'.md5($this->message);
        $msg = e($this->message);

        return <<<HELP
<span class="relative inline-block group">
    <a href="javascript:void(0);" class="grid-column-help text-gray-400 hover:text-gray-600">
        <i class="icon-question-circle"></i>
    </a>
    <span class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-1 hidden group-hover:block z-20 whitespace-nowrap rounded bg-gray-800 px-2 py-1 text-xs text-white">{$msg}</span>
</span>
HELP;
    }
}

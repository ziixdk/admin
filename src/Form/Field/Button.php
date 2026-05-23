<?php

namespace OpenAdmin\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;

class Button extends Field
{
    protected $class = 'px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 cursor-pointer';

    public function info()
    {
        $this->class = 'px-4 py-2 text-sm font-medium text-white bg-cyan-600 rounded-lg hover:bg-cyan-700 cursor-pointer';

        return $this;
    }

    public function on($event, $callback)
    {
        $this->script = <<<JS
        document.querySelector('{$this->getElementClassSelector()}').addEventListener('$event', function() {
            $callback
        });
JS;
    }
}

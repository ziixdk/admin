<?php

namespace ZiiX\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;

class Display extends Field
{
    public function prepare($value)
    {
        return $this->original();
    }
}

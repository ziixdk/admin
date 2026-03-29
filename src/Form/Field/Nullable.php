<?php

namespace ZiiX\Admin\Form\Field;

use OpenAdmin\Admin\Form\Field;

class Nullable extends Field
{
    public function __construct()
    {
    }

    public function __call($method, $parameters)
    {
        return $this;
    }
}

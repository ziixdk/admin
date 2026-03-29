<?php

namespace ZiiX\Admin\Widgets\Navbar;

use Illuminate\Contracts\Support\Renderable;
use OpenAdmin\Admin\Admin;

class RefreshButton implements Renderable
{
    public function render()
    {
        return Admin::component('admin::components.refresh-btn');
    }
}

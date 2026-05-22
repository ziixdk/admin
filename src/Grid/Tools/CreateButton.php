<?php

namespace OpenAdmin\Admin\Grid\Tools;

use OpenAdmin\Admin\Grid;

class CreateButton extends AbstractTool
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * Create a new CreateButton instance.
     *
     * @param Grid $grid
     */
    public function __construct(Grid $grid)
    {
        $this->grid = $grid;
    }

    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        if (!$this->grid->showCreateBtn()) {
            return '';
        }

        $new = trans('admin.new');

        return <<<HTML
<a href="{$this->grid->getCreateUrl()}" title="{$new}" class="grid-create-btn inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-300 me-1">
    <i class="icon-plus"></i> <span>{$new}</span>
</a>
HTML;
    }
}

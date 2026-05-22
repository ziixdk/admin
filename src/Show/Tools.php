<?php

namespace OpenAdmin\Admin\Show;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Collection;

class Tools implements Renderable
{
    /**
     * The panel that holds this tool.
     *
     * @var Panel
     */
    protected $panel;

    /**
     * @var string
     */
    protected $resource;

    /**
     * Default tools.
     *
     * @var array
     */
    protected $tools = ['delete', 'edit', 'list'];

    /**
     * Tools should be appends to default tools.
     *
     * @var Collection
     */
    protected $appends;

    /**
     * Tools should be prepends to default tools.
     *
     * @var Collection
     */
    protected $prepends;

    /**
     * Tools constructor.
     *
     * @param Panel $panel
     */
    public function __construct(Panel $panel)
    {
        $this->panel = $panel;

        $this->appends = new Collection();
        $this->prepends = new Collection();
    }

    /**
     * Append a tools.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function append($tool)
    {
        $this->appends->push($tool);

        return $this;
    }

    /**
     * Prepend a tool.
     *
     * @param mixed $tool
     *
     * @return $this
     */
    public function prepend($tool)
    {
        $this->prepends->push($tool);

        return $this;
    }

    /**
     * Get resource path.
     *
     * @return string
     */
    public function getResource()
    {
        if (is_null($this->resource)) {
            $this->resource = $this->panel->getParent()->getResourcePath();
        }

        return $this->resource;
    }

    /**
     * Disable `list` tool.
     *
     * @return $this
     */
    public function disableList(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->tools, 'list');
        } elseif (!in_array('list', $this->tools)) {
            array_push($this->tools, 'list');
        }

        return $this;
    }

    /**
     * Disable `delete` tool.
     *
     * @return $this
     */
    public function disableDelete(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->tools, 'delete');
        } elseif (!in_array('delete', $this->tools)) {
            array_push($this->tools, 'delete');
        }

        return $this;
    }

    /**
     * Disable `edit` tool.
     *
     * @return $this
     */
    public function disableEdit(bool $disable = true)
    {
        if ($disable) {
            array_delete($this->tools, 'edit');
        } elseif (!in_array('edit', $this->tools)) {
            array_push($this->tools, 'edit');
        }

        return $this;
    }

    /**
     * Get request path for resource list.
     *
     * @return string
     */
    protected function getListPath()
    {
        return ltrim($this->getResource(), '/');
    }

    /**
     * Get request path for edit.
     *
     * @return string
     */
    protected function getEditPath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath().'/'.$key.'/edit';
    }

    /**
     * Get request path for delete.
     *
     * @return string
     */
    protected function getDeletePath()
    {
        $key = $this->panel->getParent()->getModel()->getKey();

        return $this->getListPath().'/'.$key;
    }

    /**
     * Render `list` tool.
     *
     * @return string
     */
    protected function renderList()
    {
        $list = trans('admin.list');

        return <<<HTML
<a href="{$this->getListPath()}" title="{$list}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 me-2">
    <i class="icon-list"></i> <span>{$list}</span>
</a>
HTML;
    }

    /**
     * Render `edit` tool.
     *
     * @return string
     */
    protected function renderEdit()
    {
        $edit = trans('admin.edit');

        return <<<HTML
<a href="{$this->getEditPath()}" title="{$edit}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 me-2">
    <i class="icon-edit"></i> <span>{$edit}</span>
</a>
HTML;
    }

    /**
     * Render `delete` tool.
     *
     * @return string
     */
    protected function renderDelete()
    {
        $trans = [
            'delete'         => trans('admin.delete'),
        ];

        return <<<HTML
<a onclick="admin.resource.delete(event,this)" data-url="{$this->getDeletePath()}" data-list_url="{$this->getListPath()}" title="{$trans['delete']}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 cursor-pointer me-2">
    <i class="icon-trash"></i> <span>{$trans['delete']}</span>
</a>
HTML;
    }

    /**
     * Render custom tools.
     *
     * @param Collection $tools
     *
     * @return mixed
     */
    protected function renderCustomTools($tools)
    {
        return $tools->map(function ($tool) {
            if ($tool instanceof Renderable) {
                return $tool->render();
            }

            if ($tool instanceof Htmlable) {
                return $tool->toHtml();
            }

            return (string) $tool;
        })->implode(' ');
    }

    /**
     * Render tools.
     *
     * @return string
     */
    public function render()
    {
        $output = $this->renderCustomTools($this->prepends);

        foreach ($this->tools as $tool) {
            $renderMethod = 'render'.ucfirst($tool);
            $output .= $this->$renderMethod();
        }

        return $output.$this->renderCustomTools($this->appends);
    }
}

<?php

namespace OpenAdmin\Admin\Grid\Displayers;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Grid\Column;

abstract class AbstractDisplayer
{
    /**
     * @var Grid
     */
    protected $grid;

    /**
     * @var Column
     */
    protected $column;

    /**
     * @var Model
     */
    public $row;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * Create a new displayer instance.
     *
     * @param mixed     $value
     * @param Grid      $grid
     * @param Column    $column
     * @param \stdClass $row
     */
    public function __construct($value, Grid $grid, Column $column, $row)
    {
        $this->value = $value;
        $this->grid = $grid;
        $this->column = $column;
        $this->row = $row;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return Grid
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @return Column
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * Get key of current row.
     *
     * @return mixed
     */
    public function getKey()
    {
        return $this->row->{$this->grid->getKeyName()};
    }

    /**
     * @param mixed $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        return $this->row->getAttribute($key);
    }

    /**
     * Get url path of current resource.
     *
     * @return string
     */
    public function getResource()
    {
        return $this->grid->resource();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getColumn()->getName();
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->getColumn()->getClassName();
    }

    /**
     * `foo.bar.baz` => `foo[bar][baz]`.
     *
     * @return string
     */
    protected function getPayloadName($name = '')
    {
        $keys = collect(explode('.', $name ?: $this->getName()));

        return $keys->shift().$keys->reduce(function ($carry, $val) {
            return $carry."[$val]";
        });
    }

    /**
     * Get translation.
     *
     * @param string $text
     *
     * @return string|\Symfony\Component\Translation\TranslatorInterface
     */
    protected function trans($text)
    {
        return trans("admin.$text");
    }

    /**
     * Map a Bootstrap color name to Tailwind badge utility classes.
     */
    public static function twBadgeClass(string $style): string
    {
        return match ($style) {
            'primary', 'blue'                    => 'bg-blue-100 text-blue-800',
            'success', 'green'                   => 'bg-green-100 text-green-800',
            'danger', 'red'                      => 'bg-red-100 text-red-800',
            'warning', 'yellow', 'orange'        => 'bg-yellow-100 text-yellow-800',
            'info', 'cyan'                       => 'bg-cyan-100 text-cyan-800',
            'purple', 'violet'                   => 'bg-purple-100 text-purple-800',
            'pink'                               => 'bg-pink-100 text-pink-800',
            'indigo'                             => 'bg-indigo-100 text-indigo-800',
            default                              => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Render a Tailwind badge span.
     */
    public static function twBadge(string $text, string $style): string
    {
        $cls = self::twBadgeClass($style);

        return "<span class=\"{$cls} text-xs font-medium px-2.5 py-0.5 rounded\">{$text}</span>";
    }

    /**
     * Display method.
     *
     * @return mixed
     */
    abstract public function display();
}

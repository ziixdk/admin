<?php

namespace ZiiX\Admin\Grid\Filter\Presenter;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use ZiiX\Admin\Facades\Admin;

class Select extends Presenter
{
    /**
     * Options of select.
     *
     * @var array
     */
    protected $options = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $script = '';

    /**
     * @var string
     */
    protected $additional_script = '';

    /**
     * @var string|null
     */
    protected $ajaxUrl = null;

    /**
     * Select constructor.
     *
     * @param mixed $options
     */
    public function __construct($options)
    {
        $this->options = $options;
    }

    /**
     * Set config for select.
     *
     * all configurations see https://tom-select.js.org/docs/
     *
     * @param string $key
     * @param mixed  $val
     *
     * @return $this
     */
    public function config($key, $val)
    {
        $this->config[$key] = $val;

        return $this;
    }

    /**
     * Returns JS variable name for Tom Select instance.
     */
    public function tomSelectObjName($field = false)
    {
        if (empty($field)) {
            $field = $this->getElementClass();
        }

        return 'ts_'.$field;
    }

    /** @deprecated Use tomSelectObjName() */
    public function choicesObjName($field = false)
    {
        return $this->tomSelectObjName($field);
    }

    /**
     * Build options.
     *
     * @return array
     */
    protected function buildOptions(): array
    {
        if (is_string($this->options)) {
            $this->loadRemoteOptions($this->options);
        }

        if ($this->options instanceof \Closure) {
            $this->options = $this->options->call($this->filter, $this->filter->getValue());
        }

        if ($this->options instanceof Arrayable) {
            $this->options = $this->options->toArray();
        }

        $configs = array_merge([
            'allowEmptyOption' => true,
        ], $this->config);
        $configs_json = json_encode($configs);

        if ($this->ajaxUrl) {
            $valueField = $configs['valueField'] ?? 'id';
            $labelField = $configs['labelField'] ?? 'text';
            $searchField = $configs['searchField'] ?? 'text';
            $script = <<<JS
var {$this->tomSelectObjName()} = new TomSelect('.{$this->getElementClass()}', Object.assign({$configs_json}, {
    valueField: '{$valueField}',
    labelField: '{$labelField}',
    searchField: '{$searchField}',
    load: function(query, callback) {
        admin.ajax.post('{$this->ajaxUrl}', {query: query}, function(data) { callback(data.data); });
    },
    onItemAdd: function() { this.setTextboxValue(''); this.refreshOptions(); }
}));
JS;
        } else {
            $script = "var {$this->tomSelectObjName()} = new TomSelect('.{$this->getElementClass()}',{$configs_json});";
        }
        Admin::script($script.$this->additional_script);

        return is_array($this->options) ? $this->options : [];
    }

    /**
     * Load options from current selected resource(s).
     *
     * @param string $model
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function model($model, $idField = 'id', $textField = 'name')
    {
        if (!class_exists($model)
            || !in_array(Model::class, class_parents($model))
        ) {
            throw new \InvalidArgumentException("[$model] must be a valid model class");
        }

        $this->options = function ($value) use ($model, $idField, $textField) {
            if (empty($value)) {
                return [];
            }

            $resources = [];

            if (is_array($value)) {
                if (Arr::isAssoc($value)) {
                    $resources[] = Arr::get($value, $idField);
                } else {
                    $resources = array_column($value, $idField);
                }
            } else {
                $resources[] = $value;
            }

            return $model::find($resources)->pluck($textField, $idField)->toArray();
        };

        return $this;
    }

    /**
     * Load options from remote.
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $options
     *
     * @return $this
     */
    protected function loadRemoteOptions($url, $parameters = [], $options = [])
    {
        $parameters_json = json_encode($parameters);

        $this->additional_script .= <<<JS
        admin.ajax.post("{$url}",{$parameters_json},function(data){
            var elm = document.querySelector(".{$this->getElementClass()}");
            if (elm && elm.tomselect) {
                elm.tomselect.clearOptions();
                elm.tomselect.addOptions(data.data);
                elm.tomselect.refreshOptions(false);
            }
        });
JS;

        return $this;
    }

    /**
     * Load options from ajax.
     *
     * @param string $resourceUrl
     * @param $idField
     * @param $textField
     */
    public function ajax($url, $idField = 'id', $textField = 'text')
    {
        $this->config = array_merge([
            'valueField'  => $idField,
            'labelField'  => $textField,
            'searchField' => $textField,
        ], $this->config);

        $this->ajaxUrl = $url;

        return $this;
    }

    /**
     * @return array
     */
    public function variables(): array
    {
        return [
            'options' => $this->buildOptions(),
            'class'   => $this->getElementClass(),
        ];
    }

    /**
     * @return string
     */
    protected function getElementClass(): string
    {
        return str_replace('.', '_', $this->filter->getColumn());
    }

    /**
     * Get form element class.
     *
     * @param string $target
     *
     * @return mixed
     */
    protected function getClass($target): string
    {
        return str_replace('.', '_', $target);
    }
}

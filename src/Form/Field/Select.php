<?php

namespace ZiiX\Admin\Form\Field;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ZiiX\Admin\Form\Field;
use ZiiX\Admin\Form\Field\Traits\CanCascadeFields;

class Select extends Field
{
    use CanCascadeFields;

    /**
     * @var array
     */
    protected $groups = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $cascadeEvent = 'change';

    /**
     * @var bool
     */
    protected $native = false;

    public $additional_script = '';

    /**
     * Set options.
     *
     * @param array|callable|string $options
     *
     * @return $this|mixed
     */
    public function options($options = [])
    {
        // remote options
        if (is_string($options)) {
            // reload selected
            if (class_exists($options) && in_array(Model::class, class_parents($options))) {
                return $this->model(...func_get_args());
            }

            return $this->loadRemoteOptions(...func_get_args());
        }

        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        if (is_callable($options)) {
            $this->options = $options;
        } else {
            $this->options = (array) $options;
        }

        return $this;
    }

    /**
     * @param array $groups
     */

    /**
     * Set option groups.
     *
     * eg: $group = [
     *        [
     *        'label' => 'xxxx',
     *        'options' => [
     *            1 => 'foo',
     *            2 => 'bar',
     *            ...
     *        ],
     *        ...
     *     ]
     *
     * @param array $groups
     *
     * @return $this
     */
    public function groups(array $groups)
    {
        $this->groups = $groups;

        return $this;
    }

    /**
     * Load options for other select on change.
     *
     * @param string $field
     * @param string $sourceUrl
     * @param string $idField
     * @param string $textField
     *
     * @return $this
     */
    public function load($field, $url, $idField = 'id', $textField = 'text', bool $allowClear = true)
    {
        if (Str::contains($field, '.')) {
            $field = $this->formatName($field);
            $class = str_replace(['[', ']'], '_', $field);
        } else {
            $class = $field;
        }

        $this->additional_script .= <<<JS

            (function() {
                var elm = document.querySelector("{$this->getElementClassSelector()}");
                elm.addEventListener('change', function(event) {
                    var ts = elm.tomselect;
                    var query = Array.isArray(ts.getValue()) ? ts.getValue().join(',') : ts.getValue();
                    var fieldElm = document.querySelector('.{$class}');
                    var fieldTs = fieldElm ? fieldElm.tomselect : null;
                    var current_value = fieldTs ? fieldTs.getValue() : '';
                    admin.ajax.post("{$url}", {query: query}, function(data) {
                        if (!fieldTs) return;
                        fieldTs.clearOptions();
                        var found = false;
                        for (var i in data.data) {
                            if (data.data[i]['{$idField}'] == current_value) {
                                data.data[i].selected = true;
                                found = true;
                            }
                        }
                        if (!found) data.data.unshift({'{$idField}': '', '{$textField}': ''});
                        fieldTs.addOptions(data.data);
                        fieldTs.refreshOptions(false);
                    });
                });
            })();
JS;

        return $this;
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
            var elm = document.querySelector("{$this->getElementClassSelector()}");
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
     * Load options from ajax results.
     *
     * @param string $url
     * @param $idField
     * @param $textField
     *
     * @return $this
     */
    public function ajax($url, $idField = 'id', $textField = 'text')
    {
        $this->config = array_merge([
            'valueField'  => $idField,
            'labelField'  => $textField,
            'searchField' => $textField,
            'load'        => "ajax:{$url}",
        ], $this->config);

        return $this;
    }

    /**
     * Set use browser native selectbox.
     *
     * @return $this
     */
    public function useNative()
    {
        $this->native = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function useChoicesjs()
    {
        $this->native = false;

        return $this;
    }

    /**
     * Set config for Tom Select.
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
            $field = str_replace([' ', '-'], ['_', '_'], $this->getElementClassString());
        }

        return 'ts_'.$field;
    }

    /** @deprecated Use tomSelectObjName() */
    public function choicesObjName($field = false)
    {
        return $this->tomSelectObjName($field);
    }

    /**
     * Set as readonly (actual dissable with backup hidden field).
     */
    public function readonly($set = true): self
    {
        $this->useNative();
        $this->config('readonly', $set);
        $this->disabled($set);

        return parent::readonly($set);
    }

    /**
     * Check if field should be rendered with Tom Select.
     */
    public function allowedChoicesJs()
    {
        $class = get_class($this);

        return in_array($class, [
            'ZiiX\Admin\Form\Field\Select',
            'ZiiX\Admin\Form\Field\Tags',
            'ZiiX\Admin\Form\Field\MultipleSelect',
            'ZiiX\Admin\Form\Field\Timezone',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $baseConfig = [
            'allowEmptyOption' => true,
            'placeholder'      => $this->label,
        ];

        // Handle ajax load config (stored as "ajax:url" marker)
        $ajaxUrl = null;
        if (isset($this->config['load']) && str_starts_with($this->config['load'], 'ajax:')) {
            $ajaxUrl = substr($this->config['load'], 5);
            unset($this->config['load']);
        }

        $configs = array_merge($baseConfig, $this->config);
        $configs_json = json_encode($configs);

        if (!$this->native && $this->allowedChoicesJs()) {
            if ($ajaxUrl) {
                $valueField = $configs['valueField'] ?? 'id';
                $labelField = $configs['labelField'] ?? 'text';
                $searchField = $configs['searchField'] ?? 'text';
                $this->script .= <<<JS
var {$this->tomSelectObjName()} = new TomSelect('{$this->getElementClassSelector()}', Object.assign({$configs_json}, {
    valueField: '{$valueField}',
    labelField: '{$labelField}',
    searchField: '{$searchField}',
    load: function(query, callback) {
        admin.ajax.post('{$ajaxUrl}', {query: query}, function(data) { callback(data.data); });
    },
    onItemAdd: function() { this.setTextboxValue(''); this.refreshOptions(); }
}));
JS;
            } else {
                $this->script .= "var {$this->tomSelectObjName()} = new TomSelect('{$this->getElementClassSelector()}',{$configs_json});";
            }
            $this->script .= $this->additional_script;
        }

        if ($this->options instanceof \Closure) {
            if ($this->form) {
                $this->options = $this->options->bindTo($this->form->model());
            }
            $this->options(call_user_func($this->options, $this->value, $this));
        }

        $this->options = array_filter($this->options, 'strlen');

        $this->addVariables([
            'options' => $this->options,
            'groups'  => $this->groups,
        ]);

        $this->addCascadeScript();

        $this->attribute('data-value', implode(',', (array) $this->value()));

        return parent::render();
    }
}

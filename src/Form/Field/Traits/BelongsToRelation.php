<?php

namespace OpenAdmin\Admin\Form\Field\Traits;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Selectable;

trait BelongsToRelation
{
    /**
     * @var string
     */
    protected $modalID;

    /**
     * @var string
     */
    protected $selectable;

    /**
     * BelongsToRelation constructor.
     *
     * @param string $column
     * @param array  $arguments
     */
    public function __construct($column, $arguments = [])
    {
        $this->setSelectable($arguments[0]);

        parent::__construct($column, array_slice($arguments, 1));
    }

    /**
     * @param string $selectable
     */
    protected function setSelectable($selectable)
    {
        if (!class_exists($selectable) || !is_subclass_of($selectable, Selectable::class)) {
            throw new \InvalidArgumentException(
                "[Class [{$selectable}] must be a sub class of OpenAdmin\Admin\Grid\Selectable"
            );
        }

        $this->selectable = $selectable;
    }

    /**
     * @return string
     */
    public function getSelectable()
    {
        return $this->selectable;
    }

    /**
     * @param int $multiple
     *
     * @return string
     */
    protected function getLoadUrl()
    {
        $selectable = str_replace('\\', '_', $this->selectable);
        $multiple = !empty($this->multiple) ? 1 : 0;
        $args = [$multiple];

        return route('admin.handle-selectable', compact('selectable', 'args'));
    }

    /**
     * @return $this
     */
    public function addHtml()
    {
        $trans = [
            'choose' => admin_trans('admin.choose'),
            'cancal' => admin_trans('admin.cancel'),
            'submit' => admin_trans('admin.submit'),
        ];

        $html = <<<HTML
<div id="{$this->modalID}" tabindex="-1" aria-hidden="true" class="belongsto hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 flex justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
  <div class="relative p-4 w-full max-w-4xl max-h-full">
    <div class="relative bg-white rounded-xl shadow">
      <div class="flex items-center justify-between p-4 border-b rounded-t">
        <h4 class="text-lg font-semibold text-gray-900">{$trans['choose']}</h4>
        <button type="button" data-modal-hide="{$this->modalID}" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" aria-label="Close">
            <i class="icon-x"></i>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="loading text-center py-8">
            <div class="icon-spin">
                <i class="icon-spinner icon-spin text-3xl text-gray-400"></i>
            </div>
        </div>
      </div>
      <div class="flex items-center p-4 border-t gap-3">
        <button type="button" data-modal-hide="{$this->modalID}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">{$trans['cancal']}</button>
        <button type="button" class="submit px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">{$trans['submit']}</button>
      </div>
    </div>
  </div>
</div>
HTML;
        Admin::html($html);

        return $this;
    }

    /**
     * @return $this
     */
    public function addStyle()
    {
        $style = <<<'STYLE'
            .belongsto.modal tr {
                cursor: pointer;
            }

            .belongsto .modal-body{
                padding:0;
            }

            .belongsto.modal .box {
                border-top: none;
                margin-bottom: 0;
                box-shadow: none;
            }

            .belongsto.modal .loading {
                margin: 50px;
            }

            .belongsto-selected-rows footer{
                display:none;
            }

            .belongsto-selected-rows .card-header{
                padding:0rem;
            }

            .belongsto-selected-rows table{
                border:1px solid var(--table-border-color);
            }
            .belongsto-selected-rows td.column-__remove__{
                text-align:center;
            }

            .belongsto.modal .grid-table .empty-grid {
                padding: 20px !important;
            }

            .belongsto.modal .grid-table .empty-grid svg {
                width: 40px !important;
                height: 40px !important;
            }

            .belongsto.modal .grid-box .box-footer {
                border-top: none !important;
            }
        STYLE;

        Admin::style($style);

        return $this;
    }

    public function addScript()
    {
        $column = $this->column();

        $script = <<<JS
;(function () {
    var grid = document.querySelector('.{$this->relation_prefix}{$column}');
    var table = grid.querySelector('.grid-table');

    // remove row
    grid.addEventListener("click", function (event) {

        if (event.target.classList.contains('grid-row-remove') || event.target.classList.contains('icon-trash')){

            var tr = event.target.closest('tr');

            var removeKey = tr.dataset.key;
            var field = document.querySelectorAll("{$this->getElementClassSelector()} option").forEach(option=>{
                if (option.value == removeKey){
                    option.remove();
                }
            })

            if ("{$this->relation_type}" == "one"){
                document.querySelector("{$this->getElementClassSelector()}").value = null;
            }
            tr.remove();

            if (table.querySelectorAll('tbody tr').length == 0){
                var empty = document.querySelector('.{$this->relation_prefix}{$column} template.empty').innerHTML;
                var clone = htmlToElement(empty);
                table.querySelector('tbody').appendChild(clone);
            }
        }
    });

    setValue = function(values,rows){

        var field = document.querySelector("{$this->getElementClassSelector()}");
        field.innerHTML = "";

        var tbody = table.querySelector('tbody');
        for(i in values){
            var value = values[i];
            var option = new Option(value);
            option.selected = true;
            field.add(option);

            if (tbody.querySelector(".row-"+value)){
                // already there
            }else{
                var row = rows[value];
                row.querySelector('td:last-child a').classList.remove('d-none');
                row.querySelector('td:first-child').remove();
                tbody.appendChild(row);
            }
        }
        tbody.querySelectorAll("tr").forEach(tr=>{
            if (!values.includes(tr.dataset.key)){
                tr.remove();
            }
        })
    }

    function getValue(){
        var field = document.querySelector("{$this->getElementClassSelector()}");
        if ("{$this->relation_type}" == "one"){
            return field.value;
        }else{
            var arr = []
            document.querySelectorAll("{$this->getElementClassSelector()} option").forEach(option=>{
                if (option.selected){
                    arr.push(option.value);
                }
            });
            return arr;
        }
    }

    var config = {
        url : "{$this->getLoadUrl()}",
        modal_elm : document.querySelector('#{$this->modalID}'),
        trigger : '.{$this->relation_prefix}{$column} .select-relation',
        update : setValue,
        value : getValue
    }
    admin.selectable.init(config);

})();

JS;

        Admin::script($script);

        return $this;
    }

    /**
     * @return \OpenAdmin\Admin\Grid
     */
    protected function makeGrid()
    {
        /** @var Selectable $selectable */
        $selectable = new $this->selectable();

        return $selectable->renderFormGrid($this->value());
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->modalID = sprintf('modal-selector-%s', $this->getElementClassString());

        $this->addScript()->addHtml()->addStyle();

        $this->addVariables([
            'grid'    => $this->makeGrid(),
            'options' => $this->getOptions(),
        ]);

        $this->addCascadeScript();

        return parent::fieldRender();
    }
}

<?php

namespace ZiiX\Admin\Grid\Concerns;

use ZiiX\Admin\Admin;

trait CanSelectClick
{
    /**
     * Double-click grid row to jump to the edit page.
     *
     * @return $this
     */
    public function enableSelectClick()
    {
        $script = <<<SCRIPT
        
        let trs = document.querySelectorAll(".select-table tr");
            trs.forEach(tr => {
                tr.addEventListener('click', function(event) {
                    if (event.target.tagName == "TD"){
                        input = event.target.closest("tr").getElementsByClassName("row-selector")[0];
                        if (input){
                            id = input.dataset.id;
                            document.querySelectorAll(".row-"+id+" .row-selector").forEach( input =>{
                                input.checked ^= 1;
                            } );
                            admin.grid.check_status();
                        }
                    }
                }, false);
            });
SCRIPT;
        Admin::script($script);

        return $this;
    }
}

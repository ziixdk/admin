/*-------------------------------------------------*/
/* grid - inline-edit */
/*-------------------------------------------------*/

    admin.grid.inline_edit = {

        popovers : [],
        popover : false,
        trigger : false,
        functions : {},

        init : function(){

            document.addEventListener('click', function (event) {
                if (admin.grid.inline_edit.popover){
                    if (!event.target.closest('.ie-popover')) {
                        admin.grid.inline_edit.popover.hide();
                    }
                }
            });
        },

        hide_other_popovers : function(me){
            admin.grid.inline_edit.popovers.forEach(popover =>{
                if (me !== popover) {
                    popover.hide();
                }
            })
        },

        /* Legacy alias */
        hide_ohter_popovers : function(me){ this.hide_other_popovers(me); },

        create_popover : function(el, getContent) {
            var popoverEl = null;

            var self = {
                _element: el,
                eventsAdded: false,
                tip: null,

                show: function() {
                    if (popoverEl) self.hide();

                    popoverEl = document.createElement('div');
                    popoverEl.className = 'ie-popover absolute z-50 bg-white rounded-lg shadow-xl border border-gray-200 p-3 min-w-48';
                    popoverEl.appendChild(getContent());

                    document.body.appendChild(popoverEl);
                    self.tip = popoverEl;

                    // Position near trigger
                    var rect = el.getBoundingClientRect();
                    var top = window.scrollY + rect.top - popoverEl.offsetHeight - 8;
                    if (top < 8) top = window.scrollY + rect.bottom + 8;
                    popoverEl.style.position = 'absolute';
                    popoverEl.style.top = top + 'px';
                    popoverEl.style.left = Math.max(4, window.scrollX + rect.left) + 'px';
                },

                hide: function() {
                    if (popoverEl) { popoverEl.remove(); popoverEl = null; self.tip = null; }
                },

                toggle: function() {
                    if (popoverEl) { self.hide(); } else { self.show(); }
                }
            };

            return self;
        },

        init_popover : function(triggerId,target){

            var el = document.getElementById(triggerId);

            var getContent = function() {
                var tpl = document.querySelector("template#"+target);
                var content = tpl.cloneNode(true).content;

                if(typeof(admin.grid.inline_edit.functions[triggerId]) != 'undefined'){
                    if(typeof(admin.grid.inline_edit.functions[triggerId].content) === "function"){
                        admin.grid.inline_edit.functions[triggerId].content(el, content);
                    }
                }
                return content;
            };

            var popover = admin.grid.inline_edit.create_popover(el, getContent);

            el.addEventListener('click', function (event) {
                admin.grid.inline_edit.trigger = this;
                admin.grid.inline_edit.popover = popover;
                admin.grid.inline_edit.hide_other_popovers(popover);

                popover.toggle();

                if (popover.tip && !popover.eventsAdded) {
                    popover.tip.addEventListener("click", function(event){
                        if (event.target.classList.contains("ie-cancel")){
                            popover.hide();
                        }
                        if (event.target.classList.contains("ie-submit")){
                            admin.grid.inline_edit.save();
                        }
                        event.stopPropagation();
                        return false;
                    });
                    popover.eventsAdded = true;

                    triggerId = this.id;
                    if(typeof(admin.grid.inline_edit.functions[triggerId]) != 'undefined'){
                        if(typeof(admin.grid.inline_edit.functions[triggerId].shown) === "function"){
                            admin.grid.inline_edit.functions[triggerId].shown(el, popover.tip);
                        }
                    }
                }

                event.stopPropagation();
            });

            admin.grid.inline_edit.popovers.push(popover);
        },

        save : function(){

            let popover = admin.grid.inline_edit.popover;
            let content = popover.tip.querySelector(".ie-container");
            let trigger = this.trigger;
            let valueObject = this.retrieveValues(trigger, content);
            let original = trigger.dataset.original;

            if (valueObject.val == original) {
                console.log("nah its the same");
                popover.hide();
                return;
            }

            let resource = trigger.dataset.resource;
            let key = trigger.dataset.key;
            let url = resource+"/" + key;
            let obj = {
                method : 'post',
                data : {
                    _method: 'PUT',
                    _edit_inline: true,
                    'after-save': 'exit'
                }
            }
            obj.data[trigger.dataset.name] = valueObject.val;

            admin.ajax.request(url,obj,function(result){
                if (result.status){
                    trigger.dataset.original = valueObject.val;
                    trigger.querySelector(".ie-display").innerHTML = valueObject.label;
                    admin.toastr.success(result.data);
                    popover.hide();
                }else{
                    admin.toastr.warning(result.data);
                }
            });
        },

        retrieveValues : function(trigger,content){

            let val = false;
            let triggerId = trigger.id;
            if(typeof(admin.grid.inline_edit.functions[triggerId]) != 'undefined'){
                if(typeof(admin.grid.inline_edit.functions[triggerId].returnValue) === "function"){
                    val = admin.grid.inline_edit.functions[triggerId].returnValue(trigger,content);
                }
            }
            console.log(val);
            if (!val){
                val = content.querySelector('.ie-input').value;
            }
            if (typeof(val) === "string"){
                val = {'val':val,'label':val};
            }

            return val;
        }
    }

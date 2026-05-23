/*-------------------------------------------------*/
/* admin.modals  */
/*-------------------------------------------------*/

    admin.selectable = {

        /*
        var config = {
            modal_elm : '#id / .class',
            url : 'resourceSelectUrl',
            update : function, //for setting value
            modalTrigger : '#id / .class', //select that triggers the modal',
            value : 'string, array or function'
        }
        */

        init : function(config){

            var modal_elm = config.modal_elm;
            var modal = admin.modal.create(modal_elm);
            var related;
            var values;
            var rows = {};

            // If trigger selector provided, wire up click → show
            if (typeof(config.trigger) !== 'undefined'){
                document.querySelectorAll(config.trigger).forEach(elm=>{
                    elm.addEventListener("click",function (e) {
                        related = elm;
                        _initShow(elm);
                        modal.show(elm);
                        e.preventDefault();
                    });
                })
            }

            var _initShow = function(relatedTarget) {
                if (typeof(config.value) != 'undefined'){
                    if (typeof(config.value) === 'function'){
                        values = config.value(relatedTarget);
                    }else{
                        values = config.value;
                    }

                    if (typeof(values) === "string"){
                        values = [values];
                    }
                }else{
                    values = [];
                }

                load(config.url);
            };

            var load = function (url) {
                admin.ajax.request(url, {}, function (data) {
                    modal_elm.querySelector('.modal-body').innerHTML = data.data;

                    modal_elm.querySelectorAll(".form-check-input").forEach(input=>{

                        if (values.includes(String(input.value)) || values.includes(Number(input.value))){
                            input.checked = true;
                            rows[input.value] = input.closest("tr");
                        }
                        input.addEventListener("change",function(event){

                            if (event.target.checked){
                                if (input.type == "radio"){
                                    values = [input.value];
                                }else{
                                    values.push(input.value);
                                }
                                rows[input.value] = event.target.closest("tr");
                            }else{
                                values = arr_remove(values,input.value);
                            }
                        })
                    })
                });
            };

            modal_elm.ref = this;
            modal_elm.modal = modal;

            // respond to show event (set by data-modal-trigger or trigger config)
            modal_elm.addEventListener('modal.show', function (event) {
                var relatedTarget = event.detail.relatedTarget;
                if (relatedTarget) {
                    related = relatedTarget;
                }
                admin.ajax.currenTarget = modal_elm.querySelector('.modal-body');
                _initShow(related);
            })

            modal_elm.addEventListener('modal.hide', function (event) {
                admin.ajax.currenTarget = false;
            })

            modal_elm.querySelector('.modal-footer .submit').addEventListener('click', function (event) {

                if (typeof(config.update) != 'undefined'){
                    config.update(values,rows,related);
                }
                modal.hide();

                event.preventDefault();
                event.stopPropagation();
                return false;
            });

            modal_elm.addEventListener('click', function (event) {

                if (event.target.classList.contains('submit')){
                    var form = event.target.closest("form");
                    var formData = new FormData(form);
                    var queryString = new URLSearchParams(formData).toString();
                    load(form.getAttribute('action')+'&'+queryString);
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                if (event.target.classList.contains('btn-light') || event.target.classList.contains('btn-cancel')){
                    var form = event.target.closest("form");
                    if (form){
                        load(form.getAttribute('action'));
                    }
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                }

                if (event.target.tagName == "TD"){
                    event.target.parentNode.querySelector(".form-check-input").click();
                }
            })
        },

        hideModal : function (){
            this.currentModal.hide();
        }
    }

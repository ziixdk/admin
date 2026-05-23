/*-------------------------------------------------*/
/* resource  */
/*-------------------------------------------------*/

    admin.resource = {

        delete : function(event,delete_link){

            let navigate_url = false;
            let resource_url = delete_link.dataset.url;
            if (delete_link.dataset.list_url){
                navigate_url = delete_link.dataset.list_url;
            }
            this.delete_do(resource_url,navigate_url);
        },

        batch_delete : function (resource_url){
            this.delete_do(resource_url);
        },

        delete_do : function(resource_url,navigate_url){

            admin.confirm(__('delete_confirm'), {
                confirmButtonText: __('confirm'),
                cancelButtonText:  __('cancel'),
            }).then(function(){
                let data = {_method:'delete'};
                admin.ajax.post(resource_url, data, function(response){
                    let resp = response && response.data;
                    if (typeof resp === 'object') {
                        if (resp.status) {
                            admin.toastr.success(resp.message);
                        } else {
                            admin.toastr.error(resp.message);
                        }
                    }
                    if (navigate_url){
                        admin.ajax.navigate(navigate_url);
                    } else {
                        admin.ajax.reload();
                    }
                });
            }).catch(function(){});
        },

        batch_edit : function (resource_url){
            let parts = resource_url.split(",");
            let edit_url = parts.shift()+"/edit";
            if (parts.length){
                edit_url += "?ids[]="+parts.join("&ids[]=");
            }
            admin.ajax.navigate(edit_url);
        },

        default_swal_response : function(result) {
            let data = result && result.value;
            if (typeof data === 'object') {
                if (data.status) {
                    admin.toastr.success(data.message);
                } else {
                    admin.toastr.error(data.message);
                }
            }
        },

        default_swal_response_and_reload : function(result) {
            admin.ajax.reload();
            admin.resource.default_swal_response(result);
        }
    }

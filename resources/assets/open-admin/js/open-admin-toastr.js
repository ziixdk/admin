/*-------------------------------------------------*/
/* Toast notifications — Flowbite/Tailwind based  */
/*-------------------------------------------------*/

admin.toastr = {

    _colorMap: {
        success: { bg: 'bg-green-100', text: 'text-green-500', icon: 'icon-check-circle' },
        alert:   { bg: 'bg-yellow-100', text: 'text-yellow-500', icon: 'icon-exclamation-triangle' },
        warning: { bg: 'bg-yellow-100', text: 'text-yellow-500', icon: 'icon-exclamation-triangle' },
        error:   { bg: 'bg-red-100', text: 'text-red-500', icon: 'icon-times-circle' },
        info:    { bg: 'bg-blue-100', text: 'text-blue-500', icon: 'icon-info-circle' },
    },

    toast: function (message, options) {
        var type    = (options && options._type) ? options._type : 'success';
        var colors  = this._colorMap[type] || this._colorMap.success;
        var timeout = (options && options.duration) ? options.duration : 3000;
        var id      = 'toast-' + Date.now();

        var el = document.createElement('div');
        el.id = id;
        el.className = 'flex items-center w-full max-w-xs p-4 mb-3 text-gray-500 bg-white rounded-lg shadow pointer-events-auto';
        el.setAttribute('role', 'alert');
        el.innerHTML = `
            <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 ${colors.bg} ${colors.text} rounded-lg">
                <i class="${colors.icon}"></i>
            </div>
            <div class="ms-3 text-sm font-normal">${message}</div>
            <button type="button" onclick="document.getElementById('${id}').remove()"
                class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8">
                <i class="icon-times text-xs"></i>
            </button>`;

        var container = document.getElementById('admin-toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'admin-toast-container';
            container.className = 'fixed top-4 end-4 z-50 flex flex-col items-end pointer-events-none';
            document.body.appendChild(container);
        }

        container.appendChild(el);

        if (timeout > 0) {
            setTimeout(function () {
                if (document.getElementById(id)) document.getElementById(id).remove();
            }, timeout);
        }
    },

    success: function (text, obj) { this.toast(text, Object.assign({}, obj, { _type: 'success' })); },
    alert:   function (text, obj) { this.toast(text, Object.assign({}, obj, { _type: 'alert' })); },
    warning: function (text, obj) { this.toast(text, Object.assign({}, obj, { _type: 'warning' })); },
    error:   function (text, obj) { this.toast(text, Object.assign({}, obj, { _type: 'error' })); },
    info:    function (text, obj) { this.toast(text, Object.assign({}, obj, { _type: 'info' })); },
};

admin.toast = function (text, obj) { admin.toastr.toast(text, obj); };

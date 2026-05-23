<span class="grid-modal-trigger cursor-pointer" data-modal-target="grid-modal-{{ $name }}" data-key="{{ $key }}">
   <a href="#"><i class="icon-clone"></i>&nbsp;&nbsp;{{ $value }}</a>
</span>

<div class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex items-center justify-center p-4 grid-modal" id="grid-modal-{{ $name }}" tabindex="-1" aria-hidden="true">
    <div class="relative w-full max-w-3xl bg-white rounded-xl shadow-xl max-h-full flex flex-col">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h4 class="text-base font-semibold text-gray-900">{{ $title }}</h4>
            <button type="button" data-modal-close="grid-modal-{{ $name }}" class="p-1 text-gray-400 hover:text-gray-600 rounded" aria-label="Close">
                <i class="icon-times text-sm"></i>
            </button>
        </div>
        <div class="modal-body overflow-y-auto p-4 flex-1">
            {!! $html !!}
        </div>
    </div>
</div>

@if($async)
<script>
    (function() {
        var modal = document.querySelector('#grid-modal-{{ $name }}');
        var modalBody = modal.querySelector('.modal-body');
        var adminModal = admin.modal.create(modal);

        var load = function (url) {
            modalBody.innerHTML = '<div class="flex items-center justify-center py-16"><i class="icon-spinner icon-pulse text-3xl text-gray-400"></i></div>';
            axios.get(url)
                .then(function (response) { modalBody.innerHTML = response.data; })
                .catch(function (error) { console.log(error); });
        };

        document.querySelectorAll('.grid-modal-trigger[data-modal-target="grid-modal-{{ $name }}"]').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                var key = trigger.dataset.key;
                load('{{ $url }}'+'&key='+key);
                adminModal.show(trigger);
                e.preventDefault();
            });
        });
    })();
</script>
@else
<script>
    (function() {
        var modal = document.querySelector('#grid-modal-{{ $name }}');
        var adminModal = admin.modal.create(modal);

        document.querySelectorAll('.grid-modal-trigger[data-modal-target="grid-modal-{{ $name }}"]').forEach(function(trigger) {
            trigger.addEventListener('click', function(e) {
                adminModal.show(trigger);
                e.preventDefault();
            });
        });
    })();
</script>
@endif

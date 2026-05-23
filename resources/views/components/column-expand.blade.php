<div>
    <span class="{{ $elementClass }} cursor-pointer" data-inserted="0" data-key="{{ $key }}" data-name="{{ $name }}">
        <a href="#"><i class="icon-angle-double-down"></i>&nbsp;&nbsp;{{ $value }}</a>
    </span>
    <template class="grid-expand-{{ $name }}">
        <tr style='background-color: #f8fafc;'>
            <td colspan='100%' style='padding:0 !important; border:0;height:auto;'>
                <div id="grid-collapse-{{ $name }}">
                    <div style="padding: 10px 10px 0 10px;" class="html">
                        @if($html)
                            {!! $html !!}
                        @else
                            <div class="flex items-center justify-center py-5">
                                <i class="icon-spinner icon-pulse text-3xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </td>
        </tr>
    </template>
</div>

<script>
    var expand = document.querySelectorAll('.{{ $elementClass }}');

    expand.forEach(el=>{
        el.addEventListener('click', function (e) {
            var name = el.dataset.name;

            if (el.dataset.inserted == '0') {
                var row = e.target.closest('tr');
                var key = el.dataset.key;
                var new_row = document.querySelector('template.grid-expand-'+name).content.cloneNode(true);
                row.after(new_row);
                // just show inline — no Bootstrap collapse needed
                var target = document.querySelector("#grid-collapse-"+name);
                if (target) target.style.display = 'block';

                @if($async)
                    let url = '{{ $url }}'+'&key='+key;
                    axios.get(url)
                    .then(function (response) {
                        target.querySelector('.html').innerHTML = response.data;
                    }).catch(function (error) {
                        console.log(error);
                    });
                @endif

                el.dataset.inserted = 1;
            }

            var i = el.querySelector("i");
            i.classList.toggle("icon-angle-double-down");
            i.classList.toggle("icon-angle-double-up");
        });
    });
    @if ($expand)
        expand.forEach(el => el.click());
    @endif
</script>

@if($loadGrid)
<style>
    #grid-collapse-{{ $name }} .grid-box .box-header:first-child {
        display: none;
    }
</style>
@endif

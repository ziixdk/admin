<div class="grid-selector border-b border-gray-100">
    @foreach($selectors as $column => $selector)
        <div class="flex items-center gap-2 px-4 py-2 border-b border-dashed border-gray-100 last:border-0 text-sm">
            <span class="w-28 shrink-0 text-gray-400 text-xs">{{ $selector['label'] }}</span>
            <ul class="flex flex-wrap gap-2 m-0 p-0 list-none">
                @foreach($selector['options'] as $value => $option)
                    @php
                        $active = in_array($value, \Illuminate\Support\Arr::get($selected, $column, []));
                    @endphp
                    <li class="flex items-center gap-1">
                        <a href="{{ \ZiiX\Admin\Grid\Tools\Selector::url($column, $value, true) }}"
                           class="text-sm {{ $active ? 'text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600' }}">{{ $option }}</a>
                        @if(!$active && $selector['type'] == 'many')
                            <a href="{{ \ZiiX\Admin\Grid\Tools\Selector::url($column, $value) }}"
                               class="add text-gray-300 hover:text-blue-500"><i class="icon-plus-square text-xs"></i></a>
                        @else
                            <span class="w-3 inline-block"></span>
                        @endif
                    </li>
                @endforeach
                <li>
                    <a href="{{ \ZiiX\Admin\Grid\Tools\Selector::url($column) }}"
                       class="clear text-gray-300 hover:text-red-500"><i class="icon-trash text-xs"></i></a>
                </li>
            </ul>
        </div>
    @endforeach
</div>

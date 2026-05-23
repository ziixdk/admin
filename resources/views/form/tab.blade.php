@php $tabs = $tabObj->getTabs(); $firstTab = $tabs[0]['id'] ?? ''; @endphp
<div x-data="{ activeTab: '{{ $firstTab }}' }" class="nav-tabs-custom">
    <ul class="flex flex-wrap border-b border-gray-200 px-4 pt-2 gap-0.5">

        @foreach($tabs as $tab)
            <li class="nav-item">
                <button type="button"
                    @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="nav-link inline-block px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors"
                    :data-active="activeTab === '{{ $tab['id'] }}'">
                    {{ $tab['title'] }}
                    <i class="icon-exclamation-circle text-red-500 hide ms-1"></i>
                </button>
            </li>
        @endforeach

    </ul>
    <div class="tab-content fields-group p-4">

        @foreach($tabs as $tab)
            <div x-show="activeTab === '{{ $tab['id'] }}'" id="tab-{{ $tab['id'] }}" class="tab-pane">
                @foreach($tab['fields'] as $field)
                    {!! $field->render() !!}
                @endforeach
            </div>
        @endforeach

    </div>
</div>

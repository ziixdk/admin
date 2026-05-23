<li class="dd-item" data-id="{{ $branch[$keyName] }}">
    <div class="dd-handle flex items-center justify-between py-1">
        <span>{!! $branchCallback($branch) !!}</span>
        <span class="dd-nodrag flex items-center gap-3 text-gray-400 ms-2">
            <a href="{{ url("$path/$branch[$keyName]/edit") }}" class="hover:text-blue-600"><i class="icon-edit"></i></a>
            <a onclick="admin.tree.delete({{ $branch[$keyName] }})" data-id="{{ $branch[$keyName] }}" class="tree_branch_delete cursor-pointer hover:text-red-600"><i class="icon-trash"></i></a>
        </span>
    </div>
    @if(isset($branch['children']))
    <ol class="dd-list">
        @foreach($branch['children'] as $branch)
            @include($branchView, $branch)
        @endforeach
    </ol>
    @endif
</li>

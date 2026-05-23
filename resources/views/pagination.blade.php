<ul class="inline-flex items-center gap-0.5 text-sm">
    <!-- Previous Page Link -->
    @if ($paginator->onFirstPage())
    <li><span class="inline-flex items-center justify-center w-8 h-8 text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&laquo;</span></li>
    @else
    <li><a class="inline-flex items-center justify-center w-8 h-8 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a></li>
    @endif

    <!-- Pagination Elements -->
    @foreach ($elements as $element)
    <!-- "Three Dots" Separator -->
    @if (is_string($element))
    <li><span class="inline-flex items-center justify-center w-8 h-8 text-gray-400">{{ $element }}</span></li>
    @endif

    <!-- Array Of Links -->
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if ($page == $paginator->currentPage())
    <li><span class="inline-flex items-center justify-center w-8 h-8 text-white bg-blue-600 border border-blue-600 rounded-lg font-medium">{{ $page }}</span></li>
    @else
    <li><a class="inline-flex items-center justify-center w-8 h-8 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50" href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endforeach
    @endif
    @endforeach

    <!-- Next Page Link -->
    @if ($paginator->hasMorePages())
    <li><a class="inline-flex items-center justify-center w-8 h-8 text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a></li>
    @else
    <li><span class="inline-flex items-center justify-center w-8 h-8 text-gray-400 bg-white border border-gray-200 rounded-lg cursor-not-allowed">&raquo;</span></li>
    @endif
</ul>

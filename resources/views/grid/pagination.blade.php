<footer class="bg-white border-t border-gray-200 px-4 py-3 flex flex-wrap items-center justify-between gap-3 @if (!empty($fixedFooter)) sticky bottom-0 shadow-sm @endif">
    <div class="text-sm text-gray-600">{!! $range !!}</div>
    <div class="text-sm text-gray-600">{!! $per_page !!}</div>
    <nav>{!! $links !!}</nav>
</footer>

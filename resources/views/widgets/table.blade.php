<table {!! $attributes !!}>
    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
    <tr>
        @foreach($headers as $header)
            <th class="px-4 py-3 font-medium">{{ $header }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
    @foreach($rows as $row)
    <tr class="hover:bg-gray-50">
        @foreach($row as $item)
        <td class="px-4 py-3">{!! $item !!}</td>
        @endforeach
    </tr>
    @endforeach
    </tbody>
</table>

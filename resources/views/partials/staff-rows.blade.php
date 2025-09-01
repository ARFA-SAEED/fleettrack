@foreach($staff as $index => $s)
<tr data-row="{{ $index }}">
    <td class="select-cell"></td>
    <td contenteditable="false" data-col="A">{{ $s[0] ?? '' }}</td>
    <td class="editable" contenteditable="true" data-col="B" data-row="{{ $loop->iteration }}">{{ $s[1] ?? '' }}</td>
    <td class="editable" contenteditable="true" data-col="C" data-row="{{ $loop->iteration }}">{{ $s[2] ?? '' }}</td>
    <td class="editable" contenteditable="true" data-col="D" data-row="{{ $loop->iteration }}">{{ $s[3] ?? '' }}</td>
    <td class="editable" contenteditable="true" data-col="E" data-row="{{ $loop->iteration }}">{{ $s[4] ?? '' }}</td>
    <td class="expiry-cell" contenteditable="true" data-col="F" data-row="{{ $loop->iteration }}">{{ $s[5] ?? '' }}</td>
    <td contenteditable="false" data-col="G">{{ $s[6] ?? '' }}</td>
    <td contenteditable="false" data-col="H">{{ $s[7] ?? '' }}</td>
    <td contenteditable="false" data-col="I">{{ $s[8] ?? '' }}</td>
</tr>
@endforeach

@foreach($vehicles as $index => $s)
    <tr data-row="{{ $index }}">
        <td class="select-cell"></td>
        <td contenteditable="false" data-col="A">{{ $s[0] ?? '' }}</td>
        <td contenteditable="false" data-col="B">{{ $s[1] ?? '' }}</td>
        <td contenteditable="false" data-col="C">{{ $s[2] ?? '' }}</td>
        <td contenteditable="false" data-col="D">{{ $s[3] ?? '' }}</td>
        <td contenteditable="false" data-col="E">{{ $s[4] ?? '' }}</td>
        <td contenteditable="false" data-col="F">{{ $s[5] ?? '' }}</td>
        <td contenteditable="false" data-col="G">{{ $s[6] ?? '' }}</td>
        <td contenteditable="false" data-col="H">{{ $s[7] ?? '' }}</td>
        <td contenteditable="false" data-col="I">{{ $s[8] ?? '' }}</td>
        <td contenteditable="false" data-col="j">{{ $s[9] ?? '' }}</td>
        <td contenteditable="false" data-col="K">{{ $s[10] ?? '' }}</td>
        <td contenteditable="false" data-col="L">{{ $s[11] ?? '' }}</td>
        <td contenteditable="false" data-col="M">{{ $s[12] ?? '' }}</td>
        <td contenteditable="false" data-col="N">{{ $s[13] ?? '' }}</td>
        <td contenteditable="false" data-col="O">{{ $s[14] ?? '' }}</td>
        <td contenteditable="false" data-col="P">{{ $s[15] ?? '' }}</td>
        <td contenteditable="false" data-col="Q">{{ $s[16] ?? '' }}</td>
        <td contenteditable="false" data-col="R">{{ $s[17] ?? '' }}</td>
        <td contenteditable="false" data-col="S">{{ $s[18] ?? '' }}</td>
        <td contenteditable="false" data-col="T">{{ $s[19] ?? '' }}</td>
        <td contenteditable="false" data-col="U">{{ $s[20] ?? '' }}</td>
        <td contenteditable="false" data-col="V">{{ $s[21] ?? '' }}</td>
        <td contenteditable="false" data-col="W">{{ $s[22] ?? '' }}</td>
        <td contenteditable="false" data-col="X">{{ $s[23] ?? '' }}</td>
        <td contenteditable="false" data-col="Y">{{ $s[24] ?? '' }}</td>
        <td contenteditable="false" data-col="Z">{{ $s[25] ?? '' }}</td>
        <td contenteditable="false" data-col="AA">{{ $s[26] ?? '' }}</td>
    </tr>
@endforeach
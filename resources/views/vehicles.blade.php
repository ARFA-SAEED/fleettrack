@extends('layouts.app')

@section('content')

<h2>Vehicles List</h2>
<input type="text" id="vehicleSearch" class="search-input" placeholder="Search Vehicles...">

<table id="vehicleTable">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Type</th>
        <th>Status</th>
    </tr>
    @foreach($vehicles as $index => $v)
    <tr data-row="{{ $start + $index }}">
        <td>{{ $start + $index }}</td>
        <td contenteditable="true" data-col="A">{{ $v[0] ?? '' }}</td>
        <td contenteditable="true" data-col="B">{{ $v[1] ?? '' }}</td>
        <td contenteditable="true" data-col="C">{{ $v[2] ?? '' }}</td>
    </tr>
    @endforeach
    <tr class="total-row" style="font-weight:bold;">
        <td colspan="3">Total Vehicles</td>
        <td>{{ $totalVehicles }}</td>
    </tr>
</table>

<button id="loadMoreBtn" @if($start + $limit > $totalVehicles) style="display:none" @endif>Load More</button>

<script>
// Live search
function liveSearch(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    input.addEventListener('keyup', function() {
        const filter = input.value.toLowerCase();
        const rows = table.getElementsByTagName('tr');
        for (let i = 1; i < rows.length - 1; i++) {
            let match = false;
            const cols = rows[i].getElementsByTagName('td');
            for (let j=1; j<cols.length; j++) {
                if(cols[j].innerText.toLowerCase().includes(filter)) match = true;
            }
            rows[i].style.display = match ? '' : 'none';
        }
    });
}
liveSearch('vehicleSearch','vehicleTable');

// Inline auto-save + keyboard nav
function enableInline(tableId, updateUrl) {
    document.querySelectorAll(`#${tableId} td[contenteditable="true"]`).forEach(cell=>{
        cell.addEventListener('blur', function(){
            const row = this.parentElement.dataset.row;
            const col = this.dataset.col;
            const value = this.innerText;
            fetch(`${updateUrl}/${row}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type':'application/json'
                },
                body: JSON.stringify({col,value})
            }).then(res=>res.json()).then(data=>{
                if(data.success) console.log('Saved');
            });
        });

        cell.addEventListener('keydown', function(e){
            const td = this;
            const tr = td.parentElement;
            let nextCell;
            if(e.key==='ArrowRight') nextCell = td.nextElementSibling;
            else if(e.key==='ArrowLeft') nextCell = td.previousElementSibling;
            else if(e.key==='ArrowDown') nextCell = tr.nextElementSibling?.children[td.cellIndex];
            else if(e.key==='ArrowUp') nextCell = tr.previousElementSibling?.children[td.cellIndex];
            else if(e.key==='Enter') { e.preventDefault(); nextCell = tr.nextElementSibling?.children[td.cellIndex]; }
            if(nextCell) nextCell.focus();
        });
    });
}
enableInline('vehicleTable','/vehicle/inline-update');

// Load More
let start = {{ $start + $limit }};
const limit = {{ $limit }};
const totalRows = {{ $totalVehicles }};

document.getElementById('loadMoreBtn').addEventListener('click', function() {
    fetch(`?start=${start}`)
        .then(res=>res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newRows = doc.querySelectorAll('#vehicleTable tr');
            const table = document.getElementById('vehicleTable');
            newRows.forEach((row,i)=>{
                if(i>0 && !row.classList.contains('total-row')) table.appendChild(row);
            });
            enableInline('vehicleTable','/vehicle/inline-update'); 
            attachRowSelection();
            start += limit;
            if(start >= totalRows) document.getElementById('loadMoreBtn').style.display='none';
        });
});

// Excel-style row selection + Delete key
let isSelecting = false;
let selectedRows = new Set();

const table = document.getElementById('staffTable');
const rows = Array.from(table.querySelectorAll('tr')).filter(r => r.dataset.row);

// Start selection
rows.forEach(tr => {
    const selectorCell = tr.querySelector('.select-cell');

    selectorCell.addEventListener('mousedown', e => {
        isSelecting = true;
        selectedRows.clear(); // Clear previous selection
        toggleRow(tr);
        e.preventDefault(); // prevent text selection
    });

    selectorCell.addEventListener('mouseenter', e => {
        if (isSelecting) toggleRow(tr);
    });
});

// Stop selection
document.addEventListener('mouseup', () => { isSelecting = false; });

// Toggle row selection
function toggleRow(tr) {
    const rowNum = tr.dataset.row;
    if (selectedRows.has(rowNum)) selectedRows.delete(rowNum);
    else selectedRows.add(rowNum);

    highlightSelected();
}

// Highlight selected rows
function highlightSelected() {
    rows.forEach(tr => {
        if (selectedRows.has(tr.dataset.row)) tr.classList.add('selected');
        else tr.classList.remove('selected');
    });
}

// Delete selected rows with Delete key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Delete' && selectedRows.size > 0) {
        if (!confirm('Delete selected rows?')) return;

        Array.from(selectedRows).forEach(row => {
            fetch(`/staff/delete/${row}`, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'}
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    const tr = document.querySelector(`#staffTable tr[data-row="${row}"]`);
                    if (tr) tr.remove();
                    selectedRows.delete(row);
                }
            });
        });
    }
});

</script>

@endsection

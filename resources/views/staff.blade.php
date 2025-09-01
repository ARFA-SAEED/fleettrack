@extends('layouts.app')

@section('content')
    <h2>Staff List</h2>

    <input type="text" id="staffSearch" class="search-input" placeholder="Search Staff...">

    <select id="statusFilter">
        <option value="">All Status</option>
        <option value="Active">Active</option>
        <option value="Expired">Expired</option>
        <option value="Time to Renew">Time to Renew</option>
    </select>

    <!-- Loader -->
    <div id="loader" style="display:none; text-align:center; margin:10px;">
        <div class="spinner"></div>
    </div>

    <table id="staffTable">
        <tr>
            <th></th>
            <th>#</th>
            <th>Name</th>
            <th>ID No.</th>
            <th>Work Order No</th>
            <th>Client</th>
            <th>Expiry Date</th>
            <th>Lead Time</th>
            <th>Days Left</th>
            <th>Status</th>
        </tr>

        <tbody id="staffRows">
            @include('partials.staff-rows', ['staff' => $staff])
        </tbody>
    </table>

    <!-- Spinner CSS -->
    <style>
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            animation: spin 1s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .selected-row {
            background-color: #d1e7fd;
        }

        .th-resizer {
            width: 5px;
            cursor: col-resize;
            position: absolute;
            right: 0;
            top: 0;
            height: 100%;
            user-select: none;
        }
    </style>

    <script>
        const updateUrl = '/staff/inline-update';
        let selectedRows = new Set();
        let isSelecting = false;

        // ====== Main Initialization ======
        function initStaffTable() {
            enableInlineAndDatePicker('staffTable');
            attachRowSelection();
            applyFilters();
            makeColumnsResizable('staffTable');
            autoRefreshTable('staffTable');
        }
        initStaffTable();

        // ====== Inline Editing + Date Picker ======
        function enableInlineAndDatePicker(tableId) {
            const table = document.getElementById(tableId);
            const loader = document.getElementById('loader');

            // ===== Text Editable Cells =====
            table.querySelectorAll('td.editable').forEach(cell => {
                if (cell.dataset.attached) return;
                cell.dataset.attached = true;

                // Arrow Key Navigation
                cell.addEventListener('keydown', function (e) {
                    const td = this;
                    const tr = td.parentElement;
                    let nextCell;
                    if (e.key === 'ArrowRight') nextCell = td.nextElementSibling;
                    else if (e.key === 'ArrowLeft') nextCell = td.previousElementSibling;
                    else if (e.key === 'ArrowDown') nextCell = tr.nextElementSibling?.children[td.cellIndex];
                    else if (e.key === 'ArrowUp') nextCell = tr.previousElementSibling?.children[td.cellIndex];
                    else if (e.key === 'Enter') {
                        e.preventDefault();
                        nextCell = tr.nextElementSibling?.children[td.cellIndex];
                    }
                    if (nextCell) nextCell.focus();
                });

                // Blur event to save changes
                cell.addEventListener('blur', function () {
                    const rowId = this.parentElement.dataset.row; // row number
                    const col = this.dataset.col;                // column letter
                    const value = this.innerText.trim();
                    if (!rowId || !col) return;

                    loader.style.display = 'block'; // show loader

                    fetch(`${updateUrl}/${rowId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                            body: JSON.stringify({ col, value })
                    })
                        .then(res => res.json())
                        .then(data => {
                            loader.style.display = 'none'; // hide loader
                            if (!data.success) console.error('Failed to save');
                        })
                        .catch(err => {
                            loader.style.display = 'none';
                            console.error(err);
                            alert('Error saving cell');
                        });
                });
            });

            // ===== Expiry / Date Cells =====
            table.querySelectorAll('td.expiry-cell').forEach(cell => {
                if (cell.dataset.attached) return;
                cell.dataset.attached = true;

                cell.addEventListener('click', function () {
                    if (cell.querySelector('input')) return; // already open
                    const currentValue = cell.dataset.value || cell.innerText;
                    const input = document.createElement('input');
                    input.type = 'date';
                    input.value = currentValue;
                    input.style.width = '100%';
                    cell.innerHTML = '';
                    cell.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', () => {
                        const rowId = cell.parentElement.dataset.row;
                        const col = cell.dataset.col;
                        if (!rowId || !col) return;

                        loader.style.display = 'block'; // show loader

                        fetch(`${updateUrl}/${rowId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ col, value: input.value })
                        })
                            .then(res => res.json())
                            .then(data => {
                                loader.style.display = 'none'; // hide loader
                                if (data.success) {
                                    cell.innerText = input.value;
                                    cell.dataset.value = input.value;
                                } else {
                                    cell.innerText = cell.dataset.value;
                                    alert('Failed to save date');
                                }
                            })
                            .catch(err => {
                                loader.style.display = 'none';
                                console.error(err);
                                cell.innerText = cell.dataset.value;
                                alert('Error saving date');
                            });
                    });

                    input.addEventListener('keydown', (e) => {
                        if (e.key === 'Enter') { e.preventDefault(); input.blur(); }
                    });
                });
            });
        }

        function saveDate(cell, newValue) {
            const rowId = cell.parentElement.dataset.row;
            const col = cell.dataset.col;

            fetch(`${updateUrl}/${rowId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ col, value: newValue })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        cell.innerText = newValue;
                        cell.dataset.value = newValue;
                    } else {
                        cell.innerText = cell.dataset.value;
                        alert('Failed to save date');
                    }
                });
        }

        // ====== Auto-Refresh ======
        function autoRefreshTable(tableId) {
            setInterval(async () => {
                const table = document.getElementById(tableId);
                const totalRow = table.querySelector('.total-row');

                const res = await fetch('/staff/ajax?start=1');
                const html = await res.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTable = doc.querySelector(`#${tableId}`);
                if (!newTable) return;

                const newRows = Array.from(newTable.querySelectorAll('tr[data-row]'));

                newRows.forEach(newRow => {
                    const rowId = newRow.dataset.row;
                    const existingRow = table.querySelector(`tr[data-row="${rowId}"]`);

                    if (existingRow) {
                        Array.from(newRow.cells).forEach((newCell, idx) => {
                            const existingCell = existingRow.cells[idx];
                            const active = document.activeElement;
                            if (active !== existingCell && !existingCell.querySelector('input')) {
                                existingCell.innerText = newCell.innerText;
                                if (existingCell.classList.contains('expiry-cell')) {
                                    existingCell.dataset.value = newCell.innerText;
                                }
                            }
                        });
                    } else {
                        totalRow.parentNode.insertBefore(newRow, totalRow);
                    }
                });

                // Remove missing rows
                const currentRows = Array.from(table.querySelectorAll('tr[data-row]'));
                currentRows.forEach(row => {
                    if (!newRows.find(r => r.dataset.row === row.dataset.row)) row.remove();
                });

                document.getElementById('totalStaffCount').innerText = newRows.length;

                enableInlineAndDatePicker(tableId);
                attachRowSelection();
                applyFilters();
            }, 1000);
        }

        // ====== Row Selection & Deletion ======
        function attachRowSelection() {
            const table = document.getElementById('staffTable');
            const rows = Array.from(table.querySelectorAll('tr[data-row]'));

            rows.forEach(tr => {
                const selectorCell = tr.querySelector('.select-cell');
                if (!selectorCell || selectorCell.dataset.attached) return;
                selectorCell.dataset.attached = true;

                // Single click: select row
                selectorCell.addEventListener('click', () => {
                    selectedRows.forEach(rowId => {
                        const oldTr = table.querySelector(`tr[data-row="${rowId}"]`);
                        if (oldTr) oldTr.classList.remove('selected-row');
                    });
                    selectedRows.clear();
                    selectedRows.add(tr.dataset.row);
                    tr.classList.add('selected-row');
                });

                // Drag selection
                selectorCell.addEventListener('pointerdown', e => {
                    isSelecting = true;
                    selectedRows.clear();
                    selectRow(tr);
                    e.preventDefault();
                });

                selectorCell.addEventListener('pointerenter', e => {
                    if (isSelecting) selectRow(tr);
                });
            });

            document.addEventListener('pointerup', () => { isSelecting = false; });
        }

        function selectRow(tr) {
            const rowId = tr.dataset.row;
            if (!selectedRows.has(rowId)) {
                selectedRows.add(rowId);
                tr.classList.add('selected-row');
            }
        }

        // Delete with Delete key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Delete' && selectedRows.size > 0) {
                e.preventDefault();
                if (!confirm('Delete selected rows?')) return;
                const table = document.getElementById('staffTable');
                selectedRows.forEach(rowId => {
                    fetch(`/staff/delete/${rowId}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                const tr = table.querySelector(`tr[data-row="${rowId}"]`);
                                if (tr) tr.remove();
                                selectedRows.delete(rowId);
                                document.getElementById('totalStaffCount').innerText = table.querySelectorAll('tr[data-row]').length;
                            }
                        });
                });
            }
        });

        // Deselect rows when clicking outside table
        document.addEventListener('click', function (e) {
            const table = document.getElementById('staffTable');
            if (!table.contains(e.target)) {
                selectedRows.forEach(rowId => {
                    const tr = table.querySelector(`tr[data-row="${rowId}"]`);
                    if (tr) tr.classList.remove('selected-row');
                });
                selectedRows.clear();
            }
        });

        // ====== Live Search + Status Filter with Loader ======
        function applyFilters() {
            const loader = document.getElementById('loader');
            loader.style.display = 'block'; // Show loader

            setTimeout(() => {
                const searchInput = document.getElementById('staffSearch').value.toLowerCase();
                const statusFilter = document.getElementById('statusFilter').value;
                const table = document.getElementById('staffTable');
                const rows = Array.from(table.querySelectorAll('tr[data-row]'));

                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    let matchesSearch = false;

                    for (let i = 1; i < cells.length; i++) {
                        if (cells[i].innerText.toLowerCase().includes(searchInput)) {
                            matchesSearch = true;
                            loader.style.display = 'none'; // Hide loader
                            break;
                        }
                    }

                    const statusCell = row.querySelector('td:last-child');
                    const matchesStatus = !statusFilter || (statusCell && statusCell.innerText === statusFilter);

                    row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });


            }, 10); // Small delay to show loader
        }

        document.getElementById('statusFilter').addEventListener('change', applyFilters);
        document.getElementById('staffSearch').addEventListener('keyup', applyFilters);

        // ====== Column Resizing ======
        function makeColumnsResizable(tableId) {
            const table = document.getElementById(tableId);
            const ths = table.querySelectorAll('th');

            ths.forEach(th => {
                const resizer = document.createElement('div');
                resizer.classList.add('th-resizer');
                th.appendChild(resizer);

                let startX, startWidth;

                resizer.addEventListener('mousedown', function (e) {
                    startX = e.pageX;
                    startWidth = th.offsetWidth;
                    document.addEventListener('mousemove', resizeColumn);
                    document.addEventListener('mouseup', stopResize);
                    e.preventDefault();
                });

                function resizeColumn(e) {
                    const newWidth = startWidth + (e.pageX - startX);
                    th.style.width = newWidth + 'px';
                }

                function stopResize() {
                    document.removeEventListener('mousemove', resizeColumn);
                    document.removeEventListener('mouseup', stopResize);
                }
            });
        }
    </script>
@endsection
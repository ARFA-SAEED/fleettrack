@extends('layouts.app')

@section('content')
    <div class="staff-container">

        <div class="controls">
            <input type="text" id="staffSearch" class="search-input" placeholder="Search Staff...">

            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Expired">Expired</option>
                <option value="Time to Renew">Time to Renew</option>
            </select>
        </div>

        <!-- Loader -->
        <div id="loader" style="display:none; text-align:center; margin:10px;">
            <div class="spinner"></div>
            <p class="loading-text">Loading...</p>
        </div>

        <div class="table-wrapper">
            <table id="staffTable" class="styled-table">
                <thead>
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
                </thead>

                <tbody id="staffRows">
                    @include('partials.staff-rows', ['staff' => $staff])
                </tbody>
            </table>
        </div>
    </div>

    <!-- Styling -->
    <style>
        .staff-container {
            margin: 20px auto;
            padding: 15px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        }

        .controls {
            display: flex;
            justify-content: start;
            gap: 15px;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .search-input,
        .filter-select {
            height: 48px;
            /* Same fixed height */
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.2s;
        }

        /* Specific tweaks */
        .search-input {
            padding: 0 50px;
            /* Just horizontal padding */
        }

        .filter-select {
            padding: 0 40px 0 16px;
            /* Left padding for text, right padding for arrow */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #fff;
            background-image: url("data:image/svg+xml;utf8,<svg fill='%233498db' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            cursor: pointer;
        }

        /* For better look on focus */
        .filter-select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.4);
        }


        .search-input:focus,
        .filter-select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.4);
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .styled-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            border-radius: 10px;
            overflow: hidden;
        }

        .styled-table thead {
            background: #3498db;
            color: #000000;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            position: relative;
        }

        .styled-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .styled-table tr:hover {
            background: #eef6ff;
        }

        .selected-row {
            background-color: #d1e7fd !important;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            animation: spin 1s linear in finite;
            margin: auto;
        }

        .loading-text {
            font-size: 14px;
            color: #555;
            margin-top: 6px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

        let selectedRows = new Set();
        let isSelecting = false;

        // ====== Main Initialization ======
        function initStaffTable() {
            applyFilters();
            makeColumnsResizable('staffTable');
            autoRefreshTable('staffTable');
        }
        initStaffTable();





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
@extends('layouts.app')

@section('content')
    <div class="Vehicle-container">

        <div class="controls">
            <input type="text" id="vehicleSearch" class="search-input" placeholder="Search Vehicle...">

            <select id="statusFilterCol10" class="filter-select">
                <option value="">Gate Pass All Status</option>
                <option value="Active">Gate Pass Active</option>
                <option value="Expired">Gate Pass Expired</option>
                <option value="Time to Renew">Gate Pass Time to Renew</option>
            </select>

            <select id="statusFilterCol14" class="filter-select">
                <option value="">RC - Registration All Status</option>
                <option value="Active">RC - Registration Active</option>
                <option value="Expired">RC - Registration Expired</option>
                <option value="Time to Renew">RC - Registration Time to Renew</option>
            </select>

            <select id="statusFilterCol18" class="filter-select">
                <option value="">Insurance All Status</option>
                <option value="Active">Insurance Active</option>
                <option value="Expired">Insurance Expired</option>
                <option value="Time to Renew">Insurance Time to Renew</option>
            </select>


            <select id="statusFilterCol22" class="filter-select">
                <option value="">Pollution All Status</option>
                <option value="Active">Pollution Active</option>
                <option value="Expired">Pollution Expired</option>
                <option value="Time to Renew">Pollution Time to Renew</option>
            </select>


            <select id="statusFilterCol26" class="filter-select">
                <option value="">Tax All Status</option>
                <option value="Active">Tax Active</option>
                <option value="Expired">Tax Expired</option>
                <option value="Time to Renew">Tax Time to Renew</option>
            </select>
        </div>

        <!-- Loader -->
        <div id="loader" style="display:none; text-align:center; margin:10px;">
            <div class="spinner"></div>
            <p class="loading-text">Loading...</p>
        </div>

        <div class="table-wrapper">
            <table id="vehicleTable" class="styled-table">
                <thead>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Vehicle Make</th>
                        <th>Vehicle No</th>
                        <th>Driver Name 1</th>
                        <th>Driver Name 2</th>
                        <th>Work Order No</th>
                        <th>Client</th>
                        <th colspan="4">Gate Pass</th>
                        <th colspan="4">RC - Registration</th>
                        <th colspan="4">Insurance</th>
                        <th colspan="4">Pollution</th>
                        <th colspan="4">Tax</th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Expiry Date</th>
                        <th>Lead Time</th>
                        <th>Days Left</th>
                        <th>Status</th>

                        <th>Expiry Date</th>
                        <th>Lead Time</th>
                        <th>Days Left</th>
                        <th>Status</th>

                        <th>Expiry Date</th>
                        <th>Lead Time</th>
                        <th>Days Left</th>
                        <th>Status</th>

                        <th>Expiry Date</th>
                        <th>Lead Time</th>
                        <th>Days Left</th>
                        <th>Status</th>

                        <th>Expiry Date</th>
                        <th>Lead Time</th>
                        <th>Days Left</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody id="vehicleRows">
                    @include('partials.vehicle-rows', ['vehicle' => $vehicles])
                </tbody>
            </table>
        </div>
    </div>

    <!-- Styling -->
    <style>
        .Vehicle-container {
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
            width: 100%;
            overflow-x: auto;
            /* Enables horizontal scroll */
            -webkit-overflow-scrolling: touch;
            /* Smooth scroll on mobile */
            border-radius: 10px;
        }

        .styled-table {
            width: max-content;
            /* Table expands based on content */
            min-width: 1600px;
            /* Force wider table */
            border-collapse: collapse;
        }


        .styled-table thead {
            background: #3498db;
            color: #000000;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            text-align: center;
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
            animation: spin 1s linear infinite;
            /* ✅ correct */
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
        function initvehicleTable() {
            applyFilters();
            makeColumnsResizable('vehicleTable');
            autoRefreshTable('vehicleTable');
        }
        initvehicleTable();





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




        // ====== Live Search + Multi-Column Filters with Loader ======
        // ====== Live Search + Multi-Column Filters (resilient) ======
        function applyFilters() {
            const loader = document.getElementById('loader');
            if (loader) loader.style.display = 'block';

            // Small delay lets the loader paint
            setTimeout(() => {
                const normalize = s => (s ?? '').toString().trim().toLowerCase().replace(/\s+/g, ' ');

                const searchInput = normalize(document.getElementById('vehicleSearch')?.value || '');
                const table = document.getElementById('vehicleTable');

                if (!table) {
                    console.warn('applyFilters: #vehicleTable not found');
                    if (loader) loader.style.display = 'none';
                    return;
                }

                // Prefer tbody rows to avoid header
                const rows = Array.from(table.querySelectorAll('tbody tr'));

                // Map of (0-based) column index → select ID
                const filterDefs = [
                    { idx: 11, id: 'statusFilterCol10' }, // 10th visible column
                    { idx: 15, id: 'statusFilterCol14' }, // 14th visible column
                    { idx: 19, id: 'statusFilterCol18' },
                    { idx: 23, id: 'statusFilterCol22' },
                    { idx: 27, id: 'statusFilterCol26' },
                ];

                // Collect only filters that have a non-empty value
                const activeFilters = filterDefs
                    .map(f => ({ ...f, value: normalize(document.getElementById(f.id)?.value || '') }))
                    .filter(f => f.value !== ''); // only apply set filters

                rows.forEach(row => {
                    const cells = row.children; // faster & simpler than querySelectorAll('td')

                    // 1) Text search across entire row
                    const matchesSearch =
                        !searchInput ||
                        Array.from(cells).some(td => normalize(td.textContent).includes(searchInput));

                    // 2) Column-specific filters (each applies only to its own column)
                    const matchesFilters = activeFilters.every(f => {
                        const cellText = normalize(cells[f.idx]?.textContent);
                        // Use includes — handles "Expired", "Expired soon", etc.
                        return cellText.includes(f.value);
                    });

                    row.style.display = (matchesSearch && matchesFilters) ? '' : 'none';
                });

                if (loader) loader.style.display = 'none';
            }, 10);
        }

        document.getElementById('statusFilterCol10').addEventListener('change', applyFilters);
        document.getElementById('statusFilterCol14').addEventListener('change', applyFilters);
        document.getElementById('statusFilterCol18').addEventListener('change', applyFilters);
        document.getElementById('statusFilterCol22').addEventListener('change', applyFilters);
        document.getElementById('statusFilterCol26').addEventListener('change', applyFilters);


        document.getElementById('vehicleSearch').addEventListener('keyup', applyFilters);

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
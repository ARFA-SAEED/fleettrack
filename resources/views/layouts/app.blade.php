<!DOCTYPE html>
<html>
<head>
    <title>Vehicle & Staff Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        nav a { margin-right: 15px; text-decoration:none; color:#007bff; }
        nav a:hover { text-decoration:underline; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; }
        th, td { border: 1px solid #ccc; padding: 6px 10px; text-align:left; overflow:hidden; white-space:nowrap; }
        tr:hover td { background-color:#e6f2ff; }
        th { background-color:#f1f1f1; cursor:pointer; user-select:none; }
        .status-active { background-color:#d4edda; }
        .status-inactive { background-color:#f8d7da; }
        .search-input { margin-bottom:10px; padding:5px; width:200px; }
        .total-row { font-weight:bold; background-color:#ffeeba; }
        button { padding:5px 10px; margin-top:10px; }
        th {
    position: relative;
    user-select: none;
}
.th-resizer {
    position: absolute;
    right: 0;
    top: 0;
    width: 5px;
    cursor: col-resize;
    user-select: none;
    height: 100%;
}

.select-cell {
    width: 20px;
    cursor: pointer;
    background-color: #f5f5f5;
    user-select: none;
}

tr.selected {
    background-color: #cce5ff; /* highlight selected rows */
}
.select-cell {
    width: 20px;
    cursor: pointer;
    background-color: #f5f5f5;
    user-select: none;
}
#staffTable th:first-child,
#staffTable td.select-cell {
    width: 30px; /* adjust as needed */
    text-align: center;
    padding: 5px;
}

/* Highlight selected rows */
.selected-row {
    background-color: #d0e7ff;
}
  </style>
</head>
<body>

<nav>
    <a href="{{ route('dashboard') }}">Dashboard</a>
    <a href="{{ route('vehicles.view') }}">Vehicles</a>
    <a href="{{ route('staff.view') }}">Staff</a>
</nav>

<hr>

@yield('content')

</body>
</html>

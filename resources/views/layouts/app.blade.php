<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Vehicle & Staff Management' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            #printArea {
                display: block !important;
            }

            .print-card {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        #printArea {
            display: none;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px 10px;
            text-align: left;
            overflow: hidden;
            white-space: nowrap;
        }

        tr:hover td {
            background-color: #e6f2ff;
        }

        th {
            background-color: #f1f1f1;
            cursor: pointer;
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

        .status-active {
            background-color: #d4edda;
        }

        .status-inactive {
            background-color: #f8d7da;
        }

        .total-row {
            font-weight: bold;
            background-color: #ffeeba;
        }

        .select-cell {
            width: 20px;
            cursor: pointer;
            background-color: #f5f5f5;
            user-select: none;
        }

        tr.selected {
            background-color: #cce5ff;
        }

        #staffTable th:first-child,
        #staffTable td.select-cell {
            width: 30px;
            text-align: center;
            padding: 5px;
        }

        .selected-row {
            background-color: #d0e7ff;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen">
    <header
        class="relative flex items-center justify-between px-6 py-4 bg-[var(--header-bg)] border-b border-[var(--border-color)] no-print">
        <div class="flex items-center gap-3">
            <div class="size-6 text-[var(--primary-color)]">
                <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M24 4H6V17.3333V30.6667H24V44H42V30.6667V17.3333H24V4Z"
                        fill="currentColor" fill-rule="evenodd" />
                </svg>
            </div>
            <h1 class="text-xl font-bold tracking-tight">FleetTrack</h1>
        </div>

        <!-- Centered Navigation -->
        <nav class="absolute left-1/2 -translate-x-1/2 flex items-center gap-6">
            <a class="text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-[var(--primary-color)]' : 'text-[var(--text-secondary)] hover:text-[var(--primary-color)]' }}"
                href="{{ route('dashboard') }}">Dashboard</a>




            <a class="text-sm font-medium {{ request()->routeIs('vehicles.*') ? 'text-[var(--primary-color)]' : 'text-[var(--text-secondary)] hover:text-[var(--primary-color)]' }}"
                href="{{ route('vehicles.view') }}">Vehicles</a>



            <a class="text-sm font-medium {{ request()->routeIs('staff.*') ? 'text-[var(--primary-color)]' : 'text-[var(--text-secondary)] hover:text-[var(--primary-color)]' }}"
                href="{{ route('staff.view') }}">Staff</a>

        </nav>

        <div class="flex items-center gap-6">
            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <div @click="open = !open"
                    class="cursor-pointer bg-gray-200 rounded-full size-10 flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A9 9 0 0112 15a9 9 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>

                <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 mt-2 bg-white rounded-md shadow-lg py-4 z-50 px-2" style="width: 200px;">

                    <a class="block py-2 px-2 hover:bg-gray-100 rounded"
                        href="{{ route('settings.index') }}">Settings</a>

                    <a class="block py-2 px-2 hover:bg-gray-100 rounded" href="/admin/password">Change password</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left text-red-600 py-2 px-2 hover:bg-gray-100 rounded cursor-pointer">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto p-4 bg-white no-print">
        @yield('content')
    </main>

    <div>
        @yield('print_area')
    </div>

    @stack('scripts')
</body>

</html>
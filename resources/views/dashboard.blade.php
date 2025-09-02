@extends('layouts.app')

@section('content')

    <main class="container mx-auto flex-1 px-4 py-8 sm:px-6 lg:px-8 ">
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-3xl font-bold tracking-tight text-[var(--text-primary)]">Dashboard</h2>
                    <p class="mt-1 text-sm text-[var(--text-secondary)]">Comprehensive overview of your vehicle fleet and
                        document statuses.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="space-y-2">
                <span class="text-2xl font-bold">Vehicles Summary</span>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 border border-gray-300 p-2">
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm rounded cursor-pointer">
                        <p class="text-sm font-medium text-green-600">Total Vehicles</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $totalVehicles }}</p>
                    </div>

                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-blue-600">Expired Documents</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $expiredVehicles }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-yellow-600">Active Documents</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $ActiveVehicles }}</p>
                    </div>
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-red-600">Time to Renew Documents</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $TimetorenewVehicles }}</p>
                    </div>

                </div>
            </div>
            <div class="space-y-2">

                <span class="text-2xl font-bold">Staff Summary</span>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 border border-gray-300 p-2 rounded">
                    <!-- Staff 1 & 2 -->
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-green-600">Total Staff</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $totalStaff }}</p>
                        <!-- <p class="mt-1 text-sm font-medium text-green-600">+2% from last month</p> -->
                    </div>
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-yellow-600">Active</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $ActiveStaff }}</p>
                        <!-- <p class="mt-1 text-sm font-medium text-yellow-600">+3% from last month</p> -->
                    </div>

                    <!-- Staff 3 & 4 -->
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-red-600">Expired</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $expiredStaff }}</p>
                        <!-- <p class="mt-1 text-sm font-medium text-red-600">+2% from last month</p> -->
                    </div>
                    <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm cursor-pointer">
                        <p class="text-sm font-medium text-blue-600">Renew</p>
                        <p class="mt-1 text-3xl font-bold text-[var(--text-primary)]">{{ $timetorenewStaff }}</p>
                        <!-- <p class="mt-1 text-sm font-medium text-blue-600">+3% from last month</p> -->
                    </div>
                </div>
            </div>
        </div>

        @php
            $total = max($ActiveVehicles + $TimetorenewVehicles + $expiredVehicles, 1); // avoid div/0

            $validPercent = ($ActiveVehicles / $total) * 100;
            $expiringPercent = ($TimetorenewVehicles / $total) * 100;
            $expiredPercent = ($expiredVehicles / $total) * 100;
        @endphp

        <!-- Document Status Overview -->
        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm lg:col-span-1">
                <h3 class="text-lg font-semibold leading-6 text-[var(--text-primary)] mb-4">Document Status</h3>
                <div class="flex items-center justify-center">
                    <div class="relative w-48 h-48">
                        <svg class="w-full h-full rotate-[-90deg]" viewBox="0 0 36 36">

                            {{-- Valid --}}
                            <path class="text-green-500"
                                d="M18 2.0845 
                                                                                                                                                                                                                                                                                                                                                                                       a 15.9155 15.9155 0 1 1 0 31.831 
                                                                                                                                                                                                                                                                                                                                                                                       a 15.9155 15.9155 0 1 1 0 -31.831"
                                fill="none" stroke="currentColor" stroke-dasharray="{{ $validPercent }}, 100"
                                stroke-width="3.8">
                            </path>

                            {{-- Expiring Soon --}}
                            <path class="text-yellow-400"
                                d="M18 2.0845 
                                                                                                                                                                                                                                                                                                                                                                                       a 15.9155 15.9155 0 1 1 0 31.831 
                                                                                                                                                                                                                                                                                                                                                                                       a 15.9155 15.9155 0 1 1 0 -31.831"
                                fill="none" stroke="currentColor" stroke-dasharray="{{ $expiringPercent }}, 100"
                                stroke-dashoffset="-{{ $validPercent }}" stroke-width="3.8">
                            </path>

                            {{-- Expired --}}
                            <path class="text-red-600"
                                d="M18 2.0845 
                                                                                                                                                                                                                                                                                                                                                                                       a 15.9155 15.9155 0 1 1 0 31.831 
                                                                                                                                                                                                                                                                                                                                                                                       a 15.9155 15.9155 0 1 1 0 -31.831"
                                fill="none" stroke="currentColor" stroke-dasharray="{{ $expiredPercent }}, 100"
                                stroke-dashoffset="-{{ $validPercent + $expiringPercent }}" stroke-width="3.8">
                            </path>
                        </svg>




                        {{-- Labels --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-2xl font-bold">{{ $totalVehicles }}</span>
                            <span class="text-sm text-[var(--text-secondary)]">Total Documents</span>
                        </div>
                    </div>

                </div>
                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                            <span class="text-sm font-medium">Valid</span>
                        </div>
                        <span class="text-sm font-semibold">{{ $ActiveVehicles }}
                            ({{ number_format(($ActiveVehicles / max($totalVehicles, 1)) * 100, 1) }}%)
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-yellow-400 mr-2"></span>
                            <span class="text-sm font-medium">Expiring Soon</span>
                        </div>
                        <span class="text-sm font-semibold">{{ $TimetorenewVehicles }}
                            ({{ number_format(($TimetorenewVehicles / max($totalVehicles, 1)) * 100, 1) }}%)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="w-3 h-3 rounded-full bg-red-600 mr-2"></span>
                            <span class="text-sm font-medium">Expired</span>
                        </div>
                        <span class="text-sm font-semibold">{{ $expiredVehicles }}
                            ({{ number_format(($expiredVehicles / max($totalVehicles, 1)) * 100, 1) }}%)</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-300 bg-white p-6 shadow-sm lg:col-span-2">
                <h3 class="text-lg font-semibold leading-6 text-[var(--text-primary)] mb-4">Expiry Distribution by
                    Document Type</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach ($vehicleStats as $Vtype)
                        <div>
                            <h4 class="text-md font-medium mb-3">{{ $Vtype['type'] }}</h4>
                            <div class="space-y-3">
                                {{-- Expiring Soon --}}
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <p class="text-sm font-medium text-[var(--text-secondary)]">Expiring Soon</p>
                                        <p class="text-sm font-medium text-yellow-600">{{ $Vtype['statusexpiringsoon'] }}</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-yellow-400 h-2.5 rounded-full"
                                            style="width: {{ $Vtype['pctExpiringSoon'] }}%"></div>
                                    </div>
                                </div>

                                {{-- Expired --}}
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <p class="text-sm font-medium text-[var(--text-secondary)]">Expired</p>
                                        <p class="text-sm font-medium text-red-600">{{ $Vtype['statusexpired'] }}</p>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-red-600 h-2.5 rounded-full" style="width: {{ $Vtype['pctExpired'] }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach



                </div>
            </div>
        </div>


    </main>

@endsection
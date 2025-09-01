@extends('layouts.app')

@section('content')

<h2>Dashboard</h2>

<div style="display:flex; gap:40px;">
    <div style="width:300px; text-align:center;">
        <h3>Staff</h3>
        <canvas id="staffChart"></canvas>
        <p>Total Staff: {{ $totalStaff }}</p>
    </div>
    <div style="width:300px; text-align:center;">
        <h3>Vehicles</h3>
        <canvas id="vehicleChart"></canvas>
        <p>Total Vehicles: {{ $totalVehicles }}</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const staffCtx = document.getElementById('staffChart').getContext('2d');
const staffChart = new Chart(staffCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active','Inactive'],
        datasets:[{
            data: [{{ $activeStaff }}, {{ $inactiveStaff }}],
            backgroundColor: ['#28a745','#dc3545']
        }]
    },
    options:{ responsive:true }
});

const vehicleCtx = document.getElementById('vehicleChart').getContext('2d');
const vehicleChart = new Chart(vehicleCtx, {
    type: 'doughnut',
    data: {
        labels: ['Active','Inactive'],
        datasets:[{
            data: [{{ $activeVehicles }}, {{ $inactiveVehicles }}],
            backgroundColor: ['#28a745','#dc3545']
        }]
    },
    options:{ responsive:true }
});
</script>

@endsection

@extends('layouts.admin.master')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4 content" style="background-color: white;">
    <div class="dashboard-title"><h1>Thống kê</h1></div><hr>
    <div class="mb-3 d-flex gap-2">
        <button class="btn btn-outline-danger" onclick="window.print()">
            <i class="bi bi-file-earmark-pdf"></i> Xuất PDF
        </button>
        <button class="btn btn-outline-success" onclick="exportTableToExcel('statTable', 'BaoCaoThongKe')">
            <i class="bi bi-file-earmark-excel"></i> Xuất Excel
        </button>
    </div>
    <table class="table d-none" id="statTable">
        <thead>
            <tr>
                <th>Tiêu chí</th>
                <th>Giá trị</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Vé đã bán</td><td>{{ number_format($totalTickets ?? 0) }}</td></tr>
            <tr>
                <td>Doanh thu tháng ({{ $month }}/{{ $year }})</td>
                <td>{{ number_format($monthlyRevenue ?? 0) }} VND</td>
            </tr>
            <tr><td>Người dùng</td><td>{{ number_format($totalUsers ?? 0) }}</td></tr>
        </tbody>
    </table>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
        <div class="col">
            <div class="stat-card bg-blue">
                <i class="bi bi-ticket-perforated"></i>
                <div class="stat-content">
                    <h6>Vé đã bán</h6>
                    <h4>{{ number_format($totalTickets ?? 0) }}</h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card bg-green">
                <i class="bi bi-currency-dollar"></i>
                <div class="stat-content">
                    <h6>Doanh thu tháng ({{ $month }}/{{ $year }})</h6>
                    <h4>{{ number_format($monthlyRevenue ?? 0) }} VND</h4>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="stat-card bg-orange">
                <i class="bi bi-people"></i>
                <div class="stat-content">
                    <h6>Người dùng</h6>
                    <h4>{{ number_format($totalUsers ?? 0) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="chart-container">
                <h6 class="mb-3">Biểu đồ doanh thu theo tháng</h6>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="chart-container">
                <h6 class="mb-3">
                    Tỷ lệ đặt vé theo phim ({{ $month }}/{{ $year }})
                </h6>
                <canvas id="movieChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script id="revenue-labels" type="application/json">
    {!! json_encode(array_keys($revenueByMonth ?? [])) !!}
</script>
<script id="revenue-data" type="application/json">
    {!! json_encode(array_values($revenueByMonth ?? [])) !!}
</script>
<script id="movie-labels" type="application/json">
    {!! json_encode(array_keys($ticketsByMovie ?? [])) !!}
</script>
<script id="movie-data" type="application/json">
    {!! json_encode(array_values($ticketsByMovie ?? [])) !!}
</script>

<script>
    // Lấy dữ liệu từ thẻ script JSON
    var revenueLabels = JSON.parse(document.getElementById('revenue-labels').textContent);
    var revenueData = JSON.parse(document.getElementById('revenue-data').textContent);
    var movieLabels = JSON.parse(document.getElementById('movie-labels').textContent);
    var movieData = JSON.parse(document.getElementById('movie-data').textContent);

    function exportTableToExcel(tableID, filename = ''){
        var wb = XLSX.utils.table_to_book(document.getElementById(tableID), {sheet:"Sheet 1"});
        return XLSX.writeFile(wb, filename ? filename+'.xlsx' : 'excel-data.xlsx');
    }

    // Biểu đồ doanh thu theo tháng
    new Chart(document.getElementById("revenueChart"), {
        type: 'line',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Doanh thu (triệu)',
                data: revenueData,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0,123,255,0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Biểu đồ tỷ lệ đặt vé theo phim
    new Chart(document.getElementById("movieChart"), {
        type: 'doughnut',
        data: {
            labels: movieLabels,
            datasets: [{
                data: movieData,
                backgroundColor: ['#6610f2', '#fd7e14', '#20c997', '#ffc107', '#dc3545'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
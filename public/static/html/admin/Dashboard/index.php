<?php 
    $activePage ='dashboard';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/index.css" rel="stylesheet">
</head>

<body>
    <div class="admin-layout">
        <?php include __DIR__ . '/../../../layouts/admin/Sidebar.php'; ?>
        <div class="main-content">
            <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>

            <main>
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
                            <tr><td>Vé đã bán</td><td>12,540</td></tr>
                            <tr><td>Doanh thu tháng</td><td>100.000.000 VND</td></tr>
                            <tr><td>Người dùng</td><td>8,120</td></tr>
                        </tbody>
                    </table>
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 mb-4">
                        <div class="col">
                            <div class="stat-card bg-blue">
                                <i class="bi bi-ticket-perforated"></i>
                                <div class="stat-content">
                                    <h6>Vé đã bán</h6>
                                    <h4>12,540</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card bg-green">
                                <i class="bi bi-currency-dollar"></i>
                                <div class="stat-content">
                                    <h6>Doanh thu tháng</h6>
                                    <h4>100.000.000 VND</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card bg-orange">
                                <i class="bi bi-people"></i>
                                <div class="stat-content">
                                    <h6>Người dùng</h6>
                                    <h4>8,120</h4>
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
                                <h6 class="mb-3">Tỷ lệ đặt vé theo phim</h6>
                                <canvas id="movieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!--Xuất excel-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        function exportTableToExcel(tableID, filename = ''){
            var wb = XLSX.utils.table_to_book(document.getElementById(tableID), {sheet:"Sheet 1"});
            return XLSX.writeFile(wb, filename ? filename+'.xlsx' : 'excel-data.xlsx');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById("revenueChart"), {
        type: 'line',
        data: {
            labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
            datasets: [{
            label: 'Doanh thu (triệu)',
            data: [320, 420, 580, 440, 690, 510],
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

        new Chart(document.getElementById("movieChart"), {
        type: 'doughnut',
        data: {
            labels: ['Endgame', 'Spider-Man', 'Minions'],
            datasets: [{
            data: [2430, 1980, 1510],
            backgroundColor: ['#6610f2', '#fd7e14', '#20c997'],
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
</body>
</html>

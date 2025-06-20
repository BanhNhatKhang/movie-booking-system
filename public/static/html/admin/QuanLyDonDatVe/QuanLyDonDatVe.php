<?php
    $activePage = 'admin-orders';
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
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .topbar {
            background-color: #f8f9fa;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            display: flex;
            align-items: center;
        }
        .user-info i {
            margin-right: 8px;
        }
        .content {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
            background-color: #f1f3f5;
        }
        .table-responsive {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
        }
        .status-paid { background: #27ae60; color: #fff; }
        .status-unpaid { background: #f1c40f; color: #222; }
        .status-cancelled { background: #bdbdbd; color: #222; }
        .action-btn { margin-right: 6px; }
        .table th, .table td { vertical-align: middle; }
    </style>
</head>
<body>
    <div class="admin-layout">
        <?php include __DIR__ . '/../../../layouts/admin/Sidebar.php'; ?>
        <div class="main-content">
        <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>
            <main>
                <div class="container py-4 content" style="background-color: white;">
                    <h1>Quản lý Đơn đặt vé</h1><hr>
                    <div>
                        <form class="row g-3 align-items-end filter-form mb-3" method="get">
                            <div class="col-md-3">
                                <label class="form-label">Người dùng</label>
                                <input type="text" class="form-control" name="user" placeholder="Tên hoặc email..." value="<?= isset($_GET['user']) ? htmlspecialchars($_GET['user']) : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Tên phim</label>
                                <input type="text" class="form-control" name="movie" placeholder="Nhập tên phim..." value="<?= isset($_GET['movie']) ? htmlspecialchars($_GET['movie']) : '' ?>">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Ngày đặt</label>
                                <input type="date" class="form-control" name="date" value="<?= isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '' ?>">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> Tra cứu</button>
                            </div>
                        </form>
                        <!-- Danh sách đơn đặt vé -->
                        <div class="table-responsive p-3">
                            <table class="table align-middle table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Mã vé</th>
                                        <th>Người dùng</th>
                                        <th>Tên phim</th>
                                        <th>Suất chiếu</th>
                                        <th>Ghế</th>
                                        <th>Giá</th>
                                        <th>Ngày đặt</th>
                                        <th>Thanh toán</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Dữ liệu mẫu, thay bằng dữ liệu thực tế từ DB
                                    $orders = [
                                        [
                                            'id'=>1001,
                                            'user'=>'Nguyễn Văn B',
                                            'movie'=>'Thanh Gươm Diệt Quỷ',
                                            'showtime'=>'08:30 25/06/2024',
                                            'seats'=>'G09, G10',
                                            'price'=>170000,
                                            'date'=>'2024-06-20',
                                            'status'=>'paid'
                                        ],
                                        [
                                            'id'=>1002,
                                            'user'=>'Trần Thị C',
                                            'movie'=>'Hành Trình Về Miền Đất Hứa',
                                            'showtime'=>'10:00 26/06/2024',
                                            'seats'=>'A01',
                                            'price'=>70000,
                                            'date'=>'2024-06-21',
                                            'status'=>'unpaid'
                                        ],
                                        [
                                            'id'=>1003,
                                            'user'=>'Lê Văn D',
                                            'movie'=>'Ký Ức Mùa Hè',
                                            'showtime'=>'14:00 22/06/2024',
                                            'seats'=>'B05, B06, B07',
                                            'price'=>210000,
                                            'date'=>'2024-06-19',
                                            'status'=>'cancelled'
                                        ],
                                    ];
                                    // Lọc dữ liệu nếu có tìm kiếm
                                    $user = isset($_GET['user']) ? mb_strtolower(trim($_GET['user'])) : '';
                                    $movie = isset($_GET['movie']) ? mb_strtolower(trim($_GET['movie'])) : '';
                                    $date = isset($_GET['date']) ? $_GET['date'] : '';
                                    $filtered = array_filter($orders, function($o) use ($user, $movie, $date) {
                                        $ok = true;
                                        if ($user && mb_strpos(mb_strtolower($o['user']), $user) === false) $ok = false;
                                        if ($movie && mb_strpos(mb_strtolower($o['movie']), $movie) === false) $ok = false;
                                        if ($date && $o['date'] != $date) $ok = false;
                                        return $ok;
                                    });
                                    foreach($filtered as $order):
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order['id']) ?></td>
                                        <td><?= htmlspecialchars($order['user']) ?></td>
                                        <td><?= htmlspecialchars($order['movie']) ?></td>
                                        <td><?= htmlspecialchars($order['showtime']) ?></td>
                                        <td><?= htmlspecialchars($order['seats']) ?></td>
                                        <td><?= number_format($order['price'],0,',','.') ?> VNĐ</td>
                                        <td><?= date('d/m/Y', strtotime($order['date'])) ?></td>
                                        <td>
                                            <?php
                                            if($order['status']=='paid') echo '<span class="badge status-paid">Đã thanh toán</span>';
                                            elseif($order['status']=='unpaid') echo '<span class="badge status-unpaid">Chưa thanh toán</span>';
                                            else echo '<span class="badge status-cancelled">Đã hủy</span>';
                                            ?>
                                        </td>
                                        <td>
                                            <a href="ChiTietDon.php?id=<?= $order['id'] ?>" class="btn btn-info btn-sm action-btn" title="Xem chi tiết"><i class="bi bi-eye"></i></a>
                                            <?php if($order['status']!='cancelled'): ?>
                                            <a href="HuyDon.php?id=<?= $order['id'] ?>" class="btn btn-danger btn-sm action-btn" title="Hủy vé" onclick="return confirm('Bạn chắc chắn muốn hủy vé này?')"><i class="bi bi-x-circle"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if(empty($filtered)): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">Không tìm thấy đơn đặt vé phù hợp.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
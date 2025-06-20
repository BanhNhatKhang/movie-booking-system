<?php
    $activePage = 'admin-orders';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn đặt vé</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyDonDatVe.css">
</head>
<body style="background: #f5f5f5;">
<div class="main-content">
    <div class="topbar">
        <div class="system-name">
            <strong>HỆ THỐNG QUẢN LÝ RẠP PHIM</strong>
        </div>
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            <span>Nguyễn Văn A</span>
        </div>
    </div>
    <h2 class="mb-4">Quản lý Đơn đặt vé</h2>
    <div class="content">
        <!-- Bộ lọc và tìm kiếm -->
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
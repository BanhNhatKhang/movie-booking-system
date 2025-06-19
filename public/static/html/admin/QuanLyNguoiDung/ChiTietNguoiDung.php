<?php
$activePage = 'NguoiDung';

// Dữ liệu mẫu người dùng
$users = [
    [
        'id'=>1,
        'email'=>'admin@khfcinema.vn',
        'name'=>'Admin KHF',
        'role'=>'admin',
        'status'=>'active',
        'history'=>[],
        'offers'=>[]
    ],
    [
        'id'=>2,
        'email'=>'nguyenvanb@gmail.com',
        'name'=>'Nguyễn Văn B',
        'role'=>'guest',
        'status'=>'active',
        'history'=>[
            [
                'movie'=>'Thanh Gươm Diệt Quỷ',
                'showtime'=>'08:30 25/06/2024',
                'seats'=>'G09, G10',
                'price'=>170000,
                'date'=>'2024-06-20',
                'status'=>'paid'
            ],
            [
                'movie'=>'Ký Ức Mùa Hè',
                'showtime'=>'14:00 22/06/2024',
                'seats'=>'B05',
                'price'=>70000,
                'date'=>'2024-06-10',
                'status'=>'cancelled'
            ]
        ],
        'offers'=>[
            ['name'=>'Giảm 10% vé 2D', 'used_date'=>'2024-06-10'],
            ['name'=>'Tặng bắp nước', 'used_date'=>'2024-06-20']
        ]
    ],
    [
        'id'=>3,
        'email'=>'lethic@gmail.com',
        'name'=>'Lê Thị C',
        'role'=>'guest',
        'status'=>'locked',
        'history'=>[],
        'offers'=>[]
    ],
];

// Lấy id từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Tìm người dùng theo id
$user = null;
foreach($users as $u) {
    if($u['id'] == $id) {
        $user = $u;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin người dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <style>
        .main-content { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px; }
        .user-label { font-weight: bold; color: #555; width: 140px; }
        .user-value { color: #222; }
        .status-admin { background: #e67e22; color: #fff; }
        .status-guest { background: #3498db; color: #fff; }
        .status-locked { background: #bdbdbd; color: #222; }
    </style>
</head>
<body style="background: #f5f5f5;">
<div class="main-content">
    <?php if($user): ?>
    <h3 class="mb-4">Thông tin người dùng</h3>
    <div class="mb-3 row">
        <div class="user-label col-sm-4">Email:</div>
        <div class="user-value col-sm-8"><?= htmlspecialchars($user['email']) ?></div>
    </div>
    <div class="mb-3 row">
        <div class="user-label col-sm-4">Tên:</div>
        <div class="user-value col-sm-8"><?= htmlspecialchars($user['name']) ?></div>
    </div>
    <div class="mb-3 row">
        <div class="user-label col-sm-4">Phân loại:</div>
        <div class="user-value col-sm-8">
            <?php
            if($user['role']=='admin') echo '<span class="badge status-admin">Admin</span>';
            else echo '<span class="badge status-guest">Khách</span>';
            ?>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="user-label col-sm-4">Trạng thái:</div>
        <div class="user-value col-sm-8">
            <?php
            if($user['status']=='locked') echo '<span class="badge status-locked">Đã khóa</span>';
            else echo '<span class="badge bg-success">Hoạt động</span>';
            ?>
        </div>
    </div>
    <hr>
    <h5>Lịch sử đặt vé</h5>
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tên phim</th>
                    <th>Suất chiếu</th>
                    <th>Ghế</th>
                    <th>Giá</th>
                    <th>Ngày đặt</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($user['history'])): foreach($user['history'] as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['movie']) ?></td>
                    <td><?= htmlspecialchars($h['showtime']) ?></td>
                    <td><?= htmlspecialchars($h['seats']) ?></td>
                    <td><?= number_format($h['price'],0,',','.') ?> VNĐ</td>
                    <td><?= date('d/m/Y', strtotime($h['date'])) ?></td>
                    <td>
                        <?php
                        if($h['status']=='paid') echo '<span class="badge bg-success">Đã thanh toán</span>';
                        elseif($h['status']=='unpaid') echo '<span class="badge bg-warning text-dark">Chưa thanh toán</span>';
                        else echo '<span class="badge bg-secondary">Đã hủy</span>';
                        ?>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" class="text-center text-muted">Chưa có lịch sử đặt vé.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <h5>Ưu đãi đã sử dụng</h5>
    <div class="table-responsive mb-3">
        <table class="table table-sm table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tên ưu đãi</th>
                    <th>Ngày sử dụng</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($user['offers'])): foreach($user['offers'] as $offer): ?>
                <tr>
                    <td><?= htmlspecialchars($offer['name']) ?></td>
                    <td><?= date('d/m/Y', strtotime($offer['used_date'])) ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="2" class="text-center text-muted">Chưa sử dụng ưu đãi nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        <a href="QuanLyNguoiDung.php" class="btn btn-secondary">Quay lại</a>
        <?php if($user['status']!='locked'): ?>
        <a href="KhoaNguoiDung.php?id=<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')">Khóa tài khoản</a>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-danger text-center">
        Không tìm thấy người dùng!
    </div>
    <div class="mt-4 text-center">
        <a href="QuanLyNguoiDung.php" class="btn btn-secondary">Quay lại</a>
    </div>
    <?php endif; ?>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
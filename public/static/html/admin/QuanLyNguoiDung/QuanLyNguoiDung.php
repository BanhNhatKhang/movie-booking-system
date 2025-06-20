<?php
$activePage = 'NguoiDung';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Người dùng</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyNguoiDung.css">
</head>
<body style="background: #f5f5f5;">
<div class="main-content">
    <div class="topbar">
        <div class="system-name">
            <strong>HỆ THỐNG QUẢN LÝ RẠP PHIM</strong>
        </div>
        <div class="user-info">
            <i class="bi bi-person-circle"></i>
            <span>Quản trị viên</span>
        </div>
    </div>
    <h2 class="mb-4">Quản lý Người dùng</h2>
    <div class="content">
        <!-- Bộ lọc -->
        <form class="row g-3 align-items-end filter-form mb-3" method="get">
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm tên/email</label>
                <input type="text" class="form-control" name="q" placeholder="Nhập tên hoặc email..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Phân loại</label>
                <select class="form-select" name="role">
                    <option value="">Tất cả</option>
                    <option value="admin" <?= (isset($_GET['role']) && $_GET['role']=='admin')?'selected':''; ?>>Admin</option>
                    <option value="guest" <?= (isset($_GET['role']) && $_GET['role']=='guest')?'selected':''; ?>>Khách</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> Lọc</button>
            </div>
        </form>
        <!-- Danh sách người dùng -->
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Email</th>
                        <th>Tên</th>
                        <th>Phân loại</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Dữ liệu mẫu, thay bằng dữ liệu thực tế từ DB
                    $users = [
                        [
                            'id'=>1,
                            'email'=>'admin@khfcinema.vn',
                            'name'=>'Admin KHF',
                            'role'=>'admin',
                            'status'=>'active'
                        ],
                        [
                            'id'=>2,
                            'email'=>'nguyenvanb@gmail.com',
                            'name'=>'Nguyễn Văn B',
                            'role'=>'guest',
                            'status'=>'active'
                        ],
                        [
                            'id'=>3,
                            'email'=>'lethic@gmail.com',
                            'name'=>'Lê Thị C',
                            'role'=>'guest',
                            'status'=>'locked'
                        ],
                    ];
                    // Lọc dữ liệu nếu có tìm kiếm
                    $q = isset($_GET['q']) ? mb_strtolower(trim($_GET['q'])) : '';
                    $role = isset($_GET['role']) ? $_GET['role'] : '';
                    $filtered = array_filter($users, function($u) use ($q, $role) {
                        $ok = true;
                        if ($q && mb_strpos(mb_strtolower($u['name'].$u['email']), $q) === false) $ok = false;
                        if ($role && $u['role'] != $role) $ok = false;
                        return $ok;
                    });
                    foreach($filtered as $user):
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td>
                            <?php
                            if($user['role']=='admin') echo '<span class="badge status-admin">Admin</span>';
                            else echo '<span class="badge status-guest">Khách</span>';
                            ?>
                        </td>
                        <td>
                            <?php
                            if($user['status']=='locked') echo '<span class="badge status-locked">Đã khóa</span>';
                            else echo '<span class="badge bg-success">Hoạt động</span>';
                            ?>
                        </td>
                        <td>
                            <a href="ChiTietNguoiDung.php?id=<?= $user['id'] ?>" class="btn btn-info btn-sm action-btn" title="Xem thông tin"><i class="bi bi-eye"></i></a>
                            <?php if($user['status']!='locked'): ?>
                            <a href="KhoaNguoiDung.php?id=<?= $user['id'] ?>" class="btn btn-danger btn-sm action-btn" title="Khóa tài khoản" onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')"><i class="bi bi-lock"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($filtered)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Không tìm thấy người dùng phù hợp.</td>
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
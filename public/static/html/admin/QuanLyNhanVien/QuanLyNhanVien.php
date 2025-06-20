<?php
$activePage = 'NhanVien';
// Dữ liệu mẫu nhân viên
$staffs = [
    ['id'=>1, 'email'=>'nhanvien1@khfcinema.vn', 'name'=>'Nguyễn Văn A', 'phone'=>'0901234567'],
    ['id'=>2, 'email'=>'nhanvien2@khfcinema.vn', 'name'=>'Trần Thị B', 'phone'=>'0912345678'],
    ['id'=>3, 'email'=>'nhanvien3@khfcinema.vn', 'name'=>'Lê Văn C', 'phone'=>'0987654321'],
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhân viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css">
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
    <h2 class="mb-4">Quản lý Nhân viên</h2>
    <div class="content">
        <!-- Thêm nhân viên -->
        <form class="row g-3 align-items-end mb-4" method="post" action="">
            <div class="col-md-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" placeholder="Nhập email..." required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tên</label>
                <input type="text" class="form-control" name="name" placeholder="Nhập tên..." required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Số điện thoại</label>
                <input type="text" class="form-control" name="phone" placeholder="Nhập số điện thoại..." required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success w-100" type="submit"><i class="bi bi-plus-circle"></i> Thêm nhân viên</button>
            </div>
        </form>
        <!-- Danh sách nhân viên -->
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Email</th>
                        <th>Tên</th>
                        <th>Số điện thoại</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($staffs as $staff): ?>
                    <tr>
                        <td><?= htmlspecialchars($staff['email']) ?></td>
                        <td><?= htmlspecialchars($staff['name']) ?></td>
                        <td><?= htmlspecialchars($staff['phone']) ?></td>
                        <td>
                            <a href="SuaNhanVien.php?id=<?= $staff['id'] ?>" class="btn btn-warning btn-sm action-btn" title="Sửa thông tin"><i class="bi bi-pencil"></i></a>
                            <a href="XoaNhanVien.php?id=<?= $staff['id'] ?>" class="btn btn-danger btn-sm action-btn" title="Xóa nhân viên" onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($staffs)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">Chưa có nhân viên nào.</td>
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
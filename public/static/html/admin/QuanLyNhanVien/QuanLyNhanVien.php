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
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css">
</head>
<body>
    <div class="admin-layout">
        <?php include '../../../layouts/admin/Sidebar.php'; ?>

        <div class="main-content">
            <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>

            <main>
                <div class="container py-4 content">
                    <h1>Quản lý Nhân viên</h1><hr>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-primary">
                        <a href="ThemNhanVien.php" class="text-white text-decoration-none">
                            <i class="bi bi-plus-circle"></i> Thêm nhân viên
                        </a>
                    </button>
                    <form class="d-flex gap-2 w-75 justify-content-end flex-wrap" method="get" action="">
                        <input type="text" name="ten_nv" class="form-control w-auto" style="min-width:160px;" placeholder="Tìm tên nhân viên">
                        <input type="text" name="sdt_nv" class="form-control w-auto" style="min-width:140px;" placeholder="Tìm số điện thoại">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                    </div>
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
                                        <a href="SuaNhanVien.php?id=<?= $staff['id'] ?>" class="btn btn-warning btn-sm action-btn" title="Sửa thông tin"><i class="bi bi-pencil-square"></i></a>
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
            </main>
        </div>
    </div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
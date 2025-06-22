<?php
$activePage = 'NhanVien';
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($email && $name && $phone) {
        $success = "Thêm nhân viên thành công!";
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm nhân viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
</head>
<body>
<div class="admin-layout">
    <?php include '../../../layouts/admin/Sidebar.php'; ?>
    <div class="main-content">
        <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>
        <main>
            <div class="container py-4">
                <h2>Thêm nhân viên</h2>
                <?php if($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                    <a href="QuanLyNhanVien.php" class="btn btn-primary">Quay lại danh sách</a>
                <?php else: ?>
                    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                    <form method="post" class="w-50">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <button class="btn btn-success" type="submit">Thêm nhân viên</button>
                        <a href="QuanLyNhanVien.php" class="btn btn-secondary">Hủy</a>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>
<?php
$activePage = 'NhanVien';
$id = $_GET['id'] ?? 0;
$success = true;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa nhân viên</title>
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
                <h2>Xóa nhân viên</h2>
                <?php if($success): ?>
                    <div class="alert alert-success">Đã xóa nhân viên thành công!</div>
                    <a href="QuanLyNhanVien.php" class="btn btn-primary">Quay lại danh sách</a>
                <?php else: ?>
                    <div class="alert alert-danger">Có lỗi xảy ra khi xóa nhân viên.</div>
                    <a href="QuanLyNhanVien.php" class="btn btn-secondary">Quay lại</a>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>
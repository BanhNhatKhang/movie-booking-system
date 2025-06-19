<?php
$activePage = 'NguoiDung';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Thực tế: cập nhật trạng thái tài khoản trong DB thành "locked" ở đây
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Khóa tài khoản</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <style>
        .main-content { max-width: 500px; margin: 60px auto; background: #fff; border-radius: 12px; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 32px; text-align: center;}
        .icon-lock { font-size: 3rem; color: #e74c3c; }
    </style>
</head>
<body style="background: #f5f5f5;">
<div class="main-content">
    <div class="icon-lock mb-3"><i class="bi bi-lock-fill"></i></div>
    <h4>Tài khoản #<?= htmlspecialchars($id) ?> đã bị khóa thành công!</h4>
    <a href="QuanLyNguoiDung.php" class="btn btn-primary mt-4">Quay lại danh sách người dùng</a>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
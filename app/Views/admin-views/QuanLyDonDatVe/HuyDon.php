<?php
$activePage = 'admin-orders';
// Lấy id vé từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Thực tế: cập nhật trạng thái vé trong DB thành "cancelled" ở đây
// Demo: thông báo đã hủy thành công
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hủy vé #<?= htmlspecialchars($id) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <link rel="stylesheet" href="/static/css/admin/HuyDon.css">
</head>
<body style="background: #f5f5f5;">
<div class="main-content d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="icon-cancel mb-3"><i class="bi bi-x-circle-fill"></i></div>
    <h4>Vé #<?= htmlspecialchars($id) ?> đã được hủy thành công!</h4>
    <a href="QuanLyDonDatVe.php" class="btn btn-primary mt-4">Quay lại danh sách đơn đặt vé</a>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
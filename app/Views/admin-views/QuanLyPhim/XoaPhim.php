<?php
// Lấy id phim từ URL
$id = $_GET['id'] ?? 0;
// Thực tế: Xóa phim khỏi DB ở đây
// Sau khi xóa, chuyển về trang quản lý phim
header('Location: QuanLyPhim.php?msg=delete_success');
exit;

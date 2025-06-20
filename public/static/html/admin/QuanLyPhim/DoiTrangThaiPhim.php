<?php
// Lấy id phim từ URL
$id = $_GET['id'] ?? 0;
// Thực tế: Lấy trạng thái hiện tại từ DB, chuyển sang trạng thái tiếp theo (showing -> coming -> ended -> showing ...)
// Sau khi đổi trạng thái, chuyển về trang quản lý phim
header('Location: QuanLyPhim.php?msg=status_success');
exit;
?>
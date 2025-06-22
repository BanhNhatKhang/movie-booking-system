<?php
session_start();
$activePage = 'home';


if (!isset($_SESSION['user'])) {
    header('Location: /static/html/users/Login/DangNhap.php');
    exit();
}


$user_name = htmlspecialchars($_SESSION['user']['name']);
$user_email = htmlspecialchars($_SESSION['user']['email']);
$user_signup = htmlspecialchars($_SESSION['user']['signup']);
$user_phone = htmlspecialchars($_SESSION['user']['phone']);
$user_gender = htmlspecialchars($_SESSION['user']['gender'] ? 'Nam' : 'Nữ');

include __DIR__ . '/../../../layouts/users/HeaderLogin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
</head>
<body style="background-color: rgb(40, 40, 40)">
<main>
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">
                <div class="card profile-card shadow p-4 rounded-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-semibold user-name"><?= $user_name ?></h2>
                        <br>
                        <span class="badge bg-warning text-dark fs-6 px-4 py-2 fw-semibold">⭐ Thành viên Vàng</span>
                    </div><br>
                    <div class="row justify-content-center text-start">
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <p><strong>Email:</strong> <?= $user_email ?></p>
                            <p><strong>Số điện thoại:</strong> <?= $user_phone ?></p>
                            <p><strong>Giới tính:</strong> <?= $user_gender ?></p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Ngày đăng ký:</strong> <?= $user_signup ?></p>
                            <p><strong>Tổng vé đã đặt:</strong> Chưa cập nhật</p>
                            <p><strong>Điểm tích lũy:</strong> Chưa cập nhật</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <a href="/static/html/users/LichSuDatVe/LichSuDatVe.php" class="btn btn-outline-primary px-4 py-2 shadow-sm">Lịch sử đặt vé</a>
                        <button class="btn btn-danger px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            Đổi mật khẩu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal đổi mật khẩu giữ nguyên -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">Mật khẩu mới</label>
                        <input type="password" class="form-control" id="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php
    include __DIR__ . '/../../../layouts/users/Footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
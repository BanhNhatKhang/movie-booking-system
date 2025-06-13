<?php 
    include __DIR__ . '/../../../layouts/users/HeaderLogin.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
</head>
<body style="background-color: rgb(40, 40, 40)">
<main>
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10">
                <div class="card profile-card shadow p-4 rounded-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">Nguyễn Văn A</h2><br>
                        <span class="badge bg-warning text-dark fs-6 px-4 py-2 fw-semibold">⭐ Thành viên Vàng</span>
                    </div><br>
                    <div class="row justify-content-center text-start">
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <p><strong>Email:</strong> nguyenvana@gmail.com</p>
                            <p><strong>Số điện thoại:</strong> 0909123456</p>
                            <p><strong>Giới tính:</strong> Nam</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Ngày đăng ký:</strong> 01/01/2024</p>
                            <p><strong>Tổng vé đã đặt:</strong> 18</p>
                            <p><strong>Điểm tích lũy:</strong> 3200</p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
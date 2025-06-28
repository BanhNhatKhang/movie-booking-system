

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main>
    <div class="container py-3">
        
        <?php if(isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i><strong><?php echo e($_SESSION['success_message']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i><strong><?php echo e($_SESSION['error_message']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">
                <div class="card profile-card shadow p-4 rounded-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-semibold user-name"><?php echo e($_SESSION['user_name'] ?? 'Người dùng'); ?></h2>
                        <br>
                        <span class="badge bg-warning text-dark fs-6 px-4 py-2 fw-semibold">⭐ Thành viên Vàng</span>
                    </div><br>
                    <div class="row justify-content-center text-start">
                        <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                            <p><strong>Email:</strong> <?php echo e($user['nd_email'] ?? 'Chưa cập nhật'); ?></p>
                            <p><strong>Số điện thoại:</strong> <?php echo e($user['nd_sdt'] ?? 'Chưa cập nhật'); ?></p>
                            <p><strong>Giới tính:</strong> <?php echo e(isset($user['nd_gioitinh']) ? ($user['nd_gioitinh'] ? 'Nam' : 'Nữ') : 'Chưa cập nhật'); ?></p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <p><strong>Ngày sinh:</strong> <?php echo e(isset($user['nd_ngaysinh']) ? date('d/m/Y', strtotime($user['nd_ngaysinh'])) : 'Chưa cập nhật'); ?></p>
                            <p><strong>CCCD:</strong> <?php echo e($user['nd_cccd'] ?? 'Chưa cập nhật'); ?></p>
                            <p><strong>Ngày đăng ký:</strong> <?php echo e(isset($user['nd_ngaydangky']) ? date('d/m/Y', strtotime($user['nd_ngaydangky'])) : 'Chưa cập nhật'); ?></p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center gap-3 mt-4 flex-wrap">
                        <a href="/lich-su-dat-ve" class="btn btn-outline-primary px-4 py-2 shadow-sm">Lịch sử đặt vé</a>
                        <button class="btn btn-danger px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                            Đổi mật khẩu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal đổi mật khẩu -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/doi-mat-khau" id="changePasswordForm">
                        <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token'] ?? ''); ?>">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="new_password" id="newPassword" required minlength="6">
                            <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
<script>
    // Kiểm tra mật khẩu khớp
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu mới không khớp!');
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return false;
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\mysites\ct27501-project-BanhNhatKhang-1\app\Views/users-views/ThongTinCaNhan/ThongTinCaNhan.blade.php ENDPATH**/ ?>
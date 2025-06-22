


<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="/static/css/users/Login.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="auth-container">
                    <div class="container">
                        <div class="auth-card">
                            <div class="auth-header">
                                <h2 class="auth-title">ĐĂNG KÝ</h2>
                            </div>
                            <div class="auth-body">
                                <?php if(isset($_SESSION['error_message'])): ?>
                                    <div class="alert alert-danger text-center"><?php echo e($_SESSION['error_message']); ?></div>
                                <?php endif; ?>
                                <?php if(isset($_SESSION['success_message'])): ?>
                                    <div class="alert alert-success text-center"><?php echo e($_SESSION['success_message']); ?></div>
                                <?php endif; ?>
                                <form method="post" action="/dang-ky">
                                    <input type="hidden" name="csrf_token" value="<?php echo e($_SESSION['csrf_token'] ?? ''); ?>">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Họ & tên(*)" name="full_name" required value="<?php echo e($_POST['full_name'] ?? ''); ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="dob" required value="<?php echo e($_POST['dob'] ?? ''); ?>" />
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Địa chỉ(*)" name="address" required value="<?php echo e($_POST['address'] ?? ''); ?>" />
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center">
                                            <label class="me-3 mb-0 text-white">Giới tính:</label>
                                            <div class="form-check me-3">
                                                <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Nam" <?php echo e((($_POST['gender'] ?? 'Nam') == 'Nam') ? 'checked' : ''); ?> />
                                                <label class="form-check-label text-white" for="genderMale">Nam</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Nữ" <?php echo e((($_POST['gender'] ?? '') == 'Nữ') ? 'checked' : ''); ?> />
                                                <label class="form-check-label text-white" for="genderFemale">Nữ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="CCCD(*)" name="cmnd" required value="<?php echo e($_POST['cmnd'] ?? ''); ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control" placeholder="Tên đăng nhập" name="username" required value="<?php echo e($_POST['username'] ?? ''); ?>" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="email" class="form-control" placeholder="Email(*)" name="email" required value="<?php echo e($_POST['email'] ?? ''); ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" class="form-control" placeholder="Mật khẩu(*)" name="password" required />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <input type="tel" class="form-control" placeholder="Điện thoại(*)" name="phone" required value="<?php echo e($_POST['phone'] ?? ''); ?>" />
                                        </div>
                                        <div class="col-md-6">
                                            <input type="password" class="form-control" placeholder="Mật khẩu nhập lại(*)" name="confirm_password" required />
                                        </div>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-danger px-4">ĐĂNG KÝ</button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p class="text-white">
                                            Đã có tài khoản vui lòng
                                            <a href="/dang-nhap" class="text-danger fw-bold">Đăng nhập!</a>
                                        </p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server\ct27501-project-BanhNhatKhang\app\Views/users-views/Login/DangKy.blade.php ENDPATH**/ ?>
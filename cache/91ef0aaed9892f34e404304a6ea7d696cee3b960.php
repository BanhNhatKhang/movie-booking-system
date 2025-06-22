


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
                                <h2 class="auth-title">ĐĂNG NHẬP</h2>
                            </div>
                            <div class="auth-body">
                                <?php if(isset($error_message) && $error_message): ?>
                                    <div class="alert alert-danger text-center"><?php echo e(htmlspecialchars($error_message)); ?></div>
                                <?php endif; ?>
                                <form method="post">
                                    <div class="mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Email / Tên đăng nhập"
                                            name="username"
                                            required
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <input
                                            type="password"
                                            class="form-control"
                                            placeholder="Mật khẩu"
                                            name="password"
                                            required
                                        />
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            id="rememberMe"
                                            name="remember"
                                        />
                                        <label class="form-check-label text-white" for="rememberMe">
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-danger px-4">
                                            ĐĂNG NHẬP
                                        </button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p class="text-white">
                                            Chưa có tài khoản?
                                            <a href="DangKy.php" class="text-danger fw-bold">Đăng ký!</a>
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

<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server\ct27501-project-BanhNhatKhang\app\Views/users-views/Login/DangNhap.blade.php ENDPATH**/ ?>



<?php $__env->startSection('title', 'Quản lý Người dùng'); ?>

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyNguoiDung.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content">
    <h1>Quản lý Người dùng</h1><hr>
    <div>
        <!-- Bộ lọc -->
        <form class="row g-3 align-items-end filter-form mb-3" method="get">
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm tên/email</label>
                <input type="text" class="form-control" name="q" placeholder="Nhập tên hoặc email..." value="<?php echo e(isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Phân loại</label>
                <select class="form-select" name="role">
                    <option value="">Tất cả</option>
                    <option value="admin" <?php echo e((isset($_GET['role']) && $_GET['role']=='admin')?'selected':''); ?>>Admin</option>
                    <option value="guest" <?php echo e((isset($_GET['role']) && $_GET['role']=='guest')?'selected':''); ?>>Khách</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
            </div>
        </form>
        <!-- Danh sách người dùng -->
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Email</th>
                        <th>Tên</th>
                        <th>Phân loại</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Dữ liệu mẫu, thay bằng dữ liệu thực tế từ DB
                    $users = [
                        [
                            'id'=>1,
                            'email'=>'admin@khfcinema.vn',
                            'name'=>'Admin KHF',
                            'role'=>'admin',
                            'status'=>'active'
                        ],
                        [
                            'id'=>2,
                            'email'=>'nguyenvanb@gmail.com',
                            'name'=>'Nguyễn Văn B',
                            'role'=>'guest',
                            'status'=>'active'
                        ],
                        [
                            'id'=>3,
                            'email'=>'lethic@gmail.com',
                            'name'=>'Lê Thị C',
                            'role'=>'guest',
                            'status'=>'locked'
                        ],
                    ];
                    // Lọc dữ liệu nếu có tìm kiếm
                    $q = isset($_GET['q']) ? mb_strtolower(trim($_GET['q'])) : '';
                    $role = isset($_GET['role']) ? $_GET['role'] : '';
                    $filtered = array_filter($users, function($u) use ($q, $role) {
                        $ok = true;
                        if ($q && mb_strpos(mb_strtolower($u['name'].$u['email']), $q) === false) $ok = false;
                        if ($role && $u['role'] != $role) $ok = false;
                        return $ok;
                    });
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($user['email']); ?></td>
                        <td><?php echo e($user['name']); ?></td>
                        <td>
                            <?php if($user['role']=='admin'): ?>
                                <span class="badge status-admin">Admin</span>
                            <?php else: ?>
                                <span class="badge status-guest">Khách</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($user['status']=='locked'): ?>
                                <span class="badge status-locked">Đã khóa</span>
                            <?php else: ?>
                                <span class="badge bg-success">Hoạt động</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/chi-tiet-nguoi-dung?id=<?php echo e($user['id']); ?>" class="btn btn-info btn-sm action-btn" title="Xem thông tin"><i class="bi bi-eye"></i></a>
                            <?php if($user['status']!='locked'): ?>
                            <a href="/khoa-nguoi-dung?id=<?php echo e($user['id']); ?>" class="btn btn-danger btn-sm action-btn" title="Khóa tài khoản" onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')"><i class="bi bi-lock"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Không tìm thấy người dùng phù hợp.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\mysites\ct27501-project-BanhNhatKhang-1\app\Views/admin-views/QuanLyNguoiDung/QuanLyNguoiDung.blade.php ENDPATH**/ ?>
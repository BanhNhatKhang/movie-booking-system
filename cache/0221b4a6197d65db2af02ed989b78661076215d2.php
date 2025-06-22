

<?php $__env->startSection('title', 'Quản lý Nhân viên'); ?>

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    // Dữ liệu mẫu nhân viên
    $staffs = [
        ['id'=>1, 'email'=>'nhanvien1@khfcinema.vn', 'name'=>'Nguyễn Văn A', 'phone'=>'0901234567'],
        ['id'=>2, 'email'=>'nhanvien2@khfcinema.vn', 'name'=>'Trần Thị B', 'phone'=>'0912345678'],
        ['id'=>3, 'email'=>'nhanvien3@khfcinema.vn', 'name'=>'Lê Văn C', 'phone'=>'0987654321'],
    ];
    $ten_nv = isset($_GET['ten_nv']) ? mb_strtolower(trim($_GET['ten_nv'])) : '';
    $sdt_nv = isset($_GET['sdt_nv']) ? trim($_GET['sdt_nv']) : '';
    $filtered = array_filter($staffs, function($nv) use ($ten_nv, $sdt_nv) {
        $ok = true;
        if ($ten_nv && mb_strpos(mb_strtolower($nv['name']), $ten_nv) === false) $ok = false;
        if ($sdt_nv && mb_strpos($nv['phone'], $sdt_nv) === false) $ok = false;
        return $ok;
    });
?>
<div class="container py-4 content">
    <h1>Quản lý Nhân viên</h1><hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/them-nhan-vien" class="btn btn-primary text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i> Thêm nhân viên
        </a>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap" method="get" action="">
            <input type="text" name="ten_nv" class="form-control w-auto" style="min-width:160px;" placeholder="Tìm tên nhân viên" value="<?php echo e(isset($_GET['ten_nv']) ? htmlspecialchars($_GET['ten_nv']) : ''); ?>">
            <input type="text" name="sdt_nv" class="form-control w-auto" style="min-width:140px;" placeholder="Tìm số điện thoại" value="<?php echo e(isset($_GET['sdt_nv']) ? htmlspecialchars($_GET['sdt_nv']) : ''); ?>">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <!-- Danh sách nhân viên -->
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Email</th>
                    <th>Tên</th>
                    <th>Số điện thoại</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $filtered; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($staff['email']); ?></td>
                    <td><?php echo e($staff['name']); ?></td>
                    <td><?php echo e($staff['phone']); ?></td>
                    <td>
                        <a href="/sua-nhan-vien?id=<?php echo e($staff['id']); ?>" class="btn btn-warning btn-sm action-btn" title="Sửa thông tin"><i class="bi bi-pencil-square"></i></a>
                        <a href="/xoa-nhan-vien?id=<?php echo e($staff['id']); ?>" class="btn btn-danger btn-sm action-btn" title="Xóa nhân viên" onclick="return confirm('Bạn chắc chắn muốn xóa nhân viên này?')"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="text-center text-muted">Chưa có nhân viên nào.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\mysites\ct27501-project-BanhNhatKhang-1\app\Views/admin-views/QuanLyNhanVien/QuanLyNhanVien.blade.php ENDPATH**/ ?>
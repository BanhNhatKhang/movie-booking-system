


<?php $__env->startSection('title', 'Sửa ưu đãi'); ?>

<?php $__env->startSection('page-css'); ?>
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Sửa ưu đãi</h4>
    <form method="POST" enctype="multipart/form-data" action="">
        <div class="mb-3">
            <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
            <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" required />
        </div>
        <div class="mb-3">
            <label for="tenUuDai" class="form-label">Tên ưu đãi</label>
            <input type="text" class="form-control" id="tenUuDai" name="tenUuDai" required />
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Sửa ưu đãi</button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">Hủy</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/TrangChu/SuaUuDaiHome.blade.php ENDPATH**/ ?>
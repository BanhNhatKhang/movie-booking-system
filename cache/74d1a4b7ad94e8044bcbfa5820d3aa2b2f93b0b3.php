


<?php $__env->startSection('title', 'Sửa phim'); ?>

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content" style="background-color: white;">
    <h3 class="mb-4">Sửa phim</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên phim</label>
            <input type="text" class="form-control" name="name" value="<?php echo e($movie['name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <input type="text" class="form-control" name="genre" value="<?php echo e($movie['genre'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời lượng (phút)</label>
            <input type="number" class="form-control" name="duration" value="<?php echo e($movie['duration'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày khởi chiếu</label>
            <input type="date" class="form-control" name="release" value="<?php echo e($movie['release'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="desc" rows="3"><?php echo e($movie['desc'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Trailer (link YouTube)</label>
            <input type="url" class="form-control" name="trailer" value="<?php echo e($movie['trailer'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Poster hiện tại</label><br>
            <?php if(!empty($movie['poster'])): ?>
                <img src="<?php echo e($movie['poster']); ?>" style="max-width:120px">
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Đổi poster mới (nếu muốn)</label>
            <input type="file" class="form-control" name="poster">
        </div>
        <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
        <a href="/quan-ly-phim" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/QuanLyPhim/SuaPhim.blade.php ENDPATH**/ ?>



<?php $__env->startSection('title', 'Thêm phim mới'); ?>

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content" style="background-color: white;">
    <h3 class="mb-4">Thêm phim mới</h3>
    <form method="POST" enctype="multipart/form-data" action="">
        <div class="mb-3">
            <label class="form-label">Tên phim</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <input type="text" class="form-control" name="genre" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời lượng (phút)</label>
            <input type="number" class="form-control" name="duration" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày khởi chiếu</label>
            <input type="date" class="form-control" name="release" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="desc" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Trailer (link YouTube)</label>
            <input type="url" class="form-control" name="trailer">
        </div>
        <div class="mb-3">
            <label class="form-label">Poster</label>
            <input type="file" class="form-control" name="poster">
        </div>
        <button class="btn btn-success" type="submit">Thêm phim</button>
        <a href="/quan-ly-phim" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\mysites\ct27501-project-BanhNhatKhang-1\app\Views/admin-views/QuanLyPhim/ThemPhim.blade.php ENDPATH**/ ?>
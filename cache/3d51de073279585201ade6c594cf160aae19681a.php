

<?php $__env->startSection('title', 'Thêm lịch chiếu'); ?>

<?php $__env->startSection('page-css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content">
    <h2>Thêm lịch chiếu mới</h2>
    <hr>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="phim" class="form-label">Chọn phim</label>
            <select class="form-select" id="phim" name="phim" required>
                <option value="">-- Chọn phim --</option>
                <option>Avengers: Endgame</option>
                <option>Fast & Furious 10</option>
                
            </select>
        </div>
        <div class="mb-3">
            <label for="phongChieu" class="form-label">Phòng chiếu</label>
            <select class="form-select" id="phongChieu" name="phongChieu" required>
                <option value="">-- Chọn phòng --</option>
                <option>Phòng 1</option>
                <option>Phòng 2</option>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="ngayChieu" class="form-label">Ngày chiếu</label>
                <input type="date" class="form-control" id="ngayChieu" name="ngayChieu" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="gioChieu" class="form-label">Giờ chiếu</label>
                <input type="time" class="form-control" id="gioChieu" name="gioChieu" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="giaVe" class="form-label">Giá vé (VNĐ)</label>
            <input type="number" class="form-control" id="giaVe" name="giaVe" min="0" required>
        </div>
        <div class="mb-3">
            <label for="dinhDang" class="form-label">Định dạng</label>
            <select class="form-select" id="dinhDang" name="dinhDang" required>
                <option>2D</option>
                <option>3D</option>
                <option>IMAX</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="trangThai" class="form-label">Trạng thái</label>
            <select class="form-select" id="trangThai" name="trangThai" required>
                <option>Sắp chiếu</option>
                <option>Đang chiếu</option>
                <option>Đã chiếu</option>
                <option>Ẩn</option>
            </select>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Thêm lịch chiếu</button>
            <a href="/quan-ly-lich-chieu" class="btn btn-secondary ms-2">Hủy</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/LichChieu/ThemLichChieu.blade.php ENDPATH**/ ?>
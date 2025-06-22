

<?php $__env->startSection('title', 'Thêm ưu đãi'); ?>

<?php $__env->startSection('page-css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content p-4" style="background-color: white;">
    <h4 class="mb-4">Thêm ưu đãi mới</h4>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
            <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" required />
        </div>
        <div class="mb-3">
            <label for="tenUuDai" class="form-label">Tên ưu đãi</label>
            <input type="text" class="form-control" id="tenUuDai" name="tenUuDai" required />
        </div>
        <div class="mb-3">
            <label for="noiDungChiTiet" class="form-label">Nội dung chi tiết ưu đãi</label>
            <textarea class="form-control" id="noiDungChiTiet" name="noiDungChiTiet" rows="3" required></textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="dateUuDai" class="form-label">Thời gian bắt đầu</label>
                <input type="date" class="form-control" id="dateUuDai" name="dateUuDai" required />
            </div>
            <div class="col-md-6 mb-3">
                <label for="dateUuDaiEnd" class="form-label">Thời gian kết thúc</label>
                <input type="date" class="form-control" id="dateUuDaiEnd" name="dateUuDaiEnd" required />
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="trangThai" class="form-label">Trạng thái</label>
                <select class="form-select" id="trangThai" name="trangThai" required>
                    <option value="dangdienra">Đang diễn ra</option>
                    <option value="sapdienra">Sắp diễn ra</option>
                    <option value="ketthuc">Kết thúc</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label for="loaiUuDai" class="form-label">Loại ưu đãi</label>
                <select class="form-select" id="loaiUuDai" name="loaiUuDai" required>
                    <option value="giamgia">Giảm giá</option>
                    <option value="combo">Combo</option>
                    <option value="thanhvien">Thành viên</option>
                    <option value="sinhnhat">Sinh nhật</option>
                    <option value="som">Sớm</option>
                    <option value="nganhang">Ngân hàng</option>
                </select>
            </div>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">Thêm ưu đãi</button>
            <a href="/quan-ly-uu-dai" class="btn btn-secondary ms-2">Hủy</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/UuDai/SuaUuDai.blade.php ENDPATH**/ ?>
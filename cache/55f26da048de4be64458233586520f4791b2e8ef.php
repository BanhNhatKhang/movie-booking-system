


<?php $__env->startSection('title', 'Quản lý trang chủ'); ?>

<?php $__env->startSection('page-css'); ?>
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/ModalXoa.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content">
    <h1>Quản lý Poster</h1><hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary">
            <a href="/them-poster" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Thêm poster mới
            </a>
        </button>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
            <select class="form-select w-auto" style="min-width:140px;">
                <option value="">Tất cả poster phim</option>
                <option>Avengers: Endgame</option>
            </select>
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Ảnh poster</th>
                    <th>Phim</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><img src="poster1.jpg" alt="Poster" width="100"></td>
                    <td>Avengers: Endgame</td>
                    <td>
                        <a href="/sua-poster" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button 
                            class="btn btn-sm btn-danger btn-delete" 
                            title="Xóa"
                            data-title="poster phim 'Avengers: Endgame'"
                            data-url="XoaPoster.php?id=1">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr><br>

    <h1>Quản lý ưu đãi</h1><hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-primary">
            <a href="/them-uu-dai" class="text-white text-decoration-none">
                <i class="bi bi-plus-circle"></i> Thêm ưu đãi mới
            </a>
        </button>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
            <select class="form-select w-auto" style="min-width:140px;">
                <option value="">Tất cả ưu đãi</option>
                <option>Thứ 4 vui vẻ</option>
            </select>
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Ảnh ưu đãi</th>
                    <th>Tên ưu đãi</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><img src="poster1.jpg" alt="Poster" width="100"></td>
                    <td>Thứ 4 vui vẻ</td>
                    <td>
                        <a href="/sua-uu-dai" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <button 
                            class="btn btn-sm btn-danger btn-delete" 
                            title="Xóa"
                            data-title="ưu đãi 'Thứ 4 vui vẻ'"
                            data-url="XoaUuDai.php?id=1">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="/static/js/admin/ModalXoa.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->make('admin-views.ModalXoa.ModalXoa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/TrangChu/QuanLyTrangChu.blade.php ENDPATH**/ ?>
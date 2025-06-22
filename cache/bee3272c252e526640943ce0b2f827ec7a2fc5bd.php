

<?php $__env->startSection('title', 'Quản lý lịch chiếu'); ?>

<?php $__env->startSection('page-css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content">
    <h1>Quản lý Lịch chiếu</h1>
    <hr>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/them-lich-chieu" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm lịch chiếu mới
        </a>
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
            <input type="date" class="form-control w-auto" placeholder="Lọc theo ngày">
            <select class="form-select w-auto" style="min-width:140px;">
                <option value="">Tất cả trạng thái</option>
                <option>Sắp chiếu</option>
                <option>Đang chiếu</option>
                <option>Đã chiếu</option>
                <option>Ẩn</option>
            </select>
            <select class="form-select w-auto" style="min-width:170px;">
                <option value="">Tất cả phim</option>
                <option>Avengers: Endgame</option>
                <option>Fast & Furious 10</option> 
            </select>
            <select class="form-select w-auto" style="min-width:130px;">
                <option value="">Tất cả phòng</option>
                <option>Phòng 1</option>
                <option>Phòng 2</option>
            </select>
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Phim</th>
                    <th>Phòng chiếu</th>
                    <th>Ngày chiếu</th>
                    <th>Giờ bắt đầu</th>
                    <th>Giá vé</th>
                    <th>Định dạng</th>
                    <th>Đã bán/SL ghế</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Avengers: Endgame</td>
                    <td>Phòng 1</td>
                    <td>2024-06-18</td>
                    <td>18:30</td>
                    <td>100.000đ</td>
                    <td>2D</td>
                    <td>80/120</td>
                    <td>
                        <span class="badge bg-success">Sắp chiếu</span>
                    </td>
                    <td>
                        <a href="/sua-lich-chieu" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="/xoa-lich-chieu?id=5"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa lịch chiếu này không?')"
                        class="btn btn-sm btn-danger" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Fast & Furious 10</td>
                    <td>Phòng 2</td>
                    <td>2024-06-18</td>
                    <td>20:00</td>
                    <td>120.000đ</td>
                    <td>3D</td>
                    <td>50/100</td>
                    <td>
                        <span class="badge bg-secondary">Đã chiếu</span>
                    </td>
                    <td>
                        <a href="/sua-lich-chieu" class="btn btn-sm btn-warning" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="/xoa-lich-chieu?id=6"
                        onclick="return confirm('Bạn có chắc chắn muốn xóa lịch chiếu này không?')"
                        class="btn btn-sm btn-danger" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/LichChieu/QuanLyLichChieu.blade.php ENDPATH**/ ?>
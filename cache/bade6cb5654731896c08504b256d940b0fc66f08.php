


<?php $__env->startSection('title', 'Chi tiết thanh toán'); ?>

<?php $__env->startSection('page-css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4 content">
    <h1><i class="bi bi-receipt"></i> Chi tiết thanh toán</h1>
    <hr>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-credit-card"></i> Thông tin giao dịch
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Mã giao dịch:</span> GD123456</div>
                <div class="col-md-4"><span class="info-label">Thời gian:</span> 2025-06-15 09:30</div>
                <div class="col-md-4"><span class="info-label">Trạng thái:</span> <span class="badge bg-success">Thành công</span></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Phương thức:</span> VNPay</div>
                <div class="col-md-4"><span class="info-label">Số tiền:</span> <b>120.000đ</b></div>
                <div class="col-md-4"><span class="info-label">Mã vé:</span> VE4567</div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-person"></i> Thông tin người dùng
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Họ tên:</span> Nguyễn Văn A</div>
                <div class="col-md-4"><span class="info-label">Email:</span> nguyenvana@email.com</div>
                <div class="col-md-4"><span class="info-label">Số điện thoại:</span> 0901234567</div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><span class="info-label">Tài khoản:</span> nguyenvana</div>
                <div class="col-md-4"><span class="info-label">Loại khách:</span> Thành viên</div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <i class="bi bi-ticket-detailed"></i> Thông tin vé đã mua
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table class="table align-middle table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Suất chiếu</th>
                            <th>Phim</th>
                            <th>Phòng</th>
                            <th>Ghế</th>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th>Giá vé</th>
                            <th>Loại vé</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>SC7890</td>
                            <td>Avengers: Endgame</td>
                            <td>Phòng 1</td>
                            <td>A5, A6</td>
                            <td>2025-06-18</td>
                            <td>18:30</td>
                            <td>120.000đ</td>
                            <td>Vé thường</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <a href="/quan-ly-thanh-toan" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/admin-views/ThanhToan/ChiTietThanhToan.blade.php ENDPATH**/ ?>


<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="/static/css/users/ThanhToan.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main>
    <div class="container">
        <div class="pay-card">
            <div class="pay-title">Thanh toán vé</div>
            <div class="pay-info">
                <div><span class="pay-label">Phim:</span> <span id="movieName">Tên phim demo</span></div>
                <div><span class="pay-label">Suất chiếu:</span> <span id="showTime">08:30 - 25/06/2024</span></div>
                <div><span class="pay-label">Phòng:</span> <span id="room">Phòng 2</span></div>
                <div><span class="pay-label">Ghế:</span> <span id="seats"><?php echo e($seats ?? 'Chưa chọn ghế'); ?></span></div>
            </div>
            <div class="pay-total">
                Tổng tiền: <span id="totalPrice"><?php echo e(number_format($total ?? 0, 0, ',', '.')); ?> VNĐ</span>
            </div>
            <button class="btn btn-momo" id="btnMomo">
                <img src="/static/imgs/momo.jpg" alt="MoMo" class="momo-logo">
                Thanh toán bằng MoMo
            </button>
            <div class="pay-success" id="paySuccess">
                <i class="bi bi-check-circle-fill"></i> Thanh toán thành công! <br>
                Cảm ơn bạn đã đặt vé tại KHF Cinema.
            </div>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-js'); ?>
    <script src="/static/js/users/ThanhToan.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server\ct27501-project-BanhNhatKhang\app\Views/users-views/ThanhToan/ThanhToan.blade.php ENDPATH**/ ?>
<<<<<<< HEAD:cache/8af783a9f626d074b60d240396d2089b50d37aa3.php


<?php $__env->startSection('page-css'); ?>
=======
<?php
session_start();
if (isset($_SESSION['user'])) {
    include __DIR__ . '/../../../layouts/users/HeaderLogin.php';
} else {
    include __DIR__ . '/../../../layouts/users/Header.php';
}

// Lấy dữ liệu seats và total từ URL
$seats = isset($_GET['seats']) ? $_GET['seats'] : '';
$total = isset($_GET['total']) ? (int)$_GET['total'] : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán vé</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
>>>>>>> bbae041446511ff7340b18c8b8f24f9eee8ceda6:public/static/html/users/ThanhToan/ThanhToan.php
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
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Servers\test\app\Views/user-view/ThanhToan/ThanhToan.blade.php ENDPATH**/ ?>
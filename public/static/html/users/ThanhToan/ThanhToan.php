<?php 
    $activePage='movies';
    include __DIR__ . '/../../../layouts/users/Header.php';

    $seats = isset($_GET['seats']) ? $_GET['seats'] : 'Chưa chọn ghế';
    $total = isset($_GET['total']) ? intval($_GET['total']) : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán vé</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThanhToan.css">
</head>
<body style="background: #222;">
    <div class="container">
        <div class="pay-card">
            <div class="pay-title">Thanh toán vé</div>
            <div class="pay-info">
                <div><span class="pay-label">Phim:</span> <span id="movieName">Tên phim demo</span></div>
                <div><span class="pay-label">Suất chiếu:</span> <span id="showTime">08:30 - 25/06/2024</span></div>
                <div><span class="pay-label">Phòng:</span> <span id="room">Phòng 2</span></div>
                <div><span class="pay-label">Ghế:</span> <span id="seats"><?= htmlspecialchars($seats) ?></span></div>
            </div>
            <div class="pay-total">
                Tổng tiền: <span id="totalPrice"><?= number_format($total, 0, ',', '.') ?> VNĐ</span>
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
    <script src="/static/js/users/ThanhToan.js"></script>
</body>
</html>
<?php include __DIR__ . '/../../../layouts/users/Footer.php'; ?>
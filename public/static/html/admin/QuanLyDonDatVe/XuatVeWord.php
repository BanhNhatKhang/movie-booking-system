<?php
$orders = [
    [
        'id'=>1001,
        'user'=>'Nguyễn Văn B',
        'movie'=>'Thanh Gươm Diệt Quỷ',
        'showtime'=>'08:30 25/06/2024',
        'seats'=>'G09, G10',
        'price'=>170000,
        'date'=>'2024-06-20',
        'status'=>'paid'
    ],
    [
        'id'=>1002,
        'user'=>'Trần Thị C',
        'movie'=>'Hành Trình Về Miền Đất Hứa',
        'showtime'=>'10:00 26/06/2024',
        'seats'=>'A01',
        'price'=>70000,
        'date'=>'2024-06-21',
        'status'=>'unpaid'
    ],
    [
        'id'=>1003,
        'user'=>'Lê Văn D',
        'movie'=>'Ký Ức Mùa Hè',
        'showtime'=>'14:00 22/06/2024',
        'seats'=>'B05, B06, B07',
        'price'=>210000,
        'date'=>'2024-06-19',
        'status'=>'cancelled'
    ],
];

// Lấy id từ URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Tìm đơn đặt vé theo id
$order = null;
foreach($orders as $o) {
    if($o['id'] == $id) {
        $order = $o;
        break;
    }
}

if(!$order) {
    die('Không tìm thấy đơn đặt vé!');
}

// Xuất header Word
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename=Ve_{$order['id']}.doc");
?>
<html>
<head>
    <meta charset="UTF-8">
    <title>Vé #<?= htmlspecialchars($order['id']) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/Admin.css">
    <link rel="stylesheet" href="/static/css/admin/XuatVeWord.css">
</head>
<body>
    <div class="ticket-box">
        <div class="ticket-title">VÉ XEM PHIM</div>
        <div class="ticket-row"><span class="label">Mã vé:</span> <span class="value"><?= htmlspecialchars($order['id']) ?></span></div>
        <div class="ticket-row"><span class="label">Tên phim:</span> <span class="value"><?= htmlspecialchars($order['movie']) ?></span></div>
        <div class="ticket-row"><span class="label">Suất chiếu:</span> <span class="value"><?= htmlspecialchars($order['showtime']) ?></span></div>
        <div class="ticket-row"><span class="label">Ghế:</span> <span class="value"><?= htmlspecialchars($order['seats']) ?></span></div>
        <div class="ticket-row"><span class="label">Giá:</span> <span class="value"><?= number_format($order['price'],0,',','.') ?> VNĐ</span></div>
        <div class="ticket-row"><span class="label">Ngày đặt:</span> <span class="value"><?= date('d/m/Y', strtotime($order['date'])) ?></span></div>
        <div class="ticket-row"><span class="label">Khách hàng:</span> <span class="value"><?= htmlspecialchars($order['user']) ?></span></div>
        <div class="ticket-row"><span class="label">Trạng thái:</span> <span class="value">
            <?php
            if($order['status']=='paid') echo 'Đã thanh toán';
            elseif($order['status']=='unpaid') echo 'Chưa thanh toán';
            else echo 'Đã hủy';
            ?>
        </span></div>
        <div style="margin-top:24px; text-align:center; font-size:0.95em; color:#888;">
            Cảm ơn quý khách và chúc quý khách xem phim vui vẻ!
        </div>
    </div>
</body>
</html>
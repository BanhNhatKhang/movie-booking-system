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
</head>
<body style="background:#fff;">
    <div style="max-width:500px;margin:40px auto;padding:32px 32px 24px 32px;border:2px solid #ff4444;border-radius:18px;box-shadow:0 2px 12px #eee;">
        <div style="text-align:center;margin-bottom:24px;">
            <span style="font-size:2.1em;font-weight:bold;color:#ff4444;letter-spacing:2px;">VÉ XEM PHIM</span>
            <div style="font-size:1.1em;color:#555;margin-top:4px;">KHF CINEMA</div>
        </div>
        <table style="width:100%;font-size:1.1em;border-collapse:collapse;">
            <tr>
                <td style="padding:6px 0;width:120px;font-weight:bold;">Mã vé:</td>
                <td style="padding:6px 0;"><?= htmlspecialchars($order['id']) ?></td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Tên phim:</td>
                <td style="padding:6px 0;"><?= htmlspecialchars($order['movie']) ?></td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Suất chiếu:</td>
                <td style="padding:6px 0;"><?= htmlspecialchars($order['showtime']) ?></td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Ghế:</td>
                <td style="padding:6px 0;"><?= htmlspecialchars($order['seats']) ?></td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Giá:</td>
                <td style="padding:6px 0;"><?= number_format($order['price'],0,',','.') ?> VNĐ</td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Ngày đặt:</td>
                <td style="padding:6px 0;"><?= date('d/m/Y', strtotime($order['date'])) ?></td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Khách hàng:</td>
                <td style="padding:6px 0;"><?= htmlspecialchars($order['user']) ?></td>
            </tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Trạng thái:</td>
                <td style="padding:6px 0;">
                    <?php
                    if($order['status']=='paid') echo '<span style="color:green;font-weight:bold;">Đã thanh toán</span>';
                    elseif($order['status']=='unpaid') echo '<span style="color:#ff9800;font-weight:bold;">Chưa thanh toán</span>';
                    else echo '<span style="color:#888;font-weight:bold;">Đã hủy</span>';
                    ?>
                </td>
            </tr>
        </table>
        <div style="margin-top:32px;text-align:center;font-size:1.05em;color:#888;">
            <i>Cảm ơn quý khách và chúc quý khách xem phim vui vẻ!</i>
        </div>
    </div>
</body>
</html>
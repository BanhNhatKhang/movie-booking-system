{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyDonDatVe\XuatVeWord.blade.php --}}
<html>
<head>
    <meta charset="UTF-8">
    <title>Vé #{{ $order['id'] ?? 'N/A' }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body style="background:#fff;">
    <div style="max-width:500px;margin:40px auto;padding:32px 32px 24px 32px;border:2px solid #ff4444;border-radius:18px;box-shadow:0 2px 12px #eee;">
        <div style="text-align:center;margin-bottom:24px;">
            <span style="font-size:2.1em;font-weight:bold;color:#ff4444;letter-spacing:2px;">VÉ XEM PHIM</span>
            <div style="font-size:1.1em;color:#555;margin-top:4px;">KHF CINEMA</div>
        </div>
        <table style="width:100%;font-size:1.1em;border-collapse:collapse;">
            <tr><td style="padding:6px 0;width:120px;font-weight:bold;">Mã vé:</td><td style="padding:6px 0;">{{ $order['id'] ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;font-weight:bold;">Tên phim:</td><td style="padding:6px 0;">{{ $order['movie'] ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;font-weight:bold;">Suất chiếu:</td><td style="padding:6px 0;">{{ $order['showtime'] ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;font-weight:bold;">Ghế:</td><td style="padding:6px 0;">{{ $order['seats'] ?? 'N/A' }}</td></tr>
            <tr><td style="padding:6px 0;font-weight:bold;">Giá:</td><td style="padding:6px 0;">{{ number_format($order['price'] ?? 0, 0, ',', '.') }} VNĐ</td></tr>
            <tr><td style="padding:6px 0;font-weight:bold;">Ngày đặt:</td><td style="padding:6px 0;">{{ date('d/m/Y', strtotime($order['date'] ?? 'now')) }}</td></tr>
            <tr><td style="padding:6px 0;font-weight:bold;">Khách hàng:</td><td style="padding:6px 0;">{{ $order['user'] ?? 'N/A' }}</td></tr>
            <tr>
                <td style="padding:6px 0;font-weight:bold;">Trạng thái:</td>
                <td style="padding:6px 0;">
                    @if (($order['status'] ?? '') == 'paid')
                        <span style="color:green;font-weight:bold;">Đã thanh toán</span>
                    @elseif (($order['status'] ?? '') == 'unpaid')
                        <span style="color:#ff9800;font-weight:bold;">Chưa thanh toán</span>
                    @else
                        <span style="color:#888;font-weight:bold;">Đã hủy</span>
                    @endif
                </td>
            </tr>
        </table>
        <div style="margin-top:32px;text-align:center;font-size:1.05em;color:#888;">
            <i>Cảm ơn quý khách và chúc quý khách xem phim vui vẻ!</i>
        </div>
        
        {{-- QR Code hoặc Barcode (nếu cần) --}}
        <div style="text-align:center;margin-top:20px;">
            <div style="font-size:0.9em;color:#999;">Mã vé: {{ $order['id'] ?? 'N/A' }}</div>
        </div>
    </div>

    {{-- Nút in (sẽ ẩn khi in) --}}
    <div class="no-print" style="text-align:center;margin-top:20px;">
        <button onclick="window.print()" style="padding:10px 20px;background:#ff4444;color:white;border:none;border-radius:5px;cursor:pointer;">In vé</button>
        <button onclick="window.close()" style="padding:10px 20px;background:#666;color:white;border:none;border-radius:5px;cursor:pointer;margin-left:10px;">Đóng</button>
    </div>
</body>
</html>
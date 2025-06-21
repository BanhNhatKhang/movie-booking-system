<?php 
    $activePage='movies';
    include __DIR__ . '/../../../layouts/users/Header.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chọn Ghế</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ChonGhe.css">
</head>
<body style="background: #222;">
    <div class="container">
        <div class="bg-dark text-white py-2 px-3" style="border-top: 1px solid #ff4444;">
            <h3 class="text-center mb-0">Bước 1: Chọn Ghế</h3>
        </div>
        <div class="seat-map position-relative py-4">
            <div class="seat-bg"></div> <!-- ảnh nền nằm dưới -->
            <div class="seat-content position-relative z-2 text-center">
                <div class="mb-2 text-white fw-bold">Màn hình</div>
                <!-- Ghế -->
                <?php
                $rows = [
                    'A' => ['aisle','normal','normal','normal','normal','normal','normal','aisle','normal','normal','normal','normal','normal','normal','aisle'],
                    'B' => ['aisle','normal','normal','normal','normal','normal','normal','aisle','normal','normal','normal','normal','normal','normal','aisle'],
                    'C' => ['aisle','normal','normal','normal','normal','normal','normal','aisle','normal','normal','normal','normal','normal','normal','aisle'],
                    'D' => ['aisle','vip','vip','vip','vip','vip','vip','aisle','vip','vip','vip','vip','vip','vip','aisle'],
                    'E' => ['aisle','vip','vip','vip','vip','vip','sold','aisle','vip','sold','vip','vip','vip','vip','aisle'],
                    'F' => ['aisle','vip','vip','vip','vip','vip','sold','aisle','vip','sold','vip','vip','vip','vip','aisle'],
                    'G' => ['aisle','vip','vip','vip','vip','vip','vip','aisle','vip','vip','vip','vip','vip','vip','aisle'],
                    'H' => ['aisle','luxury','luxury','luxury','luxury','luxury','luxury','aisle','luxury','luxury','luxury','luxury','luxury','luxury','aisle'],
                ];
                $seat_prices = [
                    'normal' => 70000,
                    'vip' => 90000,
                    'luxury' => 120000,
                    'couple' => 150000,
                ];
                $sold_seats = ['E06','E08','F06','F08','G09','G10']; // demo
                $selected_seats = ['G09','G10'];
                ?>
                <?php foreach ($rows as $row => $types): ?>
                    <div class="seat-row">
                        <?php for ($i = 1; $i <= count($types); $i++):
                            $type = $types[$i-1];
                            if ($type == 'aisle') {
                                echo '<div class="seat aisle seat-label">V</div>';
                            } else {
                                $seat_code = $row . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $is_sold = in_array($seat_code, $sold_seats);
                                $is_selected = in_array($seat_code, $selected_seats);
                                $seat_class = $type;
                                if ($is_sold) $seat_class = 'sold';
                                if ($is_selected) $seat_class = 'selected';
                                echo '<button type="button" class="seat '.$seat_class.'" data-seat="'.$seat_code.'" data-type="'.$type.'" '.($is_sold ? 'disabled' : '').'>'.$seat_code.'</button>';
                            }
                        endfor; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
        </div>
        <div class="container-fluid summary-box mt-4">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5>Chú thích màu sắc</h5>
                    <div>
                        <span class="legend-seat legend-normal"></span> Ghế thường
                        <span class="legend-seat legend-vip ms-3"></span> Ghế VIP
                        <span class="legend-seat legend-luxury ms-3"></span> LUXURY
                        <span class="legend-seat legend-sold ms-3"></span> Ghế đã bán
                        <span class="legend-seat legend-selected ms-3"></span> Ghế đang chọn
                        <span class="legend-seat legend-aisle ms-3"></span> Lối đi
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Danh sách ghế đã chọn</h5>
                    <div id="selectedSeats">
                        G09, G10
                    </div>
                    <div id="totalPrice" class="mt-2">
                        Giá vé: 170,000 VNĐ
                    </div>
                    <a id="btn-next" class="btn btn-next mt-3 px-4 py-2">Tiếp theo</a>
                </div>
            </div>
        </div>
    </div>
    <script src="/static/js/users/ChonGhe.js"></script>
</body>
</html>
<?php include __DIR__ . '/../../../layouts/users/Footer.php'; ?>
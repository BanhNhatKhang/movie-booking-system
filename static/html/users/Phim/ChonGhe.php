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
    <style>
        .seat-map { 
            display: flex; 
            flex-direction: column; 
            align-items: center; 

        }
        .seat-bg {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('/static/imgs/bgChonGhe.avif'); /* thay đường dẫn của bạn */
            background-size: cover;
            background-position: center;
            filter: brightness(0.5);
            z-index: 1;
        }

        .seat-content {
            position: relative;
            z-index: 2;
            padding: 2rem;
        }
        .seat-row { display: flex; margin-bottom: 6px; }
        .seat {
            width: 38px; height: 38px; margin: 2px; border-radius: 6px; color: #fff; font-weight: bold;
            display: flex; align-items: center; justify-content: center; cursor: pointer; border: none;
            transition: filter 0.2s;
        }
        .seat.normal { background: #2196f3; }
        .seat.vip { background: #e53935; }
        .seat.couple { background: #8bc34a; }
        .seat.luxury { background: #8e24aa; }
        .seat.sold { background: #bdbdbd; color: #333; cursor: not-allowed; }
        .seat.selected { background: #ffe600; color: #222; }
        .seat.aisle { background: #263238; color: #fff; cursor: default; }
        .seat:active:not(.sold):not(.aisle) { filter: brightness(0.8); }
        .screen { width: 70%; height: 8px; background: #fff; margin: 18px auto 24px; border-radius: 4px; }
        .legend-seat { display: inline-block; width: 22px; height: 22px; border-radius: 4px; margin-right: 6px; vertical-align: middle; }
        .legend-normal { background: #2196f3; }
        .legend-vip { background: #e53935; }
        .legend-couple { background: #8bc34a; }
        .legend-luxury { background: #8e24aa; }
        .legend-sold { background: #bdbdbd; }
        .legend-selected { background: #ffe600; border: 1px solid #aaa; }
        .legend-aisle { background: #263238; }
        .seat-label { font-size: 13px; }
        .summary-box { background: #fff; border-radius: 8px; padding: 24px 32px; margin-top: 24px; }
        .btn-next { background: linear-gradient(90deg, #ff4444, #ffe600); color: #222; border: none; font-weight: bold; }
        .btn-next:hover { background: linear-gradient(90deg, #cc0000, #ffe600); }
    </style>
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
                    'A' => ['normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'aisle'],
                    'B' => ['normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'aisle'],
                    'C' => ['normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'normal', 'aisle'],
                    'D' => ['vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'aisle'],
                    'E' => ['vip', 'vip', 'vip', 'vip', 'vip', 'sold', 'vip', 'sold', 'vip', 'vip', 'vip', 'vip', 'aisle'],
                    'F' => ['vip', 'vip', 'vip', 'vip', 'vip', 'sold', 'vip', 'sold', 'vip', 'vip', 'vip', 'vip', 'aisle'],
                    'G' => ['vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'vip', 'selected', 'selected', 'vip', 'vip', 'aisle'],
                    'H' => ['luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury', 'luxury'],
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
                            $seat_code = $row . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $is_sold = in_array($seat_code, $sold_seats);
                            $is_selected = in_array($seat_code, $selected_seats);
                            $seat_class = $type;
                            if ($is_sold) $seat_class = 'sold';
                            if ($is_selected) $seat_class = 'selected';
                            if ($type == 'aisle') {
                                echo '<div class="seat aisle seat-label">V</div>';
                            } else {
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
                    <button class="btn btn-next mt-3 px-4 py-2">Tiếp theo</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Giá ghế theo loại
        const seatPrices = { normal: 70000, vip: 90000, luxury: 120000, couple: 150000 };
        // Ghế đã bán (không chọn được)
        const soldSeats = ["E06","E08","F06","F08"];
        // Ghế đang chọn (demo)
        let selectedSeats = ["G09","G10"];
        // Cập nhật giao diện khi chọn ghế
        document.querySelectorAll('.seat').forEach(btn => {
            btn.addEventListener('click', function() {
                const seat = this.getAttribute('data-seat');
                const type = this.getAttribute('data-type');
                if (this.classList.contains('sold') || this.classList.contains('aisle')) return;
                if (this.classList.contains('selected')) {
                    this.classList.remove('selected');
                    selectedSeats = selectedSeats.filter(s => s !== seat);
                } else {
                    this.classList.add('selected');
                    selectedSeats.push(seat);
                }
                updateSummary();
            });
        });
        function updateSummary() {
            document.getElementById('selectedSeats').textContent = selectedSeats.length ? selectedSeats.join(', ') : 'Chưa chọn ghế';
            let total = 0;
            selectedSeats.forEach(seat => {
                let type = 'normal';
                if (seat.startsWith('A') || seat.startsWith('B') || seat.startsWith('C')) type = 'normal';
                else if (seat.startsWith('D') || seat.startsWith('E') || seat.startsWith('F') || seat.startsWith('G')) type = 'vip';
                else if (seat.startsWith('H')) type = 'luxury';
                total += seatPrices[type] || 0;
            });
            document.getElementById('totalPrice').textContent = 'Giá vé: ' + total.toLocaleString('vi-VN') + ' VNĐ';
        }
        updateSummary();
    </script>
</body>
</html>
<?php include __DIR__ . '/../../../layouts/users/Footer.php'; ?>
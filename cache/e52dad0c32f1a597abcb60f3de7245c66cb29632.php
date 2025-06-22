

<?php $__env->startSection('page-css'); ?>
    <link rel="stylesheet" href="/static/css/users/ChonGhe.css">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<main>
    <div class="container">
        <div class="bg-dark text-white py-2 px-3" style="border-top: 1px solid #ff4444;">
            <h3 class="text-center mb-0">Bước 1: Chọn Ghế</h3>
        </div>
        <div class="seat-map position-relative py-4">
            <div class="seat-bg"></div>
            <div class="seat-content position-relative z-2 text-center">
                <div class="mb-2 text-white fw-bold">Màn hình</div>
                
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
                $sold_seats = ['E06','E08','F06','F08','G09','G10'];
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
                    <a href="/thanh-toan" id="btn-next" class="btn btn-next mt-3 px-4 py-2">Tiếp theo</a>
                </div>
            </div>
        </div>
    </div>
</main>
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
    document.getElementById('btn-next').onclick = function(e) {
    e.preventDefault();
    const seats = selectedSeats.join(',');
    let total = 0;
    selectedSeats.forEach(seat => {
        let type = 'normal';
        if (seat.startsWith('A') || seat.startsWith('B') || seat.startsWith('C')) type = 'normal';
        else if (seat.startsWith('D') || seat.startsWith('E') || seat.startsWith('F') || seat.startsWith('G')) type = 'vip';
        else if (seat.startsWith('H')) type = 'luxury';
        total += seatPrices[type] || 0;
    });
    window.location.href = '/thanh-toan?seats=' + encodeURIComponent(seats) + '&total=' + total;
};
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.users.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Server\ct27501-project-BanhNhatKhang\app\Views/users-views/Phim/ChonGhe.blade.php ENDPATH**/ ?>
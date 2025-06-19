<?php
$activePage = 'PhongGhe';
$roomTypes = ['2D', 'IMAX', 'Event'];

// Danh sách phòng
$rooms = [
    1 => ['name'=>'Phòng 1', 'type'=>'2D'],
    2 => ['name'=>'Phòng 2', 'type'=>'IMAX'],
    3 => ['name'=>'Phòng 3', 'type'=>'Event'],
];

// Lấy phòng đang chọn
$roomId = isset($_GET['room']) && isset($rooms[$_GET['room']]) ? intval($_GET['room']) : 1;
$room = $rooms[$roomId];

// Sơ đồ ghế cho từng phòng
if ($roomId == 2) {
    // Phòng 2: 4 hàng, 5 cột
    $rows = ['A','B','C','D', 'E'];
    $cols = range(1,10);
} else {
    // Phòng 1 & 3: 8 hàng, 12 cột
    $rows = ['A','B','C','D','E','F','G','H'];
    $cols = range(1,12);
}

// Tạo dữ liệu ghế mẫu
$room['seats'] = [];
foreach($rows as $row) {
    foreach($cols as $col) {
        $code = $row . str_pad($col,2,'0',STR_PAD_LEFT);
        // Loại ghế
        if(in_array($row, ['A','B','C'])) $type = 'normal';
        elseif(in_array($row, ['D','E','F','G'])) $type = 'vip';
        else $type = 'luxury';
        // Trạng thái
        $status = 'available';
        $room['seats'][$code] = ['type'=>$type, 'status'=>$status];
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Ghế phòng chiếu</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <style>
        .seat-map-demo { font-family: monospace; font-size: 1.1em; }
        .seat-demo { display: inline-block; width: 38px; height: 38px; line-height: 38px; text-align: center; border-radius: 4px; margin: 2px; font-weight: bold; cursor: pointer; }
        .seat-normal { background: #2196f3; color: #fff; }
        .seat-vip { background: #e53935; color: #fff; }
        .seat-luxury { background: #8e24aa; color: #fff; }
        .seat-booked { background: #bdbdbd; color: #333; }
        .seat-selected { background: #ffe600; color: #222; }
        .seat-locked { background: #888; color: #fff; text-decoration: line-through; }
        .seat-label { font-size: 0.95em; }
        .action-btn { margin-right: 6px; }
        .seat-outline { outline: 2px solid #ffe600; }
    </style>
</head>
<body style="background: #f5f5f5;">
<div class="d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div>
        <h2 class="mb-4 text-center">Quản lý Ghế - <?= htmlspecialchars($room['name']) ?></h2>
        <!-- Chọn phòng -->
        <form class="row g-3 align-items-center mb-4 justify-content-center" method="get" action="">
            <div class="col-auto">
                <label class="form-label fw-bold">Chọn phòng:</label>
            </div>
            <div class="col-auto">
                <select name="room" class="form-select" onchange="this.form.submit()">
                    <?php foreach($rooms as $id=>$r): ?>
                        <option value="<?= $id ?>"<?= $roomId==$id?' selected':''; ?>><?= htmlspecialchars($r['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <!-- Chỉnh sửa phân loại phòng -->
        <form class="row g-3 align-items-center mb-4 justify-content-center" method="post" action="">
            <div class="col-auto">
                <label class="form-label fw-bold">Phân loại phòng:</label>
            </div>
            <div class="col-auto">
                <select name="room_type" class="form-select">
                    <?php foreach($roomTypes as $type): ?>
                        <option value="<?= $type ?>"<?= $room['type']==$type?' selected':''; ?>><?= $type ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary" type="submit" name="update_room_type">Cập nhật</button>
            </div>
        </form>
        <!-- Sơ đồ ghế -->
        <div class="seat-map-demo mb-3 text-center">
            <?php
            foreach($rows as $row) {
                foreach($cols as $col) {
                    $code = $row . str_pad($col,2,'0',STR_PAD_LEFT);
                    $seat = $room['seats'][$code];
                    $class = 'seat-demo seat-label ';
                    if($seat['status']=='booked') $class .= 'seat-booked';
                    elseif($seat['status']=='selected') $class .= 'seat-selected';
                    elseif($seat['status']=='locked') $class .= 'seat-locked';
                    else $class .= 'seat-' . $seat['type'];
                    echo "<span class='$class' data-seat='$code' data-type='{$seat['type']}' data-status='{$seat['status']}'>$code</span>";
                }
                echo "<br>";
            }
            ?>
        </div>
        <div class="mb-3 text-center">
            <button class="btn btn-warning btn-sm" id="btn-vip">Đổi sang VIP</button>
            <button class="btn btn-info btn-sm" id="btn-normal">Đổi sang Thường</button>
            <button class="btn btn-purple btn-sm" id="btn-luxury" style="background:#8e24aa;color:#fff;">Đổi sang LUXURY</button>
            <button class="btn btn-secondary btn-sm" id="btn-lock">Khóa ghế</button>
            <button class="btn btn-success btn-sm" id="btn-unlock">Mở khóa ghế</button>
        </div>
        <div class="mt-4 text-center">
            <h5>Chú thích:</h5>
            <span class="seat-demo seat-normal seat-label">A01</span> Ghế thường
            <span class="seat-demo seat-vip seat-label ms-3">D01</span> Ghế VIP
            <span class="seat-demo seat-luxury seat-label ms-3">H01</span> LUXURY
            <span class="seat-demo seat-booked seat-label ms-3">F06</span> Ghế đã bán
            <span class="seat-demo seat-selected seat-label ms-3">G09</span> Ghế đang chọn
            <span class="seat-demo seat-locked seat-label ms-3">A03</span> Ghế khóa (hư)
        </div>
        <div class="mt-4 text-center">
            <h5>Thống kê</h5>
            <div id="stat"></div>
        </div>
    </div>
</div>
<script>
    // Demo: chọn ghế để thao tác
    let selectedSeats = [];
    document.querySelectorAll('.seat-demo').forEach(seat => {
        seat.addEventListener('click', function() {
            this.classList.toggle('seat-outline');
            const code = this.getAttribute('data-seat');
            if(!code) return;
            if(selectedSeats.includes(code)) {
                selectedSeats = selectedSeats.filter(s => s !== code);
            } else {
                selectedSeats.push(code);
            }
        });
    });
    // Đổi loại ghế
    document.getElementById('btn-vip').onclick = function() {
        selectedSeats.forEach(code => {
            let el = document.querySelector('.seat-demo[data-seat="'+code+'"]');
            if(el && !el.classList.contains('seat-locked')) {
                el.classList.remove('seat-normal','seat-luxury');
                el.classList.add('seat-vip');
                el.setAttribute('data-type','vip');
            }
        });
        selectedSeats = [];
        clearSelection();
        updateStat();
    };
    document.getElementById('btn-normal').onclick = function() {
        selectedSeats.forEach(code => {
            let el = document.querySelector('.seat-demo[data-seat="'+code+'"]');
            if(el && !el.classList.contains('seat-locked')) {
                el.classList.remove('seat-vip','seat-luxury');
                el.classList.add('seat-normal');
                el.setAttribute('data-type','normal');
            }
        });
        selectedSeats = [];
        clearSelection();
        updateStat();
    };
    document.getElementById('btn-luxury').onclick = function() {
        selectedSeats.forEach(code => {
            let el = document.querySelector('.seat-demo[data-seat="'+code+'"]');
            if(el && !el.classList.contains('seat-locked')) {
                el.classList.remove('seat-normal','seat-vip');
                el.classList.add('seat-luxury');
                el.setAttribute('data-type','luxury');
            }
        });
        selectedSeats = [];
        clearSelection();
        updateStat();
    };
    // Khóa/mở khóa ghế (có lưu loại ghế cũ)
    document.getElementById('btn-lock').onclick = function() {
        selectedSeats.forEach(code => {
            let el = document.querySelector('.seat-demo[data-seat="'+code+'"]');
            if(el) {
                el.setAttribute('data-prev-type', el.getAttribute('data-type'));
                el.classList.remove('seat-normal','seat-vip','seat-luxury','seat-booked','seat-selected');
                el.classList.add('seat-locked');
                el.setAttribute('data-status','locked');
            }
        });
        selectedSeats = [];
        clearSelection();
        updateStat();
    };
    document.getElementById('btn-unlock').onclick = function() {
        selectedSeats.forEach(code => {
            let el = document.querySelector('.seat-demo[data-seat="'+code+'"]');
            if(el && el.classList.contains('seat-locked')) {
                let prevType = el.getAttribute('data-prev-type') || 'normal';
                el.classList.remove('seat-locked','seat-normal','seat-vip','seat-luxury');
                if(prevType === 'vip') {
                    el.classList.add('seat-vip');
                } else if(prevType === 'luxury') {
                    el.classList.add('seat-luxury');
                } else {
                    el.classList.add('seat-normal');
                }
                el.setAttribute('data-status','available');
                el.setAttribute('data-type', prevType);
                el.removeAttribute('data-prev-type');
            }
        });
        selectedSeats = [];
        clearSelection();
        updateStat();
    };
    function clearSelection() {
        document.querySelectorAll('.seat-outline').forEach(el => el.classList.remove('seat-outline'));
    }
    // Thống kê tỷ lệ lấp đầy
    function updateStat() {
        let total = 0, booked = 0, locked = 0, vip = 0, normal = 0, luxury = 0, selected = 0;
        document.querySelectorAll('.seat-demo[data-seat]').forEach(el => {
            total++;
            if(el.classList.contains('seat-locked')) locked++;
            else if(el.classList.contains('seat-booked')) booked++;
            else if(el.classList.contains('seat-selected')) selected++;
            if(el.classList.contains('seat-vip')) vip++;
            else if(el.classList.contains('seat-normal')) normal++;
            else if(el.classList.contains('seat-luxury')) luxury++;
        });
        let fillRate = total-locked > 0 ? ((booked/(total-locked))*100).toFixed(1) : 0;
        document.getElementById('stat').innerHTML =
            `Tổng ghế: <b>${total}</b> | Đã bán: <b>${booked}</b> | Đang chọn: <b>${selected}</b> | Đang khóa: <b>${locked}</b> | VIP: <b>${vip}</b> | Thường: <b>${normal}</b> | LUXURY: <b>${luxury}</b><br>
            <span class="text-primary">Tỷ lệ lấp đầy: <b>${fillRate}%</b></span>`;
    }
    updateStat();
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</body>
</html>
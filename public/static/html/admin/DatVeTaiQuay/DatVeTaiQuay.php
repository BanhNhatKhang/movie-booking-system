<?php 
    $activePage ='book-ticket';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đặt vé tại quầy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <style>
    .seat {
        width: 32px; height: 32px; margin: 4px;
        background: #eee; border-radius: 4px; border: 1px solid #ccc;
        display: inline-block; text-align: center; line-height: 32px; cursor: pointer;
        transition: background 0.2s;
    }
    .seat.selected { background: #198754; color: #fff; }
    .seat.sold { background: #dc3545; color: #fff; cursor: not-allowed; }
    </style>
</head>

<body>
    <div class="admin-layout">
        <?php include '../../../layouts/admin/Sidebar.php'; ?>

        <div class="main-content">
            <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>

            <main>
                <div class="container py-4 content">
                    <h1>Đặt vé tại quầy</h1>
                    <hr>
                    <form id="bookingForm">
                        <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Chọn Phim</label>
                            <select class="form-select" id="movieSelect" required>
                            <option value="">-- Chọn phim --</option>
                            <option value="1">Godzilla x Kong</option>
                            <option value="2">Inside Out 2</option>
                            <option value="3">Kungfu Panda 4</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Chọn Suất Chiếu</label>
                            <select class="form-select" id="showtimeSelect" required>
                            <option value="">-- Chọn suất chiếu --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Phòng</label>
                            <input type="text" class="form-control" id="roomInput" readonly>
                        </div>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Chọn Ghế</label>
                        <div id="seatMap">
                        </div>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Thông tin khách (không bắt buộc)</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Họ tên (tùy chọn)" id="customerName">
                            </div>
                            <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="Số điện thoại (tùy chọn)" id="customerPhone">
                            </div>
                        </div>
                        <div class="form-text">Không cần nhập nếu không muốn nhận vé điện tử hoặc hỗ trợ sau này.</div>
                        </div>
                        <div class="mb-3">
                        <label class="form-label">Thanh toán</label>
                        <div>
                            <span class="fw-bold text-success" id="totalAmount">0</span> VNĐ
                            <span class="ms-3 badge bg-secondary">Tiền mặt</span>
                        </div>
                        </div>
                        <button type="submit" class="btn btn-success">Xác nhận đặt vé & In vé</button>
                    </form>
                    <div class="mt-4" id="ticketResult" style="display:none;">
                        <h5>Vé đã đặt thành công!</h5>
                        <div id="ticketInfo" class="alert alert-info"></div>
                        <button class="btn btn-secondary" onclick="window.location.reload()">Đặt vé mới</button>
                    </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const showtimesData = {
        1: [ // Godzilla x Kong
            { time: '10:00', room: 'A1' },
            { time: '14:00', room: 'A1' },
            { time: '18:00', room: 'A2' }
        ],
        2: [ // Inside Out 2
            { time: '09:00', room: 'B1' },
            { time: '13:30', room: 'B1' },
            { time: '17:00', room: 'B2' }
        ],
        3: [ // Kungfu Panda 4
            { time: '11:00', room: 'C1' },
            { time: '15:30', room: 'C1' },
            { time: '20:00', room: 'C2' }
        ]
        };
        const soldSeats = {
        // key: movieId-showtime, value: array of sold seat codes
        '1-10:00': ['A2', 'A3', 'B4'],
        '2-13:30': ['C5', 'D1'],
        '3-20:00': ['F1', 'F2', 'F3']
        };
        const seatRows = ['A','B','C','D','E','F'];
        const seatCols = 6;
        const seatPrice = 70000;

        document.getElementById('movieSelect').addEventListener('change', function() {
        const movieId = this.value;
        const showtimeSelect = document.getElementById('showtimeSelect');
        showtimeSelect.innerHTML = '<option value="">-- Chọn suất chiếu --</option>';
        if (showtimesData[movieId]) {
            showtimesData[movieId].forEach((s, idx) => {
            const op = document.createElement('option');
            op.value = s.time;
            op.text = s.time + ' (' + s.room + ')';
            op.dataset.room = s.room;
            showtimeSelect.appendChild(op);
            });
        }
        document.getElementById('roomInput').value = '';
        document.getElementById('seatMap').innerHTML = '';
        updateTotal();
        });

        document.getElementById('showtimeSelect').addEventListener('change', function() {
        const movieId = document.getElementById('movieSelect').value;
        const showtime = this.value;
        const room = this.selectedOptions[0]?.dataset.room || '';
        document.getElementById('roomInput').value = room;
        if (movieId && showtime) {
            renderSeats(movieId, showtime);
        }
        updateTotal();
        });

        function renderSeats(movieId, showtime) {
        const seatMap = document.getElementById('seatMap');
        seatMap.innerHTML = '';
        const sold = soldSeats[`${movieId}-${showtime}`] || [];
        for (let r of seatRows) {
            for (let c = 1; c <= seatCols; c++) {
            const seatCode = r + c;
            const btn = document.createElement('span');
            btn.className = 'seat' + (sold.includes(seatCode) ? ' sold' : '');
            btn.innerText = seatCode;
            btn.dataset.seat = seatCode;
            if (!sold.includes(seatCode)) {
                btn.onclick = function() {
                btn.classList.toggle('selected');
                updateTotal();
                };
            }
            seatMap.appendChild(btn);
            }
            seatMap.appendChild(document.createElement('br'));
        }
        }

        function updateTotal() {
        const selectedSeats = document.querySelectorAll('.seat.selected');
        document.getElementById('totalAmount').textContent = seatPrice * selectedSeats.length;
        }

        document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Collect data
        const movie = document.getElementById('movieSelect');
        const showtime = document.getElementById('showtimeSelect');
        const room = document.getElementById('roomInput').value;
        const seats = Array.from(document.querySelectorAll('.seat.selected')).map(s => s.dataset.seat);
        if (!movie.value || !showtime.value || seats.length === 0) {
            alert('Vui lòng chọn phim, suất chiếu và ít nhất một ghế!');
            return;
        }
        const name = document.getElementById('customerName').value.trim();
        const phone = document.getElementById('customerPhone').value.trim();
        // Show ticket info
        document.getElementById('ticketResult').style.display = 'block';
        document.getElementById('bookingForm').style.display = 'none';
        document.getElementById('ticketInfo').innerHTML = `
            <b>Phim:</b> ${movie.options[movie.selectedIndex].text} <br>
            <b>Suất chiếu:</b> ${showtime.value} <br>
            <b>Phòng:</b> ${room} <br>
            <b>Ghế:</b> ${seats.join(', ')} <br>
            <b>Thanh toán:</b> ${(seats.length * seatPrice).toLocaleString()} VNĐ (tiền mặt)<br>
            ${name || phone ? `<b>Khách:</b> ${name ? name : ''} ${phone ? '('+phone+')' : ''}` : ''}
            <br><i>Giữ vé cẩn thận. Liên hệ quầy nếu cần hỗ trợ.</i>
        `;
        });
</script>
</body>
</html>

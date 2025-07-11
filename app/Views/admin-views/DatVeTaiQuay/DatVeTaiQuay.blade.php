@extends('layouts.admin.master')

@section('title', 'Đặt vé tại quầy')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <style>
        body, .card, .seat {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }
        
        .seat {
            width: 32px; height: 32px; margin: 1px;
            background: #e9ecef; border-radius: 5px; border: 1px solid #dee2e6;
            display: inline-block; text-align: center; line-height: 32px; 
            cursor: pointer; transition: all 0.2s; 
            font-size: 10px; font-weight: bold;
            font-family: 'Courier New', monospace !important; /* ✅ FONT RÕ RÀNG CHO GHẾ */
        }

        .seat:hover:not(.sold):not(.disabled) { 
            background: #adb5bd; 
            transform: scale(1.05);
        }

        .seat.normal { 
            background: #e9ecef; 
            border: 1px solid #dee2e6; 
        }

        .seat.vip { 
            background: #ffc107; 
            color: #000; 
            border-color: #ffc107;
        }

        .seat.luxury { 
            background: #6f42c1; 
            color: #fff; 
            border-color: #6f42c1;
        }

        .seat.couple { 
            background: #fd7e14; 
            color: #fff; 
            border-color: #fd7e14;
        }

        .seat.selected { 
            background: #198754 !important; 
            color: #fff !important; 
            border-color: #198754 !important;
            transform: scale(1.1);
        }

        .seat.sold { 
            background: #dc3545 !important; 
            color: #fff !important; 
            cursor: not-allowed !important; 
        }

        .seat.disabled {
            background: #f8f9fa !important;
            color: #6c757d !important;
            cursor: not-allowed !important;
            border-color: #e9ecef !important;
            opacity: 0.3;
        }

        .seat-row {
            margin-bottom: 8px;
            min-height: 40px;
        }

        .row-label {
            font-family: 'Arial', sans-serif !important;
            font-weight: bold !important;
            font-size: 14px !important;
        }

        /* ✅ RESPONSIVE CHO MÀN HÌNH NHỎ */
        @media (max-width: 1200px) {
            .seat {
                width: 28px;
                height: 28px;
                line-height: 28px;
                font-size: 9px;
            }
        }

        @media (max-width: 992px) {
            .seat {
                width: 25px;
                height: 25px;
                line-height: 25px;
                font-size: 8px;
            }
        }

        .screen {
            background: linear-gradient(to bottom, #6c757d, #495057);
            color: white; text-align: center; padding: 15px;
            margin: 20px 0; border-radius: 15px;
            font-size: 18px; font-weight: bold;
            font-family: 'Arial', sans-serif !important;
        }

        .legend { 
            display: flex; gap: 15px; justify-content: center; 
            margin: 15px 0; flex-wrap: wrap;
            font-family: 'Arial', sans-serif !important;
        }
        .legend-item { 
            display: flex; align-items: center; gap: 5px; 
            font-size: 14px;
        }
        .legend-seat { width: 20px; height: 20px; border-radius: 3px; }
        
        .ticket-summary { 
            background: #f8f9fa; border-radius: 8px; padding: 15px; 
            border-left: 4px solid #198754; 
            font-family: 'Arial', sans-serif !important;
        }
    </style>
@endsection

@section('content')
<div class="content p-4" style="background-color: white;">
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-ticket-perforated me-2"></i>Đặt vé tại quầy</h2>
        <div class="text-muted">
            <i class="bi bi-person-badge me-1"></i>
            Nhân viên: <strong>{{ $_SESSION['user_name'] ?? 'N/A' }}</strong>
        </div>
    </div>

    <div class="row">
        <!-- Form chọn phim và suất chiếu -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-film me-2"></i>Chọn phim & suất chiếu</h5>
                </div>
                <div class="card-body">
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label class="form-label">Phim:</label>
                            <select class="form-select" id="movieSelect" required>
                                <option value="">-- Chọn phim --</option>
                                @foreach($phimList as $phim)
                                    <option value="{{ $phim['p_maphim'] }}">{{ $phim['p_tenphim'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Suất chiếu:</label>
                            <select class="form-select" id="showtimeSelect" required disabled>
                                <option value="">-- Chọn suất chiếu --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Thông tin khách (tùy chọn):</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Tên khách hàng">
                            <input type="tel" class="form-control mt-2" id="customerPhone" placeholder="Số điện thoại">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tóm tắt vé -->
            <div class="card mt-3" id="ticketSummary" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-receipt me-2"></i>Tóm tắt đặt vé</h6>
                </div>
                <div class="card-body ticket-summary">
                    <div id="summaryContent"></div>
                    <hr>
                    <div class="d-grid">
                        <button type="button" class="btn btn-success" id="confirmBooking">
                            <i class="bi bi-check-circle me-2"></i>Xác nhận đặt vé
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sơ đồ ghế -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-grid-3x3 me-2"></i>Chọn ghế</h5>
                </div>
                <div class="card-body">
                    <div id="seatMapContainer" style="display: none;">
                        <div class="screen">
                            <i class="bi bi-camera-reels me-2"></i>MÀN HÌNH
                        </div>
                        
                        <div id="seatMap" class="text-center">
                            <!-- Ghế sẽ được render bằng JS -->
                        </div>
                        
                        <div class="legend">
                            <div class="legend-item">
                                <div class="legend-seat" style="background: #e9ecef; border: 1px solid #dee2e6;"></div>
                                <span>Thường (30k)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-seat" style="background: #ffc107;"></div>
                                <span>VIP (50k)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-seat" style="background: #6f42c1;"></div>
                                <span>Luxury (80k)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-seat" style="background: #fd7e14;"></div>
                                <span>Couple (110k)</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-seat" style="background: #198754;"></div>
                                <span>Đã chọn</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-seat" style="background: #dc3545;"></div>
                                <span>Đã bán</span>
                            </div>
                        </div>
                    </div>
                    
                    <div id="noSeatMessage" class="text-center text-muted py-5">
                        <i class="bi bi-arrow-left me-2"></i>Vui lòng chọn phim và suất chiếu để xem sơ đồ ghế
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Lấy dữ liệu trực tiếp từ PHP
var lichChieuData = <?= json_encode($lichChieuList ?? []) ?>;
var gheData = <?= json_encode($gheList ?? []) ?>;
var soldSeatsData = <?= json_encode($soldSeats ?? []) ?>;

let selectedSeats = [];
let currentShowtime = null;
let currentMovie = null;

// ✅ BIẾN TOÀN CỤC LƯU GIÁ TỪ SERVER
let seatPrices = {
    'normal': 70000,
    'vip': 90000,
    'luxury': 120000,
    'couple': 150000
};

document.addEventListener('DOMContentLoaded', function() {
    const movieSelect = document.getElementById('movieSelect');
    const showtimeSelect = document.getElementById('showtimeSelect');
    const seatMapContainer = document.getElementById('seatMapContainer');
    const noSeatMessage = document.getElementById('noSeatMessage');
    const ticketSummary = document.getElementById('ticketSummary');

    // Khi chọn phim
    movieSelect.addEventListener('change', function() {
        const movieId = this.value;
        currentMovie = movieId;
        
        if (movieId) {
            const showtimes = lichChieuData.filter(lc => lc.p_maphim === movieId);
            
            showtimeSelect.innerHTML = '<option value="">-- Chọn suất chiếu --</option>';
            showtimes.forEach(showtime => {
                const option = document.createElement('option');
                option.value = showtime.lc_malichchieu;
                option.textContent = `${showtime.lc_giobatdau} - ${showtime.pc_tenphong}`;
                showtimeSelect.appendChild(option);
            });
            
            showtimeSelect.disabled = false;
        } else {
            showtimeSelect.disabled = true;
            showtimeSelect.innerHTML = '<option value="">-- Chọn suất chiếu --</option>';
            hideSeats();
        }
        
        resetSelection();
    });

    // Khi chọn suất chiếu
    showtimeSelect.addEventListener('change', function() {
        const showtimeId = this.value;
        currentShowtime = showtimeId;
        
        if (showtimeId) {
            fetch(`/get-ghe-by-lich-chieu?lichchieu_id=${showtimeId}`)
                .then(response => response.json())
                .then(data => {
                    // ✅ CẬP NHẬT GIÁ GHẾ TỪ SERVER
                    if (data.giaBanVe) {
                        seatPrices = data.giaBanVe; // Giá theo loại
                    }
                    
                    // ✅ LƯU GIÁ CHI TIẾT TỪNG GHẾ
                    window.seatPricesDetail = data.giaBanVeChiTiet || {};
                    
                    console.log('Updated seat prices:', seatPrices);
                    console.log('Detailed seat prices:', window.seatPricesDetail);
                    
                    renderSeats(data.ghe, data.soldSeats);
                    updateLegend();
                    seatMapContainer.style.display = 'block';
                    noSeatMessage.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error loading seats:', error);
                    hideSeats();
                });
        } else {
            hideSeats();
        }
        
        resetSelection();
    });

    // Xác nhận đặt vé
    document.getElementById('confirmBooking').addEventListener('click', function() {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ghế!');
            return;
        }

        const formData = new FormData();
        formData.append('lichchieu', currentShowtime);
        formData.append('ghe', selectedSeats.map(seat => seat.id).join(','));
        formData.append('tongtien', calculateTotal());

        fetch('/dat-ve-tai-quay', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đặt vé thành công! Mã vé: ' + data.ticket_id);
                location.reload();
            } else {
                alert('Có lỗi: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra!');
        });
    });
});

// ✅ LOGIC RENDER GHẾ KHÔNG ĐỔI
function renderSeats(seats, soldSeatIds) {
    const seatMap = document.getElementById('seatMap');
    seatMap.innerHTML = '';

    if (!seats || seats.length === 0) {
        seatMap.innerHTML = '<div class="text-center text-muted">Không có ghế nào</div>';
        return;
    }

    // ✅ TỰ ĐỘNG PHÁT HIỆN SỐ HÀNG VÀ SỐ GHẾ TỪ DATABASE
    const seatsByRow = {};
    const maxCols = {};
    
    seats.forEach(seat => {
        // Lấy mã ghế cuối cùng (VD: A01, B15...)
        const seatCode = seat.g_maghe.split('_').pop();
        const row = seatCode.charAt(0); // A, B, C...
        const col = parseInt(seatCode.substring(1)); // 01, 02, 03...
        
        if (!seatsByRow[row]) {
            seatsByRow[row] = {};
        }
        seatsByRow[row][col] = seat;
        
        // Tính số ghế tối đa trong hàng
        if (!maxCols[row] || col > maxCols[row]) {
            maxCols[row] = col;
        }
    });
    
    // ✅ SẮP XẾP CÁC HÀNG A-O
    const allRows = Object.keys(seatsByRow).sort();
    
    allRows.forEach(row => {
        const rowDiv = document.createElement('div');
        rowDiv.className = 'seat-row mb-2 d-flex align-items-center justify-content-center';
        
        // Label hàng trái
        const rowLabel = document.createElement('span');
        rowLabel.textContent = row;
        rowLabel.className = 'me-3 fw-bold row-label';
        rowLabel.style.width = '25px';
        rowLabel.style.textAlign = 'center';
        rowDiv.appendChild(rowLabel);
        
        // Container cho ghế
        const seatsContainer = document.createElement('div');
        seatsContainer.className = 'd-flex justify-content-center flex-wrap';
        
        const maxColInRow = maxCols[row];
        
        // ✅ CHỈ TẠO GHẾ CÓ TRONG DATABASE
        for (let col = 1; col <= maxColInRow; col++) {
            const seatData = seatsByRow[row] && seatsByRow[row][col];
            
            if (seatData) { // ✅ CHỈ HIỆN GHẾ CÓ TRONG DB
                const seatNumber = String(col).padStart(2, '0');
                const seatCode = `${row}${seatNumber}`;
                
                const seatElement = document.createElement('span');
                seatElement.className = 'seat';
                seatElement.textContent = seatCode;
                seatElement.dataset.seatId = seatData.g_maghe;
                
                // ✅ SET LOẠI GHẾ THEO DATABASE
                const seatType = seatData.g_loaighe;
                
                if (seatType === 'vip') {
                    seatElement.classList.add('vip');
                } else if (seatType === 'luxury') {
                    seatElement.classList.add('luxury');
                } else if (seatType === 'couple') {
                    seatElement.classList.add('couple');
                } else {
                    seatElement.classList.add('normal');
                }
                
                // Kiểm tra ghế đã bán
                if (soldSeatIds.includes(seatData.g_maghe)) {
                    seatElement.classList.add('sold');
                    seatElement.style.cursor = 'not-allowed';
                } else {
                    seatElement.addEventListener('click', () => toggleSeat(seatData.g_maghe, seatElement, seatType));
                }
                
                // ✅ THÊM KHOẢNG CÁCH GIỮA CÁC NHÓM GHẾ
                if (col % 4 === 0 && col < maxColInRow) {
                    seatElement.style.marginRight = '8px';
                }
                
                seatsContainer.appendChild(seatElement);
            }
        }
        
        rowDiv.appendChild(seatsContainer);
        
        // Label hàng phải
        const rowLabelRight = document.createElement('span');
        rowLabelRight.textContent = row;
        rowLabelRight.className = 'ms-3 fw-bold row-label';
        rowLabelRight.style.width = '25px';
        rowLabelRight.style.textAlign = 'center';
        rowDiv.appendChild(rowLabelRight);
        
        seatMap.appendChild(rowDiv);
    });
}

function toggleSeat(seatId, seatElement, seatType) {
    if (seatElement.classList.contains('sold')) return;
    
    if (seatElement.classList.contains('selected')) {
        seatElement.classList.remove('selected');
        selectedSeats = selectedSeats.filter(seat => seat.id !== seatId);
    } else {
        seatElement.classList.add('selected');
        
        // ✅ DÙNG GIÁ CHI TIẾT TỪNG GHẾ NẾU CÓ
        let seatPrice = seatPrices[seatType] || seatPrices['normal'];
        if (window.seatPricesDetail && window.seatPricesDetail[seatId]) {
            seatPrice = window.seatPricesDetail[seatId];
        }
        
        selectedSeats.push({
            id: seatId,
            display: seatId.split('_').pop(),
            type: seatType || 'normal',
            price: seatPrice
        });
    }
    
    updateSummary();
}

function updateSummary() {
    if (selectedSeats.length === 0) {
        document.getElementById('ticketSummary').style.display = 'none';
        return;
    }

    const movieName = document.getElementById('movieSelect').options[document.getElementById('movieSelect').selectedIndex].text;
    const showtimeText = document.getElementById('showtimeSelect').options[document.getElementById('showtimeSelect').selectedIndex].text;
    const total = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);

    document.getElementById('summaryContent').innerHTML = `
        <div><strong>Phim:</strong> ${movieName}</div>
        <div><strong>Suất chiếu:</strong> ${showtimeText}</div>
        <div><strong>Ghế:</strong> ${selectedSeats.map(s => s.display).join(', ')}</div>
        <div><strong>Số lượng:</strong> ${selectedSeats.length} vé</div>
        <div class="mt-2"><strong>Tổng tiền:</strong> <span class="text-success fs-5">${total.toLocaleString()} VNĐ</span></div>
    `;
    
    document.getElementById('ticketSummary').style.display = 'block';
}

// ✅ SỬA HÀM calculateTotal() ĐỂ DÙNG LOGIC ĐÚNG
function calculateTotal() {
    return selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
}

function hideSeats() {
    document.getElementById('seatMapContainer').style.display = 'none';
    document.getElementById('noSeatMessage').style.display = 'block';
}

function resetSelection() {
    selectedSeats = [];
    document.getElementById('ticketSummary').style.display = 'none';
    document.querySelectorAll('.seat.selected').forEach(seat => {
        seat.classList.remove('selected');
    });
}

// ✅ CẬP NHẬT LEGEND VỚI GIÁ ĐỘNG
function updateLegend() {
    const legendItems = [
        { class: 'normal', bg: '#e9ecef', text: `Thường (${(seatPrices.normal/1000)}k)` },
        { class: 'vip', bg: '#ffc107', text: `VIP (${(seatPrices.vip/1000)}k)` },
        { class: 'luxury', bg: '#6f42c1', text: `Luxury (${(seatPrices.luxury/1000)}k)` },
        { class: 'couple', bg: '#fd7e14', text: `Couple (${(seatPrices.couple/1000)}k)` },
        { class: 'selected', bg: '#198754', text: 'Đã chọn' },
        { class: 'sold', bg: '#dc3545', text: 'Đã bán' }
    ];
    
    const legendContainer = document.querySelector('.legend');
    if (legendContainer) {
        legendContainer.innerHTML = legendItems.map(item => `
            <div class="legend-item">
                <div class="legend-seat" style="background: ${item.bg};"></div>
                <span>${item.text}</span>
            </div>
        `).join('');
    }
}
</script>
@endsection
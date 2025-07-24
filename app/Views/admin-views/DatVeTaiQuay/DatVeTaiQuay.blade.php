@extends('layouts.admin.master')

@section('title', 'Đặt vé tại quầy')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/DatVeTaiQuay.css" rel="stylesheet">   
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

                        <!-- <div class="mb-3">
                            <label class="form-label">Thông tin khách (tùy chọn):</label>
                            <input type="text" class="form-control" id="customerName" placeholder="Tên khách hàng">
                            <input type="tel" class="form-control mt-2" id="customerPhone" placeholder="Số điện thoại">
                        </div> -->
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

// DEBUG DỮ LIỆU NGAY TẠI ĐÂY
console.log("=== DEBUGGING DATA ===");
console.log("📊 Lich chieu raw data:", lichChieuData);
if (lichChieuData.length > 0) {
    console.log("🔍 First showtime structure:", lichChieuData[0]);
    console.log("🔍 Available properties:", Object.keys(lichChieuData[0]));
}

let selectedSeats = [];
let currentShowtime = null;
let currentMovie = null;

// ✅ SỬA PHẦN XỬ LÝ KHI CHỌN PHIM
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
        
        console.log('🎬 Selected movie:', movieId);
        console.log('📋 All available showtimes:', lichChieuData);
        
        if (movieId) {
            const showtimes = lichChieuData.filter(lc => lc.p_maphim === movieId);
            console.log('🎯 Filtered showtimes for movie', movieId, ':', showtimes);
            
            showtimeSelect.innerHTML = '<option value="">-- Chọn suất chiếu --</option>';
            
            if (showtimes.length === 0) {
                showtimeSelect.innerHTML += '<option value="" disabled>Không có suất chiếu cho phim này</option>';
                console.log('❌ No showtimes found for movie:', movieId);
            } else {
                // ✅ SỬA PHẦN TẠO OPTION
                showtimes.forEach((showtime, index) => {
                    console.log(`🎪 Processing showtime ${index}:`, showtime);
                    
                    const option = document.createElement('option');
                    option.value = showtime.lc_malichchieu;
                    
                    // ✅ XỬ LÝ CÁC FIELD CÓ THỂ NULL/UNDEFINED
                    const ngayChieu = showtime.lc_ngaychieu || 'N/A';
                    
                    // ✅ XỬ LÝ THỜI GIAN
                    let gioChieu = 'N/A';
                    if (showtime.gio_chieu) {
                        gioChieu = showtime.gio_chieu;
                    } else if (showtime.lc_giobatdau) {
                        // Trích xuất giờ từ timestamp
                        const timestamp = showtime.lc_giobatdau.toString();
                        if (timestamp.includes(' ')) {
                            gioChieu = timestamp.split(' ')[1].substring(0, 5);
                        } else if (timestamp.includes(':')) {
                            gioChieu = timestamp.substring(0, 5);
                        }
                    }
                    
                    let tenPhong = showtime.pc_tenphong;
                    if (!tenPhong || tenPhong === 'Phòng không xác định') {
                        // Nếu không có tên phòng, tạo tên từ mã phòng
                        if (showtime.pc_maphongchieu) {
                            const roomNumber = showtime.pc_maphongchieu.replace('PC', '').replace(/^0+/, '');
                            tenPhong = `Phòng ${roomNumber}`;
                        } else {
                            tenPhong = 'Phòng N/A';
                        }
                    }
                    option.textContent = `${ngayChieu} ${gioChieu} - ${tenPhong}`;
                    
                    console.log(`✅ Final option text: "${option.textContent}"`);
                    
                    showtimeSelect.appendChild(option);
                });
            }
            
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
        
        console.log('🎫 Selected showtime:', showtimeId);
        
        if (showtimeId) {
            console.log('📞 Fetching seats for showtime:', showtimeId);
            
            fetch(`/get-ghe-by-lich-chieu?lichchieu_id=${showtimeId}`)
                .then(response => {
                    console.log('📡 Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('🪑 Received seat data:', data);
                    
                    // ✅ CẬP NHẬT GIÁ GHẾ TỪ SERVER
                    if (data.giaBanVe) {
                        seatPrices = data.giaBanVe;
                        console.log('💰 Updated seat prices:', seatPrices);
                    }
                    
                    // ✅ LƯU GIÁ CHI TIẾT TỪNG GHẾ
                    window.seatPricesDetail = data.giaBanVeChiTiet || {};
                    console.log('💸 Detailed seat prices:', window.seatPricesDetail);
                    
                    renderSeats(data.ghe, data.soldSeats);
                    updateLegend();
                    seatMapContainer.style.display = 'block';
                    noSeatMessage.style.display = 'none';
                })
                .catch(error => {
                    console.error('💥 Error loading seats:', error);
                    hideSeats();
                    alert('Lỗi tải dữ liệu ghế: ' + error.message);
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

        // ✅ HIỂN THỊ MODAL CHỌN PHƯƠNG THỨC THANH TOÁN
        showPaymentModal();
    });

    // ✅ SỬA FUNCTION showPaymentModal()
    function showPaymentModal() {
        const movieName = document.getElementById('movieSelect').options[document.getElementById('movieSelect').selectedIndex].text;
        const showtimeText = document.getElementById('showtimeSelect').options[document.getElementById('showtimeSelect').selectedIndex].text;
        const customerName = document.getElementById('customerName').value || 'Khách vãng lai';
        const customerPhone = document.getElementById('customerPhone').value || '';
        const total = calculateTotal();

        // ✅ TẠO MODAL THANH TOÁN
        const modalHtml = `
            <div class="modal fade" id="paymentModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-credit-card me-2"></i>Xác nhận thanh toán
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Thông tin đặt vé -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">📋 Thông tin đặt vé</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>🎬 Phim:</strong> ${movieName}</p>
                                            <p><strong>🎫 Suất chiếu:</strong> ${showtimeText}</p>
                                            <p><strong>🪑 Ghế:</strong> ${selectedSeats.map(s => s.display).join(', ')}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>👤 Khách hàng:</strong> ${customerName}</p>
                                            <p><strong>📞 Điện thoại:</strong> ${customerPhone || 'Không có'}</p>
                                            <p><strong>💰 Tổng tiền:</strong> <span class="text-success fs-5">${total.toLocaleString()} VNĐ</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chọn phương thức thanh toán -->
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">💳 Phương thức thanh toán</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Tiền mặt -->
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-option">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="Tiền mặt" checked>
                                                <label class="form-check-label payment-label" for="cash">
                                                    <div class="d-flex align-items-center">
                                                        <div class="payment-icon bg-success text-white me-3">
                                                            <i class="bi bi-cash-stack"></i>
                                                        </div>
                                                        <div>
                                                            <strong>Tiền mặt</strong><br>
                                                            <small class="text-muted">Thanh toán tại quầy</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Ngân hàng -->
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-option">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="bank" value="Ngân hàng">
                                                <label class="form-check-label payment-label" for="bank">
                                                    <div class="d-flex align-items-center">
                                                        <div class="payment-icon bg-primary text-white me-3">
                                                            <i class="bi bi-credit-card"></i>
                                                        </div>
                                                        <div>
                                                            <strong>Thẻ ngân hàng</strong><br>
                                                            <small class="text-muted">Visa/MasterCard</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- MoMo -->
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-option">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="momo" value="MoMo">
                                                <label class="form-check-label payment-label" for="momo">
                                                    <div class="d-flex align-items-center">
                                                        <div class="payment-icon bg-danger text-white me-3">
                                                            <i class="bi bi-wallet2"></i>
                                                        </div>
                                                        <div>
                                                            <strong>MoMo</strong><br>
                                                            <small class="text-muted">Ví điện tử</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- VNPay -->
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-option">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="vnpay" value="VNPay">
                                                <label class="form-check-label payment-label" for="vnpay">
                                                    <div class="d-flex align-items-center">
                                                        <div class="payment-icon bg-info text-white me-3">
                                                            <i class="bi bi-qr-code"></i>
                                                        </div>
                                                        <div>
                                                            <strong>VNPay</strong><br>
                                                            <small class="text-muted">QR Code</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>

                                        <!-- ZaloPay -->
                                        <div class="col-md-6 mb-3">
                                            <div class="payment-option">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="zalopay" value="ZaloPay">
                                                <label class="form-check-label payment-label" for="zalopay">
                                                    <div class="d-flex align-items-center">
                                                        <div class="payment-icon bg-warning text-white me-3">
                                                            <i class="bi bi-phone"></i>
                                                        </div>
                                                        <div>
                                                            <strong>ZaloPay</strong><br>
                                                            <small class="text-muted">Ví Zalo</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="bi bi-x-circle me-2"></i>Hủy
                            </button>
                            <button type="button" class="btn btn-success" id="processPayment">
                                <i class="bi bi-printer me-2"></i>Thanh toán & In vé
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // ✅ THÊM MODAL VÀO BODY
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        
        // ✅ HIỂN THỊ MODAL
        const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
        modal.show();

        // ✅ XỬ LÝ KHI BẤMM THANH TOÁN
        document.getElementById('processPayment').addEventListener('click', function() {
            processPaymentAndPrint();
        });

        // ✅ XỬ LÝ CLICK VÀO PAYMENT OPTION
        document.querySelectorAll('.payment-label').forEach(label => {
            label.addEventListener('click', function() {
                // Remove active class from all options
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                // Add active class to selected option
                this.closest('.payment-option').classList.add('active');
            });
        });

        // ✅ XÓA MODAL KHI ĐÓNG
        document.getElementById('paymentModal').addEventListener('hidden.bs.modal', function() {
            this.remove();
        });
    }

    // ✅ SỬA XỬ LÝ THANH TOÁN - BỎ GHI CHÚ
    function processPaymentAndPrint() {
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        const customerName = document.getElementById('customerName').value || 'Khách vãng lai';
        const customerPhone = document.getElementById('customerPhone').value || '';

        const bookingData = {
            showtime_id: currentShowtime,
            selected_seats: selectedSeats,
            customer_info: {
                name: customerName,
                phone: customerPhone
            },
            payment_method: paymentMethod,
            total_amount: calculateTotal()
        };

        console.log('💳 Processing payment:', bookingData);

        // ✅ HIỂN THỊ LOADING
        document.getElementById('processPayment').innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Đang xử lý...';
        document.getElementById('processPayment').disabled = true;

        fetch('/dat-ve-tai-quay/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(bookingData)
        })
        .then(response => response.json())
        .then(data => {
            console.log('📨 Payment response:', data);
            
            if (data.success) {
                // ✅ ĐÓNG MODAL THANH TOÁN
                bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
                
                // ✅ HIỂN THỊ VÉ ĐỂ IN
                showPrintTicket(data);
            } else {
                alert('Lỗi thanh toán: ' + data.message);
                document.getElementById('processPayment').disabled = false;
                document.getElementById('processPayment').innerHTML = '<i class="bi bi-printer me-2"></i>Thanh toán & In vé';
            }
        })
        .catch(error => {
            console.error('💥 Payment error:', error);
            alert('Lỗi kết nối: ' + error.message);
            document.getElementById('processPayment').disabled = false;
            document.getElementById('processPayment').innerHTML = '<i class="bi bi-printer me-2"></i>Thanh toán & In vé';
        });
    }

    // ✅ HIỂN THỊ VÉ ĐỂ IN
    function showPrintTicket(data) {
        const printHtml = `
            <div class="modal fade" id="printModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-check-circle me-2"></i>Đặt vé thành công
                            </h5>
                        </div>
                        <div class="modal-body" id="ticketContent">
                            ${generateTicketHTML(data)}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="window.print()">
                                <i class="bi bi-printer me-2"></i>In vé
                            </button>
                            <button type="button" class="btn btn-success" onclick="location.reload()">
                                <i class="bi bi-arrow-clockwise me-2"></i>Đặt vé mới
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', printHtml);
        const modal = new bootstrap.Modal(document.getElementById('printModal'));
        modal.show();
    }

    // ✅ TẠO HTML VÉ ĐỂ IN
    function generateTicketHTML(data) {
        const movieName = document.getElementById('movieSelect').options[document.getElementById('movieSelect').selectedIndex].text;
        const showtimeText = document.getElementById('showtimeSelect').options[document.getElementById('showtimeSelect').selectedIndex].text;
        const customerName = document.getElementById('customerName').value || 'Khách vãng lai';

        return `
            <div class="ticket-print">
                <div class="text-center mb-4">
                    <h3>🎬 CINEMA BOOKING SYSTEM</h3>
                    <h5>VÉ XEM PHIM</h5>
                    <hr>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Mã thanh toán:</strong> ${data.payment_id}</p>
                        <p><strong>Khách hàng:</strong> ${customerName}</p>
                        <p><strong>Phim:</strong> ${movieName}</p>
                        <p><strong>Suất chiếu:</strong> ${showtimeText}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Ghế:</strong> ${selectedSeats.map(s => s.display).join(', ')}</p>
                        <p><strong>Số lượng:</strong> ${selectedSeats.length} vé</p>
                        <p><strong>Tổng tiền:</strong> ${data.total_amount?.toLocaleString()} VNĐ</p>
                        <p><strong>Thời gian:</strong> ${new Date().toLocaleString()}</p>
                    </div>
                </div>
                
                <hr>
                <div class="text-center">
                    <small>Cảm ơn quý khách! Chúc quý khách xem phim vui vẻ!</small>
                </div>
            </div>
        `;
    }
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
        // { class: 'couple', bg: '#fd7e14', text: `Couple (${(seatPrices.couple/1000)}k)` },
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
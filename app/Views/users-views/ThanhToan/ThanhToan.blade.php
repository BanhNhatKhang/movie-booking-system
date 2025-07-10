@extends('layouts.users.master')

@section('title', 'Thanh Toán Vé')

@section('page-css')
<style>
/* Override CSS trực tiếp */
.payment-page {
    background: #1a1a1a !important;
    min-height: 100vh !important;
    padding: 20px 0 !important;
    color: #ffffff !important;
}

.payment-card {
    background: #2d2d2d !important;
    border-radius: 15px !important;
    padding: 30px !important;
    margin-bottom: 20px !important;
    border: 1px solid #444 !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important;
}

.payment-title {
    color: #ff4444 !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    margin-bottom: 25px !important;
    text-align: center !important;
    border-bottom: 2px solid #ff4444 !important;
    padding-bottom: 15px !important;
}

.movie-section {
    display: flex !important;
    gap: 20px !important;
    margin-bottom: 25px !important;
}

.movie-poster {
    width: 120px !important;
    height: 160px !important;
    border-radius: 10px !important;
    object-fit: cover !important;
    border: 2px solid #ff4444 !important;
}

.movie-info {
    flex: 1 !important;
}

.movie-info h3 {
    color: #ff4444 !important;
    font-size: 20px !important;
    margin-bottom: 10px !important;
}

.movie-info p {
    color: #cccccc !important;
    margin: 5px 0 !important;
}

.movie-info strong {
    color: #ffffff !important;
}

.showtime-section {
    background: #3d3d3d !important;
    padding: 20px !important;
    border-radius: 10px !important;
    margin-bottom: 20px !important;
}

.showtime-section h4 {
    color: #ff4444 !important;
    margin-bottom: 15px !important;
}

.showtime-details {
    display: flex !important;
    gap: 30px !important;
    justify-content: center !important;
}

.showtime-item {
    text-align: center !important;
}

.showtime-item strong {
    color: #ffd700 !important;
    font-size: 18px !important;
}

.seats-section {
    background: #3d3d3d !important;
    padding: 20px !important;
    border-radius: 10px !important;
}

.seats-section h4 {
    color: #ff4444 !important;
    margin-bottom: 15px !important;
}

.seats-list {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 10px !important;
    justify-content: center !important;
}

.seat-badge {
    background: #ff4444 !important;
    color: #ffffff !important;
    padding: 8px 15px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
}

.payment-summary {
    background: #2d2d2d !important;
    border-radius: 15px !important;
    padding: 30px !important;
    border: 1px solid #444 !important;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important;
    position: sticky !important;
    top: 20px !important;
}

.summary-title {
    color: #ff4444 !important;
    font-size: 20px !important;
    font-weight: 700 !important;
    text-align: center !important;
    margin-bottom: 20px !important;
    border-bottom: 2px solid #ff4444 !important;
    padding-bottom: 10px !important;
}

.price-item {
    display: flex !important;
    justify-content: space-between !important;
    padding: 8px 0 !important;
    border-bottom: 1px solid #444 !important;
    color: #cccccc !important;
}

.price-total {
    display: flex !important;
    justify-content: space-between !important;
    padding: 15px 0 !important;
    margin-top: 15px !important;
    border-top: 2px solid #ff4444 !important;
    font-size: 18px !important;
    font-weight: 700 !important;
}

.total-amount {
    color: #ffd700 !important;
    font-size: 20px !important;
}

.payment-method {
    margin-bottom: 10px !important;
}

.payment-method input[type="radio"] {
    display: none !important;
}

.payment-method label {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
    padding: 12px 15px !important;
    border: 2px solid #444 !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    background: rgba(255,255,255,0.05) !important;
    color: #cccccc !important;
}

.payment-method label:hover {
    border-color: #ff4444 !important;
    background: rgba(255,68,68,0.1) !important;
}

.payment-method input[type="radio"]:checked + label {
    border-color: #ff4444 !important;
    background: rgba(255,68,68,0.2) !important;
    color: #ffffff !important;
}


.btn-actions {
    display: flex !important;
    gap: 10px !important;
    margin-top: 20px !important;
}

.btn {
    flex: 1 !important;
    padding: 12px !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
}

.btn-secondary {
    background: #6c757d !important;
    color: #ffffff !important;
}

.btn-secondary:hover {
    background: #5a6268 !important;
}

.btn-primary {
    background: linear-gradient(135deg, #ff4444, #ff6b6b) !important;
    color: #ffffff !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #ff6b6b, #ff4444) !important;
}

@media (max-width: 768px) {
    .movie-section {
        flex-direction: column !important;
        text-align: center !important;
    }
    
    .showtime-details {
        flex-direction: column !important;
        gap: 10px !important;
    }
    
    .btn-actions {
        flex-direction: column !important;
    }
}
</style>
@endsection

@section('content')
<div class="payment-page">
    <div class="container">
        <div class="row">
            <!-- Cột trái: Thông tin đặt vé -->
            <div class="col-md-8">
                <div class="payment-card">
                    <h2 class="payment-title">Thông tin đặt vé</h2>
                    
                    <!-- Thông tin phim -->
                    <div class="movie-section">
                        @php
                            $posterUrl = $lichChieu['p_poster'] ?? '/static/images/default-poster.jpg';
                            
                            // Kiểm tra xem poster có tồn tại không
                            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $posterUrl)) {
                                $posterUrl = 'https://via.placeholder.com/120x160/333333/ffffff?text=No+Image';
                            }
                        @endphp
                        
                        <img src="{{ $posterUrl }}" 
                             alt="{{ $lichChieu['p_tenphim'] ?? 'Phim' }}" 
                             class="movie-poster"
                             onerror="this.src='https://via.placeholder.com/120x160/333333/ffffff?text=No+Image'">
                        
                        <div class="movie-info">
                            <h3>{{ $lichChieu['p_tenphim'] ?? 'Tên phim không xác định' }}</h3>
                            <p><strong>Thể loại:</strong> {{ $lichChieu['p_theloai'] ?? 'Chưa có thông tin' }}</p>
                            <p><strong>Thời lượng:</strong> {{ $lichChieu['p_thoiluong'] ?? 'N/A' }} phút</p>
                            <p><strong>Đạo diễn:</strong> {{ $lichChieu['p_daodien'] ?? 'N/A' }}</p>
                            <p><strong>Phòng chiếu:</strong> {{ $lichChieu['pc_tenphong'] ?? 'N/A' }}</p>
                            <p><strong>Loại phòng:</strong> {{ $lichChieu['pc_loaiphong'] ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <!-- Lịch chiếu -->
                    <div class="showtime-section">
                        <h4>Lịch chiếu</h4>
                        <div class="showtime-details">
                            <div class="showtime-item">
                                <p><strong>{{ date('d/m/Y', strtotime($lichChieu['lc_ngaychieu'])) }}</strong></p>
                                <p>Ngày chiếu</p>
                            </div>
                            <div class="showtime-item">
                                <p><strong>{{ date('H:i', strtotime($lichChieu['lc_giobatdau'])) }}</strong></p>
                                <p>Giờ chiếu</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Ghế đã chọn -->
                    <div class="seats-section">
                        <h4>Ghế đã chọn</h4>
                        <div class="seats-list">
                            @foreach($seatDetails as $seat)
                                <span class="seat-badge">
                                    {{ $seat['display'] }} ({{ ucfirst($seat['type']) }}) - {{ number_format($seat['price'], 0, ',', '.') }}đ
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cột phải: Thanh toán -->
            <div class="col-md-4">
                <div class="payment-summary">
                    <h3 class="summary-title">Tổng cộng</h3>
                    
                    <!-- Chi tiết giá -->
                    <div class="price-breakdown">
                        @foreach($seatDetails as $seat)
                            <div class="price-item">
                                <span>{{ $seat['display'] }} ({{ ucfirst($seat['type']) }})</span>
                                <span>{{ number_format($seat['price'], 0, ',', '.') }}đ</span>
                            </div>
                        @endforeach
                        
                        <div class="price-total">
                            <span>Tổng tiền:</span>
                            <span class="total-amount">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    
                    <!-- Phương thức thanh toán -->
                    <h4 style="color: #ffffff; margin-bottom: 15px;">Phương thức thanh toán</h4>
                    
                    <div class="payment-method">
                        <input type="radio" id="momo" name="payment_method" value="momo" checked>
                        <label for="momo">
                            <i class="fab fa-cc-paypal" style="color: #ff4444;"></i>
                            <span>Ví MoMo</span>
                        </label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="vnpay" name="payment_method" value="vnpay">
                        <label for="vnpay">
                            <i class="fas fa-credit-card" style="color: #ff4444;"></i>
                            <span>VNPay</span>
                        </label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="zalopay" name="payment_method" value="zalopay">
                        <label for="zalopay">
                            <i class="fas fa-mobile-alt" style="color: #ff4444;"></i>
                            <span>ZaloPay</span>
                        </label>
                    </div>
                    
                    <div class="payment-method">
                        <input type="radio" id="bank" name="payment_method" value="bank">
                        <label for="bank">
                            <i class="fas fa-university" style="color: #ff4444;"></i>
                            <span>Chuyển khoản ngân hàng</span>
                        </label>
                    </div>
                    
                    <!-- Nút hành động -->
                    <div class="payment-actions">
                        <button type="button" class="btn btn-secondary" onclick="goBack()">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </button>
                        
                        <button type="button" class="btn btn-danger" onclick="processPayment()">
                            <i class="fas fa-credit-card"></i> Thanh toán
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal loading -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
      <div class="fw-bold fs-5">Đang xử lý thanh toán...</div>
    </div>
  </div>
</div>

<!-- Modal cảm ơn -->
<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <div class="mb-3">
        <i class="fa fa-check-circle text-success" style="font-size: 3rem;"></i>
      </div>
      <div class="fw-bold fs-4 mb-2">Cảm ơn bạn đã đặt vé!</div>
      <div class="success-message mb-2">Chúc bạn xem phim vui vẻ!<br>Vé đã được lưu trong lịch sử đặt vé của bạn.</div>
      <div class="mb-3">
        <span class="badge bg-info text-dark">Mã thanh toán: <span id="paymentCode">-</span></span>
      </div>
      <button class="btn btn-success px-4" onclick="goToMyTickets()">Xem lịch sử đặt vé</button>
    </div>
  </div>
</div>

<!-- Data for JavaScript -->
<script type="application/json" id="payment-data">
{!! json_encode([
    'lichChieuId' => $lichChieuId,
    'seatDetails' => $seatDetails,
    'total' => $total,
    'user' => $user
]) !!}
</script>
@endsection

@section('page-js')
<script src="/static/js/users/ThanhToan.js"></script>
@endsection
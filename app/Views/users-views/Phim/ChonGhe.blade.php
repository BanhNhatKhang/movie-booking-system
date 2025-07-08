@extends('layouts.users.master')

@section('title', 'Chọn ghế')
@section('page-css')
    <link rel="stylesheet" href="/static/css/users/ChonGhe.css">
@endsection

@section('content')
<main>
    <div class="container">
        <div class="bg-dark text-white py-2 px-3" style="border-top: 1px solid #ff4444;">
            <h3 class="text-center mb-0">Bước 1: Chọn Ghế</h3>
        </div>
        
        <!-- Thông tin suất chiếu -->
        <div class="bg-light border p-3 mb-3">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-1">{{ $lichChieu['p_tenphim'] ?? 'Phim không xác định' }}</h5>
                    <p class="mb-1">
                        <i class="bi bi-calendar"></i> 
                        {{ date('d/m/Y', strtotime($lichChieu['lc_ngaychieu'])) }} - 
                        {{ date('H:i', strtotime($lichChieu['lc_giobatdau'])) }}
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-geo-alt"></i> 
                        {{ $lichChieu['pc_tenphong'] ?? 'Phòng không xác định' }} 
                        ({{ $lichChieu['pc_loaiphong'] ?? '' }})
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="mb-1"><strong>Thời lượng:</strong> {{ $lichChieu['p_thoiluong'] ?? 0 }} phút</p>
                    <p class="mb-0"><strong>Trạng thái:</strong> 
                        <span class="badge bg-success">{{ $lichChieu['lc_trangthai'] }}</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="seat-map position-relative py-4">
            <div class="seat-bg"></div>
            <div class="seat-content position-relative z-2 text-center">
                <div class="mb-4">
                    <div class="screen"></div>
                    <div class="text-white fw-bold">Màn hình</div>
                </div>
                
                {{-- Hiển thị sơ đồ ghế từ database --}}
                @if(!empty($gheByRow))
                    @foreach($gheByRow as $row => $seats)
                        <div class="seat-row" data-row="{{ $row }}">
                            <span class="row-label text-white me-2">{{ $row }}</span>
                            @foreach($seats as $seat)
                                @php
                                    $seatClass = $seat['loai_ghe']; // normal, vip, luxury
                                    
                                    if ($seat['trang_thai'] === 'sold' || $seat['trang_thai'] === 'booked') {
                                        $seatClass = 'sold';
                                    }
                                    
                                    $isDisabled = in_array($seat['trang_thai'], ['sold', 'booked', 'maintenance']);
                                @endphp
                                
                                <button type="button" 
                                        class="seat {{ $seatClass }}" 
                                        data-seat="{{ $seat['ma_ghe'] }}" 
                                        data-display="{{ $seat['display_code'] }}"
                                        data-type="{{ $seat['loai_ghe'] }}"
                                        data-price="{{ $giaBanVe[$seat['loai_ghe']] ?? 0 }}"
                                        {{ $isDisabled ? 'disabled' : '' }}
                                        title="Ghế {{ $seat['display_code'] }} - {{ ucfirst($seat['loai_ghe']) }} ({{ number_format($giaBanVe[$seat['loai_ghe']] ?? 0) }}đ)">
                                    {{ $seat['display_code'] }}
                                </button>
                            @endforeach
                            <span class="row-label text-white ms-2">{{ $row }}</span>
                        </div>
                    @endforeach
                @else
                    <div class="text-white text-center p-4">
                        <i class="bi bi-exclamation-triangle display-4"></i>
                        <h5 class="mt-2">Không có dữ liệu ghế</h5>
                        <p>Phòng chiếu chưa được thiết lập sơ đồ ghế</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="container-fluid summary-box mt-4">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5>Chú thích màu sắc</h5>
                    <div class="d-flex flex-wrap gap-3">
                        <div><span class="legend-seat legend-normal"></span> Ghế thường</div>
                        <div><span class="legend-seat legend-vip"></span> Ghế VIP</div>
                        <div><span class="legend-seat legend-luxury"></span> Luxury</div>
                        <div><span class="legend-seat legend-couple"></span> Couple</div>
                        <div><span class="legend-seat legend-sold"></span> Đã đặt</div>
                        <div><span class="legend-seat legend-selected"></span> Đang chọn</div>
                    </div>
                    
                    <!-- Bảng giá -->
                    <div class="mt-3">
                        <h6>Bảng giá vé:</h6>
                        <div class="small">
                            @foreach($giaBanVe as $type => $price)
                                <span class="me-3">
                                    {{ ucfirst($type) }}: <strong>{{ number_format($price) }}đ</strong>
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Danh sách ghế đã chọn</h5>
                    <div id="selectedSeats" class="mb-2">
                        <em class="text-muted">Chưa chọn ghế nào</em>
                    </div>
                    <div id="totalPrice" class="mb-3">
                        <strong>Tổng tiền: 0đ</strong>
                    </div>
                    <button id="btn-next" class="btn btn-next px-4 py-2" disabled onclick="goToPayment()">
                        Tiếp theo
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- dữ liệu ẩn cho javascript --}}
<script type="application/json" id="app-data">
{!! json_encode([
    'giaBanVe' => $giaBanVe ?? [],
    'lichChieuId' => $lichChieuId ?? ''
]) !!}
</script>
@endsection

@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lấy dữ liệu từ hidden script tag
    const appDataElement = document.getElementById('app-data');
    const appData = JSON.parse(appDataElement.textContent);
    
    const giaBanVe = appData.giaBanVe;
    const lichChieuId = appData.lichChieuId;
    
    // Biến lưu trữ ghế đã chọn
    let selectedSeats = [];
    
    // Xử lý click chọn ghế
    document.querySelectorAll('.seat:not([disabled])').forEach(btn => {
        btn.addEventListener('click', function() {
            const seatCode = this.getAttribute('data-seat');
            const displayCode = this.getAttribute('data-display');
            const seatType = this.getAttribute('data-type');
            const seatPrice = parseInt(this.getAttribute('data-price'));
            
            if (this.classList.contains('selected')) {
                // Bỏ chọn ghế
                this.classList.remove('selected');
                selectedSeats = selectedSeats.filter(seat => seat.code !== seatCode);
            } else {
                // Chọn ghế (giới hạn tối đa 8 ghế)
                if (selectedSeats.length >= 8) {
                    alert('Bạn chỉ có thể chọn tối đa 8 ghế trong một lần đặt!');
                    return;
                }
                
                this.classList.add('selected');
                selectedSeats.push({
                    code: seatCode,
                    display: displayCode,
                    type: seatType,
                    price: seatPrice
                });
            }
            
            updateSummary();
        });
    });
    
    // Cập nhật thông tin tóm tắt
    function updateSummary() {
        const selectedSeatsDiv = document.getElementById('selectedSeats');
        const totalPriceDiv = document.getElementById('totalPrice');
        const nextBtn = document.getElementById('btn-next');
        
        if (selectedSeats.length === 0) {
            selectedSeatsDiv.innerHTML = '<em class="text-muted">Chưa chọn ghế nào</em>';
            totalPriceDiv.innerHTML = '<strong>Tổng tiền: 0đ</strong>';
            nextBtn.disabled = true;
        } else {
            // Hiển thị danh sách ghế
            const seatList = selectedSeats.map(seat => 
                `<span class="badge bg-primary me-1">${seat.display}</span>`
            ).join('');
            selectedSeatsDiv.innerHTML = seatList;
            
            // Tính tổng tiền
            const total = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
            totalPriceDiv.innerHTML = `<strong>Tổng tiền: ${total.toLocaleString()}đ</strong>`;
            
            nextBtn.disabled = false;
        }
    }
    
    // Chuyển đến trang thanh toán
    window.goToPayment = function() {
        if (selectedSeats.length === 0) {
            alert('Vui lòng chọn ít nhất một ghế!');
            return;
        }
        
        // Tạo query string
        const seatCodes = selectedSeats.map(seat => seat.code).join(',');
        const seatDisplays = selectedSeats.map(seat => seat.display).join(',');
        const total = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
        
        const params = new URLSearchParams({
            lich_chieu: lichChieuId,
            seats: seatCodes,
            seat_displays: seatDisplays,
            total: total
        });
        
        window.location.href = '/thanh-toan?' + params.toString();
    };
    
    // Khởi tạo
    updateSummary();
});
</script>
@endsection
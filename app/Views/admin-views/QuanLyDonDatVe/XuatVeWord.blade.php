@extends('layouts.admin.master')

@section('title', 'Xuất vé Word')

@section('content')
<div class="content" style="background-color: white;">
    <div class="container-fluid">
        {{-- Thông báo khi vừa in thành công --}}
        @if(isset($print_success) && $print_success)
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>
                <strong>In vé thành công!</strong> 
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        {{-- Thông báo khi vé đã in từ trước --}}
        @if(isset($order['v_trangthai']) && $order['v_trangthai'] == 'da_in' && (!isset($print_success) || !$print_success))
            <div class="alert alert-info alert-dismissible fade show">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Thông tin:</strong> Vé này đã được in trước đó.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-ticket-perforated me-2"></i>
                            Thông tin vé điện tử
                            {{-- Hiển thị trạng thái --}}
                            @if(isset($order['v_trangthai']) && $order['v_trangthai'] == 'da_in')
                                <span class="badge bg-success ms-2">Đã in</span>
                            @else
                                <span class="badge bg-warning ms-2">Chưa in</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($order)
                            <div class="ticket-content">
                                <div class="text-center mb-4">
                                    <h2 class="text-danger">KHF CINEMA</h2>
                                    <p class="text-muted">Vé xem phim điện tử</p>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Mã vé:</strong> {{ $order['v_mave'] }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Ngày đặt:</strong> 
                                        @if($order['v_ngaydat'])
                                            {{ date('d/m/Y', strtotime($order['v_ngaydat'])) }}
                                        @else
                                            {{ date('d/m/Y') }}
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Khách hàng:</strong> {{ $order['nd_hoten'] }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Số điện thoại:</strong> {{ $order['nd_sdt'] ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Phim:</strong> {{ $order['p_tenphim'] }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Phòng:</strong> {{ $order['pc_tenphong'] }}
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Ghế:</strong> {{ $order['g_maghe'] }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Loại vé:</strong> {{ $order['lv_tenloaive'] }}
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Suất chiếu:</strong> 
                                        @if($order['lc_ngaychieu'])
                                            {{ date('d/m/Y', strtotime($order['lc_ngaychieu'])) }}
                                        @endif
                                        @if($order['lc_giobatdau'])
                                            {{ date('H:i', strtotime($order['lc_giobatdau'])) }}
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Tổng tiền:</strong> 
                                        <span class="text-success">{{ number_format($order['v_tongtien'] ?? 0) }}₫</span>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Thanh toán:</strong> {{ $order['tt_phuongthuc'] }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Trạng thái:</strong> 
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="text-center">
                                    <p class="text-muted small mb-0">
                                        Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!
                                    </p>
                                    <p class="text-muted small">
                                        Vui lòng đến rạp trước 15 phút để làm thủ tục vào xem phim.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Không tìm thấy thông tin vé!
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-center">
                        <a href="/quan-ly-don-dat-ve" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại
                        </a>
                        
                        {{-- Nút in vé với xác nhận --}}
                        @if(isset($order['v_trangthai']) && $order['v_trangthai'] == 'da_in')
                            <button onclick="window.print()" class="btn btn-success">
                                <i class="bi bi-printer me-1"></i>In lại vé
                            </button>
                        @else
                            <button onclick="window.print()" class="btn btn-primary" id="printBtn">
                                <i class="bi bi-printer me-1"></i>In vé
                            </button>
                            
                            {{-- Form ẩn để cập nhật trạng thái --}}
                            <form method="POST" action="/process-ve-print" id="printForm" style="display: none;">
                                <input type="hidden" name="ve_id" value="{{ $order['v_mave'] }}">
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Xử lý sự kiện in vé
document.getElementById('printBtn')?.addEventListener('click', function() {
    // Hiển thị hộp thoại xác nhận
    if (confirm('Bạn có chắc chắn muốn in vé này? Vé sẽ được đánh dấu là đã in.')) {
        // Gửi form để cập nhật trạng thái
        document.getElementById('printForm').submit();
    }
});

// THÊM ĐOẠN NÀY - Tự động ẩn thông báo sau 3000ms
setTimeout(function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 3000);
</script>

<style>
@media print {
    .card-footer, .alert, .btn { display: none !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection
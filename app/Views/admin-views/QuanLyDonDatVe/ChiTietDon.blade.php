@extends('layouts.admin.master')

@section('title', 'Chi tiết đơn đặt vé')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/ChiTietDon.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <div>
        @if($order)
            <h1 class="mb-4">Chi tiết vé #{{ $order['v_mave'] }}</h1>
            
            <!-- Thông báo -->
            @if(isset($_SESSION['success_message']))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ $_SESSION['success_message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            @endif
            
            @if(isset($_SESSION['error_message']))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ $_SESSION['error_message'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            @endif
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Thông tin vé</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Mã vé:</div>
                                <div class="order-value col-sm-8"><strong>{{ $order['v_mave'] }}</strong></div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Khách hàng:</div>
                                <div class="order-value col-sm-8">
                                    {{ $order['nd_hoten'] ?: 'Khách vãng lai' }}
                                    @if($order['nd_email'])
                                        <br><small class="text-muted">{{ $order['nd_email'] }}</small>
                                    @endif
                                    @if($order['nd_sdt'])
                                        <br><small class="text-muted">{{ $order['nd_sdt'] }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Tên phim:</div>
                                <div class="order-value col-sm-8">{{ $order['p_tenphim'] }}</div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Suất chiếu:</div>
                                <div class="order-value col-sm-8">
                                    {{ date('H:i d/m/Y', strtotime($order['lc_giobatdau'])) }}
                                    @if($order['lc_ngaychieu'])
                                        <br><small class="text-muted">Ngày: {{ date('d/m/Y', strtotime($order['lc_ngaychieu'])) }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Phòng chiếu:</div>
                                <div class="order-value col-sm-8">{{ $order['pc_tenphong'] }}</div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Ghế:</div>
                                <div class="order-value col-sm-8">
                                    <span class="badge bg-info fs-6">{{ $order['g_maghe'] }}</span>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Loại vé:</div>
                                <div class="order-value col-sm-8">{{ $order['lv_tenloaive'] }}</div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Giá:</div>
                                <div class="order-value col-sm-8">
                                    <strong class="text-success">{{ number_format($order['v_tongtien'], 0, ',', '.') }} VNĐ</strong>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Ngày đặt:</div>
                                <div class="order-value col-sm-8">{{ date('d/m/Y H:i', strtotime($order['v_ngaydat'])) }}</div>
                            </div>
                            <div class="mb-3 row">
                                <div class="order-label col-sm-4">Trạng thái in:</div>
                                <div class="order-value col-sm-8">
                                    @if ($order['v_trangthai'] == 'chua_in')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-clock me-1"></i>Chưa in
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Đã in
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Thông tin thanh toán</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="order-label">Trạng thái:</div>
                                <div class="order-value">
                                    @if($order['tt_mathanhtoan'])
                                        <span class="badge bg-success status-paid">Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-warning status-unpaid">Chưa thanh toán</span>
                                    @endif
                                </div>
                            </div>
                            
                            @if($order['tt_phuongthuc'])
                                <div class="mb-3">
                                    <div class="order-label">Phương thức:</div>
                                    <div class="order-value">{{ $order['tt_phuongthuc'] }}</div>
                                </div>
                            @endif
                            
                            @if($order['tt_thoigianthanhtoan'])
                                <div class="mb-3">
                                    <div class="order-label">Thời gian thanh toán:</div>
                                    <div class="order-value">{{ date('d/m/Y H:i', strtotime($order['tt_thoigianthanhtoan'])) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4">
                <a href="/quan-ly-don-dat-ve" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
                
                @if($order['tt_mathanhtoan'])
                    <a href="/xuat-ve-word?id={{ $order['v_mave'] }}" class="btn btn-success">
                        <i class="bi bi-download me-2"></i>In vé
                    </a>
                @endif
                
                @php
                    $canCancel = true;
                    if ($order['lc_giobatdau']) {
                        $showTime = strtotime($order['lc_giobatdau']);
                        $currentTime = time();
                        $timeDiff = $showTime - $currentTime;
                        $canCancel = $timeDiff >= 7200; // 2 giờ
                    }
                @endphp
            </div>
        @else
            <div class="alert alert-danger text-center">
                <i class="bi bi-exclamation-triangle fs-3 d-block mb-2"></i>
                Không tìm thấy đơn đặt vé!
            </div>
            <div class="mt-4 text-center">
                <a href="/quan-ly-don-dat-ve" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.admin.master')

@section('title', 'Quản lý Đơn đặt vé')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyDonDatVe.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="content" style="background-color: white;">
    <h1>Quản lý Đơn đặt vé</h1><hr>
    
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
    
    <!-- Thống kê tổng quan -->
    @if(isset($stats))
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Tổng vé</h5>
                        <h3>{{ number_format($stats['total_tickets'] ?? 0) }}</h3>
                        <small>Tất cả thời gian</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Vé năm {{ date('Y') }}</h5>
                        <h3>{{ number_format($stats['tickets_this_year'] ?? 0) }}</h3>
                        <small>Từ 01/01/{{ date('Y') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5 class="card-title">Vé tháng {{ date('m') }}</h5>
                        <h3>{{ number_format($stats['tickets_this_month'] ?? 0) }}</h3>
                        <small>Tháng {{ date('m/Y') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Vé hôm nay</h5>
                        <h3>{{ number_format($stats['tickets_today'] ?? 0) }}</h3>
                        <small>{{ date('d/m/Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-warning">Không có thống kê</div>
    @endif
    
    <!-- Form tìm kiếm giữ nguyên -->
    <div>
        <form class="row g-3 align-items-end filter-form mb-3" method="get">
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" name="search" 
                       value="{{ $filters['search'] ?? '' }}" 
                       placeholder="Tên khách hàng, phim, mã vé...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Phương thức TT</label>
                <select class="form-select" name="payment_method">
                    <option value="">Tất cả</option>
                    <option value="momo" {{ ($filters['payment_method'] ?? '') == 'momo' ? 'selected' : '' }}>MoMo</option>
                    <option value="Tiền mặt" {{ ($filters['payment_method'] ?? '') == 'Tiền mặt' ? 'selected' : '' }}>Tiền mặt</option>
                    <option value="vnpay" {{ ($filters['payment_method'] ?? '') == 'vnpay' ? 'selected' : '' }}>VN Pay</option>
                    <option value="zalopay" {{ ($filters['payment_method'] ?? '') == 'zalopay' ? 'selected' : '' }}>Zalo Pay</option>
                    <option value="bank" {{ ($filters['payment_method'] ?? '') == 'bank' ? 'selected' : '' }}>Ngân hàng</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái in</label>
                <select class="form-select" name="print_status">
                    <option value="">Tất cả</option>
                    <option value="chua_in" {{ ($filters['print_status'] ?? '') == 'chua_in' ? 'selected' : '' }}>Chưa in</option>
                    <option value="da_in" {{ ($filters['print_status'] ?? '') == 'da_in' ? 'selected' : '' }}>Đã in</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Ngày đặt</label>
                <input type="date" class="form-control" name="date" 
                       value="{{ $filters['date'] ?? '' }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
        
        <!-- Danh sách đơn đặt vé -->
        <div class="table-responsive p-3">
            <table class="table align-middle table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Mã vé</th>
                        <th>Khách hàng</th>
                        <th>Phim</th>
                        <th>Suất chiếu</th>
                        <th>Ghế</th>
                        <th>Giá</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td><strong>{{ $order['v_mave'] ?? 'N/A' }}</strong></td>
                            <td>
                                {{ $order['nd_hoten'] ?? 'Khách vãng lai' }}
                                @if($order['nd_sdt'] ?? '')
                                    <br><small class="text-muted">{{ $order['nd_sdt'] }}</small>
                                @endif
                            </td>
                            <td>{{ $order['p_tenphim'] ?? 'Phim demo' }}</td>
                            <td>
                                @if(isset($order['lc_giobatdau']) && $order['lc_giobatdau'])
                                    {{ date('H:i d/m/Y', strtotime($order['lc_giobatdau'])) }}
                                @else
                                    <span class="text-muted">Chưa có lịch chiếu</span>
                                @endif
                                <br><small class="text-muted">{{ $order['pc_tenphong'] ?? 'Phòng demo' }}</small>
                            </td>
                            <td><span class="badge bg-info">{{ $order['g_maghe'] ?? 'N/A' }}</span></td>
                            <td><strong>{{ number_format($order['v_tongtien'] ?? 0, 0, ',', '.') }} VNĐ</strong></td>
                            <td>
                            @if(($order['v_trangthai'] ?? 'chua_in') == 'da_in')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Đã in
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="bi bi-clock me-1"></i>Chưa in
                                </span>
                            @endif
                        </td>

                            <td>
                                <span class="badge bg-success">Đã thanh toán</span>
                                @if($order['tt_phuongthuc'] ?? '')
                                    <br><small class="text-muted">{{ $order['tt_phuongthuc'] }}</small>
                                @endif
                                @if($order['tt_thoigianthanhtoan'] ?? '')
                                    <br><small class="text-muted">{{ date('d/m H:i', strtotime($order['tt_thoigianthanhtoan'])) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="/chi-tiet-don-dat-ve?id={{ $order['v_mave'] }}" 
                                       class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/xuat-ve-word?id={{ $order['v_mave'] }}" 
                                       class="btn btn-success btn-sm" title="In vé">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                Không có vé nào được tìm thấy
                                <br><small>Có thể dữ liệu chưa được tạo hoặc có lỗi truy vấn</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Phân trang -->
        @include('layouts.admin.Pagination', [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalOrders,
            'itemsPerPage' => 10,  // THÊM DÒNG NÀY - theo Controller đang dùng limit = 10
            'itemName' => 'vé',
            'search' => $filters['search'] ?? '',
            'status' => $filters['payment_method'] ?? '',
            'sort' => $filters['date'] ?? '',
            'print_status' => $filters['print_status'] ?? ''
        ])
    </div>
</div>
@endsection

@extends('layouts.admin.master')

@section('title', 'Quản lý lịch chiếu')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h1>Quản lý Lịch chiếu</h1>
    <hr>

    {{-- Thông báo --}}
    @if(isset($_SESSION['success_message']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @php unset($_SESSION['success_message']); @endphp
    @endif

    @if(isset($_SESSION['error_message']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @php unset($_SESSION['error_message']); @endphp
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/them-lich-chieu" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Thêm lịch chiếu mới
        </a>
        
        {{-- Filter Form --}}
        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap" method="GET">
            <input type="date" class="form-control w-auto" name="ngay_chieu" 
                   value="{{ $filters['ngay_chieu'] ?? '' }}" placeholder="Lọc theo ngày">
            
            <select class="form-select w-auto" name="trang_thai" style="min-width:140px;">
                <option value="">Tất cả trạng thái</option>
                <option value="Sắp chiếu" {{ ($filters['trang_thai'] ?? '') === 'Sắp chiếu' ? 'selected' : '' }}>Sắp chiếu</option>
                <option value="Đang chiếu" {{ ($filters['trang_thai'] ?? '') === 'Đang chiếu' ? 'selected' : '' }}>Đang chiếu</option>
                <option value="Đã chiếu" {{ ($filters['trang_thai'] ?? '') === 'Đã chiếu' ? 'selected' : '' }}>Đã chiếu</option>
                <option value="Hủy" {{ ($filters['trang_thai'] ?? '') === 'Hủy' ? 'selected' : '' }}>Hủy</option>
            </select>
            
            <select class="form-select w-auto" name="ma_phim" style="min-width:170px;">
                <option value="">Tất cả phim</option>
                @foreach($phimList ?? [] as $phim)
                    <option value="{{ $phim['p_maphim'] }}" 
                            {{ ($filters['ma_phim'] ?? '') === $phim['p_maphim'] ? 'selected' : '' }}>
                        {{ $phim['p_tenphim'] }}
                    </option>
                @endforeach
            </select>
            
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i>
            </button>
            
            @if(array_filter($filters ?? []))
                <a href="/quan-ly-lich-chieu" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>STT</th>
                    <th>Mã LC</th>
                    <th>Phim</th>
                    <th>Ngày chiếu</th>
                    <th>Giờ bắt đầu</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lichChieuList ?? [] as $index => $lichChieu)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $lichChieu['lc_malichchieu'] }}</td>
                        <td>
                            <strong>{{ $lichChieu['p_tenphim'] ?? 'N/A' }}</strong>
                        </td>
                        <td>{{ date('d/m/Y', strtotime($lichChieu['lc_ngaychieu'])) }}</td>
                        <td>{{ date('H:i', strtotime($lichChieu['lc_giobatdau'])) }}</td>
                        <td>
                            @if($lichChieu['lc_trangthai'] === 'Sắp chiếu')
                                <span class="badge bg-warning text-dark">Sắp chiếu</span>
                            @elseif($lichChieu['lc_trangthai'] === 'Đang chiếu')
                                <span class="badge bg-success">Đang chiếu</span>
                            @elseif($lichChieu['lc_trangthai'] === 'Đã chiếu')
                                <span class="badge bg-secondary">Đã chiếu</span>
                            @else
                                <span class="badge bg-danger">{{ $lichChieu['lc_trangthai'] }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="/sua-lich-chieu?id={{ $lichChieu['lc_malichchieu'] }}" 
                               class="btn btn-sm btn-warning me-1" title="Sửa">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="/xoa-lich-chieu?id={{ $lichChieu['lc_malichchieu'] }}"
                               class="btn btn-sm btn-danger delete-btn" 
                               title="Xóa"
                               data-name="{{ $lichChieu['p_tenphim'] ?? 'lịch chiếu này' }}">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-calendar-x fs-1"></i>
                                <p class="mt-2">Không có lịch chiếu nào</p>
                                <a href="/them-lich-chieu" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Thêm lịch chiếu đầu tiên
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @include('layouts.admin.Pagination', [
    'currentPage' => $currentPage ?? 1,
    'totalPages' => $totalPages ?? 1,
    'totalItems' => $totalItems ?? 0,
    'itemsPerPage' => $itemsPerPage ?? 10,
    'itemName' => 'lịch chiếu'
    ])
    
    <hr>
</div>
@endsection

@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    const deleteBtns = document.querySelectorAll('.delete-btn');
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const name = this.getAttribute('data-name');
            const href = this.getAttribute('href');
            
            if (confirm(`Bạn có chắc chắn muốn xóa lịch chiếu "${name}" không?`)) {
                window.location.href = href;
            }
        });
    });

    // Auto dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            if (alert.querySelector('.btn-close')) {
                alert.querySelector('.btn-close').click();
            }
        });
    }, 5000);
});
</script>
@endsection
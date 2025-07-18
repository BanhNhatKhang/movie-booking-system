{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyPhim\DoiTrangThaiPhim.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Đổi trạng thái phim')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

@endsection

@section('content')
<div class="container py-4 content" style="background-color: white;">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="bi bi-arrow-repeat text-primary"></i> Đổi Trạng Thái Phim
                    </h1>
                    <p class="text-muted mb-0">Thay đổi trạng thái hiển thị của phim trong hệ thống</p>
                </div>
                <a href="/quan-ly-phim" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    @if(isset($success) && $success)
        <!-- Success Message -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                        </div>
                        
                        <h4 class="text-success mb-3">Đổi trạng thái thành công!</h4>
                        
                        <div class="alert alert-success">
                            <strong>Phim:</strong> {{ $phimName ?? 'N/A' }}<br>
                            <strong>Trạng thái cũ:</strong> 
                            <span class="badge bg-secondary">{{ $oldStatus ?? '' }}</span><br>
                            <strong>Trạng thái mới:</strong> 
                            <span class="badge bg-success">{{ $newStatus ?? '' }}</span>
                        </div>
                        
                        <div class="mt-3">
                            <a href="/quan-ly-phim" class="btn btn-primary">
                                <i class="bi bi-arrow-left"></i> Quay lại danh sách
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Status Change Form -->
        @php
            $phim = $phim ?? null;
            if (!$phim) {
                echo '<div class="alert alert-danger">Không tìm thấy thông tin phim!</div>';
                exit;
            }
        @endphp

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Movie Information -->
                <div class="movie-info">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            @if(!empty($phim['poster']))
                                <img src="{{ $phim['poster'] }}" class="img-fluid rounded" 
                                     style="max-height: 150px;" alt="Poster {{ $phim['name'] }}">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 150px;">
                                    <i class="bi bi-image text-muted fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4 class="text-primary mb-2">{{ $phim['name'] ?? 'N/A' }}</h4>
                            <div class="row text-sm">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Mã phim:</strong> {{ $phim['id'] ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Thể loại:</strong> {{ $phim['genre'] ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Thời lượng:</strong> {{ $phim['duration'] ?? 0 }} phút</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Đạo diễn:</strong> {{ $phim['director'] ?? 'N/A' }}</p>
                                    <p class="mb-1"><strong>Ngày phát hành:</strong> 
                                        @if(!empty($phim['release']))
                                            {{ date('d/m/Y', strtotime($phim['release'])) }}
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                    <p class="mb-1"><strong>Trạng thái hiện tại:</strong> 
                                        @php
                                            $currentStatusMap = [
                                                'active' => ['bg-success', 'Đang chiếu'],
                                                'coming_soon' => ['bg-warning text-dark', 'Sắp chiếu'],
                                                'ended' => ['bg-danger', 'Đã kết thúc'],
                                                'suspended' => ['bg-secondary', 'Tạm ngưng'],
                                                'inactive' => ['bg-dark', 'Không hoạt động']
                                            ];
                                            $currentStatus = $phim['status'] ?? 'ended';
                                            $statusConfig = $currentStatusMap[$currentStatus] ?? ['bg-secondary', 'Không xác định'];
                                        @endphp
                                        <span class="badge {{ $statusConfig[0] }}">{{ $statusConfig[1] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Selection Form -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-gear"></i> Chọn Trạng Thái Mới
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="/cap-nhat-trang-thai" id="status-form">
                            <input type="hidden" name="id" value="{{ $phim['id'] }}">
                            
                            <p class="text-muted mb-4">Chọn trạng thái mới cho phim này:</p>

                            <!-- Status Options -->
                            <div class="status-options">
                                <!-- Đang chiếu -->
                                <div class="status-option" data-status="active">
                                    <label class="d-flex align-items-center mb-0 w-100 cursor-pointer">
                                        <input type="radio" name="status" value="active" 
                                               {{ ($phim['status'] ?? '') == 'active' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-success me-3" style="min-width: 80px;">Đang chiếu</span>
                                                <div>
                                                    <strong>Đang chiếu</strong>
                                                    <small class="text-muted d-block">
                                                        Phim đang được chiếu tại rạp, khách hàng có thể đặt vé
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Sắp chiếu -->
                                <div class="status-option" data-status="coming_soon">
                                    <label class="d-flex align-items-center mb-0 w-100 cursor-pointer">
                                        <input type="radio" name="status" value="coming_soon" 
                                               {{ ($phim['status'] ?? '') == 'coming_soon' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-warning text-dark me-3" style="min-width: 80px;">Sắp chiếu</span>
                                                <div>
                                                    <strong>Sắp chiếu</strong>
                                                    <small class="text-muted d-block">
                                                        Phim sắp được chiếu, khách hàng có thể xem thông tin và trailer
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Đã kết thúc -->
                                <div class="status-option" data-status="ended">
                                    <label class="d-flex align-items-center mb-0 w-100 cursor-pointer">
                                        <input type="radio" name="status" value="ended" 
                                               {{ ($phim['status'] ?? '') == 'ended' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-danger me-3" style="min-width: 80px;">Đã kết thúc</span>
                                                <div>
                                                    <strong>Đã kết thúc</strong>
                                                    <small class="text-muted d-block">
                                                        Phim đã ngừng chiếu, chỉ hiển thị trong lịch sử
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Tạm ngưng -->
                                <div class="status-option" data-status="suspended">
                                    <label class="d-flex align-items-center mb-0 w-100 cursor-pointer">
                                        <input type="radio" name="status" value="suspended" 
                                               {{ ($phim['status'] ?? '') == 'suspended' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-secondary me-3" style="min-width: 80px;">Tạm ngưng</span>
                                                <div>
                                                    <strong>Tạm ngưng</strong>
                                                    <small class="text-muted d-block">
                                                        Tạm thời ngừng chiếu, có thể khôi phục lại sau
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="/quan-ly-phim" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                    <i class="bi bi-check-circle"></i> Cập nhật trạng thái
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(isset($success) && $success)
    <meta http-equiv="refresh" content="3;url=/quan-ly-phim?success=status_updated">
@endif
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="/static/js/admin/DoiTrangThaiPhim.js"></script>

@if(isset($success) && $success)
<script>
// Auto redirect after 3 seconds for success case
setTimeout(function() {
    window.location.href = '/quan-ly-phim?success=status_updated';
}, 3000);
</script>
@endif
@endsection
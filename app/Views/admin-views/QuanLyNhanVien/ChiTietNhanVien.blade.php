@extends('layouts.admin.master')

@section('title', 'Chi tiết nhân viên')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .bg-gradient-gold {
            background: linear-gradient(45deg, #FFD700, #FFA500) !important;
        }
        .progress {
            border-radius: 10px;
            overflow: hidden;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
    </style>
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-badge me-2"></i>Chi tiết nhân viên</h1>
        <div>
            <span class="badge bg-danger me-2">
                <i class="bi bi-shield-check me-1"></i>Admin
            </span>
            <a href="/sua-nhan-vien?id={{ $nhanVien['nd_id'] }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-2"></i>Sửa thông tin
            </a>
            <a href="/quan-ly-nhan-vien" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
    
    <div class="row">
        {{-- Thông tin cơ bản --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-badge me-2"></i>Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID:</strong> {{ $nhanVien['nd_id'] }}
                        </div>
                        <div class="col-md-6">
                            <strong>Tên đăng nhập:</strong> {{ $nhanVien['nd_tendangnhap'] ?? 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong> {{ $nhanVien['nd_hoten'] }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $nhanVien['nd_email'] }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong> {{ $nhanVien['nd_sdt'] ?? 'Chưa cập nhật' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong>
                            @if(isset($nhanVien['nd_gioitinh']))
                                @if($nhanVien['nd_gioitinh'] == 1)
                                    <span class="badge bg-info">
                                        <i class="bi bi-person me-1"></i>Nam
                                    </span>
                                @elseif($nhanVien['nd_gioitinh'] == 0)
                                    <span class="badge bg-pink" style="background-color: #e91e63;">
                                        <i class="bi bi-person-dress me-1"></i>Nữ
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Chưa cập nhật</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Chưa cập nhật</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Ngày sinh:</strong> 
                            {{ $nhanVien['nd_ngaysinh'] ? date('d/m/Y', strtotime($nhanVien['nd_ngaysinh'])) : 'Chưa cập nhật' }}
                        </div>
                        <div class="col-md-6">
                            <strong>CCCD/CMND:</strong> {{ $nhanVien['nd_cccd'] ?? 'Chưa cập nhật' }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày tham gia:</strong> 
                            {{ date('d/m/Y', strtotime($nhanVien['nd_ngaydangky'])) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lịch sử công việc --}}
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clipboard-check me-2"></i>Lịch sử công việc
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center py-4">
                        <i class="bi bi-clipboard text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Chưa có lịch sử công việc</p>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Sidebar thống kê --}}
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-gradient-gold">
                    <h6 class="card-title mb-0 text-white">
                        <i class="bi bi-star-fill me-2"></i>Thông tin thành viên
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if(!empty($memberInfo['tv_loaithanhvien']))
                            {{-- Có thông tin thành viên --}}
                            <div class="mb-2">
                                <i class="bi bi-award text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h5 class="mb-1">
                                <span class="badge bg-warning fs-6">
                                    <i class="bi bi-star me-1"></i>
                                    @if($memberInfo['tv_loaithanhvien'] == 'bronze')
                                        Thành viên Đồng
                                    @elseif($memberInfo['tv_loaithanhvien'] == 'silver')
                                        Thành viên Bạc
                                    @elseif($memberInfo['tv_loaithanhvien'] == 'gold')
                                        Thành viên Vàng
                                    @elseif($memberInfo['tv_loaithanhvien'] == 'platinum')
                                        Thành viên Bạch Kim
                                    @elseif($memberInfo['tv_loaithanhvien'] == 'diamond')
                                        Thành viên Kim Cương
                                    @else
                                        {{ ucfirst($memberInfo['tv_loaithanhvien']) }}
                                    @endif
                                </span>
                            </h5>
                            <p class="text-muted small">ID: {{ $memberInfo['tv_mathanhvien'] }}</p>
                        @else
                            {{-- Chưa có thông tin thành viên --}}
                            <div class="mb-2">
                                <i class="bi bi-person-circle text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="text-muted">Chưa có bậc thành viên</h6>
                        @endif
                    </div>
                    
                    <hr>
                    
                    <div class="row text-center">
                        <div class="col-12">
                            <h4 class="text-primary mb-0">
                                <i class="bi bi-gem me-1"></i>{{ number_format($memberInfo['tv_diemtichluy'] ?? 0) }}
                            </h4>
                            <small class="text-muted">Điểm tích lũy</small>
                        </div>
                    </div>
                    
                    @if(($memberInfo['tv_diemtichluy'] ?? 0) > 0)
                        <div class="mt-3">
                            <small class="text-muted">Tiến trình lên hạng:</small>
                            <div class="progress mt-1" style="height: 8px;">
                                @php
                                    $points = $memberInfo['tv_diemtichluy'] ?? 0;
                                    $nextLevel = 1000;
                                    $progress = min(($points % $nextLevel) / $nextLevel * 100, 100);
                                @endphp
                                <div class="progress-bar bg-warning progress-dynamic" data-progress="{{ $progress }}"></div>
                            </div>
                            <small class="text-muted">{{ $points % $nextLevel }}/{{ $nextLevel }} điểm</small>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Card trạng thái & quyền hạn --}}
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-gear me-2"></i>Trạng thái & Quyền hạn
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng thái:</strong><br>
                        @if(($nhanVien['nd_trangthai'] ?? 'active') === 'active')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Hoạt động
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-x-circle me-1"></i>Đã khóa
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phân quyền:</strong><br>
                        <span class="badge bg-danger fs-6">
                            <i class="bi bi-shield-check me-1"></i>Quản trị viên
                        </span>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="/sua-nhan-vien?id={{ $nhanVien['nd_id'] }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-2"></i>Sửa thông tin
                        </a>
                        
                        @if($nhanVien['nd_id'] !== $_SESSION['user_id'])
                            <a href="/khoa-nhan-vien?id={{ $nhanVien['nd_id'] }}" 
                               class="btn btn-{{ ($nhanVien['nd_trangthai'] ?? 'active') === 'active' ? 'danger' : 'success' }} btn-sm"
                               onclick="return confirm('Bạn có chắc chắn?')">
                                @if(($nhanVien['nd_trangthai'] ?? 'active') === 'active')
                                    <i class="bi bi-lock me-2"></i>Khóa tài khoản
                                @else
                                    <i class="bi bi-unlock me-2"></i>Mở khóa
                                @endif
                            </a>
                        @else
                            <button class="btn btn-secondary btn-sm" disabled>
                                <i class="bi bi-person-check me-2"></i>Tài khoản hiện tại
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Thống kê hoạt động --}}
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Thống kê hoạt động
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-primary mb-0">{{ $workStats['total_tasks'] ?? 0 }}</h5>
                                <small class="text-muted">Tổng CV</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h5 class="text-success mb-0">{{ $workStats['completed_tasks'] ?? 0 }}</h5>
                                <small class="text-muted">Hoàn thành</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h5 class="text-warning mb-0">{{ number_format($memberInfo['tv_diemtichluy'] ?? 0) }}</h5>
                            <small class="text-muted">Điểm</small>
                        </div>
                    </div>
                    
                    <div>
                        <small class="text-muted">Tham gia từ:</small><br>
                        <strong>{{ date('d/m/Y', strtotime($nhanVien['nd_ngaydangky'])) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-dynamic[data-progress]');
    progressBars.forEach(bar => {
        const progress = bar.getAttribute('data-progress');
        setTimeout(() => {
            bar.style.width = progress + '%';
        }, 100); // Delay nhỏ để có animation
    });
});
</script>
@endsection
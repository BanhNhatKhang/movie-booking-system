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
                    <h6 class="card-title mb-0">
                        <i class="bi bi-star-fill me-2"></i>Thông tin thành viên
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="mb-2">
                            <i class="bi bi-award 
                                @if($nhanVien['nd_loaithanhvien'] == 'bac') text-secondary
                                @elseif($nhanVien['nd_loaithanhvien'] == 'vang') text-danger
                                @elseif($nhanVien['nd_loaithanhvien'] == 'kimcuong') text-info
                                @else text-dark @endif" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="mb-1">
                            <span class="badge 
                                @if($nhanVien['nd_loaithanhvien'] == 'bac') bg-secondary
                                @elseif($nhanVien['nd_loaithanhvien'] == 'vang') bg-danger
                                @elseif($nhanVien['nd_loaithanhvien'] == 'kimcuong') bg-info
                                @else bg-light text-dark @endif fs-6">
                                <i class="bi bi-star me-1"></i>
                                @if($nhanVien['nd_loaithanhvien'] == 'bac')
                                    Thành viên Bạc
                                @elseif($nhanVien['nd_loaithanhvien'] == 'vang')
                                    Thành viên Vàng
                                @elseif($nhanVien['nd_loaithanhvien'] == 'kimcuong')
                                    Thành viên Kim Cương
                                @else
                                    {{ ucfirst($nhanVien['nd_loaithanhvien']) }}
                                @endif
                            </span>
                        </h5>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <h4 class="text-primary mb-0">
                                <i class="bi bi-gem me-1"></i>{{ number_format($nhanVien['nd_diemtichluy'] ?? 0) }}
                            </h4>
                            <small class="text-muted">Điểm tích lũy</small>
                        </div>
                    </div>
                    @php
                        $points = $nhanVien['nd_diemtichluy'] ?? 0;
                        if($nhanVien['nd_loaithanhvien'] == 'bac') {
                            $nextLevel = 2000;
                            $progress = min($points / $nextLevel * 100, 100);
                            $label = $points . '/' . $nextLevel . ' điểm để lên Vàng';
                        } elseif($nhanVien['nd_loaithanhvien'] == 'vang') {
                            $nextLevel = 4000;
                            $progress = min(($points - 2000) / 2000 * 100, 100);
                            $label = ($points - 2000) . '/2000 điểm để lên Kim Cương';
                        } else { // kim cương
                            $nextLevel = 4000;
                            $progress = 100;
                            $label = 'Đã đạt hạng cao nhất';
                        }
                    @endphp
                    <div class="mt-3">
                        <small class="text-muted">Tiến trình lên hạng:</small>
                        <div class="progress mt-1" style="height: 8px;">
                            <div class="progress-bar 
                                @if($nhanVien['nd_loaithanhvien'] == 'bac') bg-secondary
                                @elseif($nhanVien['nd_loaithanhvien'] == 'vang') bg-danger
                                @elseif($nhanVien['nd_loaithanhvien'] == 'kimcuong') bg-info
                                @else bg-light text-dark @endif progress-dynamic" 
                                data-progress="{{ $progress }}"></div>
                        </div>
                        <small class="text-muted">{{ $label }}</small>
                    </div>
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
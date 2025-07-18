@extends('layouts.admin.master')

@section('title', 'Chi tiết người dùng')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/ChiTietNguoiDung.css">
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
        <h1><i class="bi bi-person-circle me-2"></i>Chi tiết người dùng</h1>
        <div>
            <a href="/sua-nguoi-dung?id={{ $user['nd_id'] }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-2"></i>Sửa thông tin
            </a>
            <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">
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
                            <strong>ID:</strong> {{ $user['nd_id'] }}
                        </div>
                        <div class="col-md-6">
                            <strong>Tên đăng nhập:</strong> {{ $user['nd_tendangnhap'] ?? 'N/A' }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong> {{ $user['nd_hoten'] }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong> {{ $user['nd_email'] }}
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong> {{ $user['nd_sdt'] ?? 'Chưa cập nhật' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Giới tính:</strong>
                            @if(isset($user['nd_gioitinh']))
                                @if($user['nd_gioitinh'] == 1)
                                    <span class="badge bg-info">
                                        <i class="bi bi-person me-1"></i>Nam
                                    </span>
                                @elseif($user['nd_gioitinh'] == 0)
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
                            {{ $user['nd_ngaysinh'] ? date('d/m/Y', strtotime($user['nd_ngaysinh'])) : 'Chưa cập nhật' }}
                        </div>
                        <div class="col-md-6">
                            <strong>CCCD/CMND:</strong> {{ $user['nd_cccd'] ?? 'Chưa cập nhật' }}
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Ngày đăng ký:</strong> 
                            {{ date('d/m/Y', strtotime($user['nd_ngaydangky'])) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Lịch sử đặt vé --}}
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-ticket-perforated me-2"></i>Lịch sử đặt vé
                    </h5>
                </div>
                <div class="card-body">
                    @if(!empty($bookingHistory))
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Phim</th>
                                        <th>Suất chiếu</th>
                                        <th>Ghế</th>
                                        <th>Phòng chiếu</th>
                                        <th>Tổng tiền</th>
                                        <th>Ngày đặt</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookingHistory as $booking)
                                    <tr>
                                        <td>{{ $booking['movie_name'] }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($booking['showtime'])) }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ isset($booking['seats']) ? preg_replace('/^.*_/', '', $booking['seats']) : '' }}
                                            </span>
                                        </td>
                                        <td>{{ $booking['pc_tenphong'] ?? '' }}</td>
                                        <td><strong>{{ number_format($booking['total_price']) }} VNĐ</strong></td>
                                        <td>{{ date('d/m/Y', strtotime($booking['booking_date'])) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($totalPages > 1)
                            <nav>
                                <ul class="pagination justify-content-center mt-3">
                                    <li class="page-item {{ $page == 1 ? 'disabled' : '' }}">
                                        <a class="page-link" href="?id={{ $user['nd_id'] }}&page={{ $page - 1 }}" tabindex="-1">Trước</a>
                                    </li>
                                    @for($i = 1; $i <= $totalPages; $i++)
                                        <li class="page-item {{ $i == $page ? 'active' : '' }}">
                                            <a class="page-link" href="?id={{ $user['nd_id'] }}&page={{ $i }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item {{ $page == $totalPages ? 'disabled' : '' }}">
                                        <a class="page-link" href="?id={{ $user['nd_id'] }}&page={{ $page + 1 }}">Sau</a>
                                    </li>
                                </ul>
                            </nav>
                            @endif
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-ticket text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Người dùng chưa có lịch sử đặt vé nào</p>
                        </div>
                    @endif
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
                                @if($user['nd_loaithanhvien'] == 'bac') text-secondary
                                @elseif($user['nd_loaithanhvien'] == 'vang') text-danger
                                @elseif($user['nd_loaithanhvien'] == 'kimcuong') text-info
                                @else text-dark @endif" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="mb-1">
                            <span class="badge 
                                @if($user['nd_loaithanhvien'] == 'bac') bg-secondary
                                @elseif($user['nd_loaithanhvien'] == 'vang') bg-danger
                                @elseif($user['nd_loaithanhvien'] == 'kimcuong') bg-info
                                @else bg-light text-dark @endif fs-6">
                                <i class="bi bi-star me-1"></i>
                                @if($user['nd_loaithanhvien'] == 'bac')
                                    Thành viên Bạc
                                @elseif($user['nd_loaithanhvien'] == 'vang')
                                    Thành viên Vàng
                                @elseif($user['nd_loaithanhvien'] == 'kimcuong')
                                    Thành viên Kim Cương
                                @else
                                    {{ ucfirst($user['nd_loaithanhvien']) }}
                                @endif
                            </span>
                        </h5>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <h4 class="text-primary mb-0">
                                <i class="bi bi-gem me-1"></i>{{ number_format($user['nd_diemtichluy'] ?? 0) }}
                            </h4>
                            <small class="text-muted">Điểm tích lũy</small>
                        </div>
                    </div>
                    @php
                        $points = $user['nd_diemtichluy'] ?? 0;
                        if($user['nd_loaithanhvien'] == 'bac') {
                            $nextLevel = 2000;
                            $progress = min($points / $nextLevel * 100, 100);
                            $label = $points . '/' . $nextLevel . ' điểm để lên Vàng';
                        } elseif($user['nd_loaithanhvien'] == 'vang') {
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
                                @if($user['nd_loaithanhvien'] == 'bac') bg-secondary
                                @elseif($user['nd_loaithanhvien'] == 'vang') bg-danger
                                @elseif($user['nd_loaithanhvien'] == 'kimcuong') bg-info
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
                        @if(($user['nd_trangthai'] ?? 'active') === 'active')
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Hoạt động
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-lock me-1"></i>Đã khóa
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Phân quyền:</strong><br>
                        @if(($user['nd_role'] ?? 'user') === 'admin')
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-shield-check me-1"></i>Quản trị viên
                            </span>
                        @else
                            <span class="badge bg-primary fs-6">
                                <i class="bi bi-person me-1"></i>Khách hàng
                            </span>
                        @endif
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        <a href="/sua-nguoi-dung?id={{ $user['nd_id'] }}" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil me-2"></i>Sửa thông tin
                        </a>
                        
                        @if($user['nd_id'] !== $_SESSION['user_id'])
                            <a href="/khoa-nguoi-dung?id={{ $user['nd_id'] }}" 
                               class="btn btn-{{ ($user['nd_trangthai'] ?? 'active') === 'active' ? 'danger' : 'success' }} btn-sm"
                               onclick="return confirm('Bạn có chắc chắn?')">
                                @if(($user['nd_trangthai'] ?? 'active') === 'active')
                                    <i class="bi bi-lock me-2"></i>Khóa tài khoản
                                @else
                                    <i class="bi bi-unlock me-2"></i>Mở khóa
                                @endif
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Thống kê hoạt động
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="text-primary mb-0">{{ $userStats['total_bookings'] ?? 0 }}</h5>
                                <small class="text-muted">Lượt đặt</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="text-success mb-0">{{ number_format($userStats['total_spent'] ?? 0) }}</h5>
                            <small class="text-muted">VNĐ chi</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('page-js')
<script src="/static/js/admin/ChiTietNguoiDung.js"></script>
@endsection
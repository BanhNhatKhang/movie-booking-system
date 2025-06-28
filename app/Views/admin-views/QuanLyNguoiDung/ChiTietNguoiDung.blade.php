@extends('layouts.admin.master')

@section('title', 'Chi tiết người dùng')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
                                        <th>Tổng tiền</th>
                                        <th>Ngày đặt</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bookingHistory as $booking)
                                    <tr>
                                        <td>{{ $booking['movie_name'] }}</td>
                                        <td>{{ date('d/m/Y H:i', strtotime($booking['showtime'])) }}</td>
                                        <td><span class="badge bg-info">{{ $booking['seats'] }}</span></td>
                                        <td><strong>{{ number_format($booking['total_price']) }} VNĐ</strong></td>
                                        <td>{{ date('d/m/Y H:i', strtotime($booking['booking_date'])) }}</td>
                                        <td>
                                            @if($booking['status'] === 'completed')
                                                <span class="badge bg-success">Hoàn thành</span>
                                            @elseif($booking['status'] === 'pending')
                                                <span class="badge bg-warning">Chờ xử lý</span>
                                            @else
                                                <span class="badge bg-danger">Đã hủy</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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

            {{-- Thống kê --}}
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
                                <h4 class="text-primary mb-0">{{ $userStats['total_bookings'] ?? 0 }}</h4>
                                <small class="text-muted">Lượt đặt vé</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">{{ number_format($userStats['total_spent'] ?? 0) }}</h4>
                            <small class="text-muted">VNĐ đã chi</small>
                        </div>
                    </div>
                    
                    <div class="mb-2">
                        <small class="text-muted">Thể loại ưa thích:</small><br>
                        <span class="badge bg-light text-dark">{{ $userStats['favorite_genre'] ?? 'Chưa có' }}</span>
                    </div>
                    
                    <div>
                        <small class="text-muted">Tham gia từ:</small><br>
                        <strong>{{ isset($userStats['join_date']) ? date('d/m/Y', strtotime($userStats['join_date'])) : 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
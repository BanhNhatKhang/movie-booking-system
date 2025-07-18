@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
@endsection

@section('content')
<main>
    <div class="profile-container">
        @php
            $hang = $user['nd_loaithanhvien'] ?? 'bac';
            $diem = $user['nd_diemtichluy'] ?? 0;
            $badge = [
                'bac' => 'light text-dark',
                'vang' => 'warning',
                'kimcuong' => 'info'
            ][$hang] ?? 'light text-dark';
            $tenHang = [
                'bac' => 'Thành viên Bạc',
                'vang' => 'Thành viên Vàng', 
                'kimcuong' => 'Thành viên Kim cương'
            ][$hang] ?? 'Thành viên Bạc';
        @endphp
        
        <!-- Header Profile -->
        <div class="text-center">
            <h1 class="profile-title">{{ $_SESSION['user_name'] ?? 'Người dùng' }}</h1>
            <span class="badge bg-{{ $badge }} profile-badge">{{ $tenHang }}</span>
            
            <div class="points-section">
                <span class="fw-semibold text-secondary me-2">Điểm tích lũy:</span>
                <span class="badge bg-warning text-dark">{{ number_format($diem) }} điểm</span>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <ul class="profile-info-list">
            <li>
                <span class="profile-info-label"> <strong>Email:</strong></span>
                <span class="profile-info-value">{{ $user['nd_email'] ?? 'Chưa cập nhật' }}</span>
            </li>
            <li>
                <span class="profile-info-label"> <strong>Số điện thoại:</strong></span>
                <span class="profile-info-value">{{ $user['nd_sdt'] ?? 'Chưa cập nhật' }}</span>
            </li>
            <li>
                <span class="profile-info-label"> <strong>Giới tính:</strong></span>
                <span class="profile-info-value">
                    {{ isset($user['nd_gioitinh']) ? ($user['nd_gioitinh'] ? 'Nam' : 'Nữ') : 'Chưa cập nhật' }}
                </span>
            </li>
            <li>
                <span class="profile-info-label"> <strong>Ngày sinh:</strong></span>
                <span class="profile-info-value">
                    {{ isset($user['nd_ngaysinh']) ? date('d/m/Y', strtotime($user['nd_ngaysinh'])) : 'Chưa cập nhật' }}
                </span>
            </li>
            <li>
                <span class="profile-info-label"> <strong>CCCD:</strong></span>
                <span class="profile-info-value">{{ $user['nd_cccd'] ?? 'Chưa cập nhật' }}</span>
            </li>
            <li>
                <span class="profile-info-label"> <strong>Ngày đăng ký:</strong></span>
                <span class="profile-info-value">
                    {{ isset($user['nd_ngaydangky']) ? date('d/m/Y', strtotime($user['nd_ngaydangky'])) : 'Chưa cập nhật' }}
                </span>
            </li>
        </ul>

        <!-- Action Buttons -->
        <div class="profile-btns">
            <a href="/lich-su-dat-ve" class="btn btn-outline-primary">
                <i class="bi bi-clock-history me-2"></i>Lịch sử đặt vé
            </a>
            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                <i class="bi bi-shield-lock me-2"></i>Đổi mật khẩu
            </button>
        </div>
    </div>

    <!-- Modal đổi mật khẩu -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-3">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="changePasswordModalLabel">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="/doi-mat-khau" id="changePasswordForm">
                        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control" name="current_password" id="currentPassword" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">Mật khẩu mới</label>
                            <input type="password" class="form-control" name="new_password" id="newPassword" required minlength="6">
                            <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirmPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cập nhật mật khẩu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('page-js')
<script src="/static/js/users/ThongTinCaNhan.js"></script>

@endsection
@extends('layouts.users.master')

@section('page-css')
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
    <style>
        .profile-container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
            padding: 36px 32px 28px 32px;
        }
        .profile-title {
            font-size: 2rem;
            font-weight: 700;
            color: #ff5858;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .profile-badge {
            font-size: 1rem;
            padding: 6px 18px;
            border-radius: 14px;
            margin-bottom: 12px;
            display: inline-block;
        }
        .profile-info-list {
            list-style: none;
            padding: 0;
            margin: 0 0 24px 0;
        }
        .profile-info-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f2f2f2;
        }
        .profile-info-label {
            color: #888;
            font-size: 1rem;
        }
        .profile-info-value {
            color: #222;
            font-weight: 500;
            font-size: 1.05rem;
        }
        .profile-btns {
            display: flex;
            gap: 16px;
            justify-content: center;
            margin-top: 18px;
        }
        .profile-btns .btn {
            min-width: 160px;
            font-size: 1.08rem;
            border-radius: 10px;
        }
    </style>
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
        <div class="text-center mb-2">
            <div class="profile-title">{{ $_SESSION['user_name'] ?? 'Người dùng' }}</div>
            <span class="badge bg-{{ $badge }} profile-badge">{{ $tenHang }}</span>
            <div class="mt-2 mb-2">
                <span class="fw-semibold text-secondary">Điểm tích lũy:</span>
                <span class="badge bg-warning text-dark ms-1">{{ $diem }}</span>
            </div>
        </div>
        <ul class="profile-info-list">
            <li>
                <span class="profile-info-label">Email</span>
                <span class="profile-info-value">{{ $user['nd_email'] ?? 'Chưa cập nhật' }}</span>
            </li>
            <li>
                <span class="profile-info-label">Số điện thoại</span>
                <span class="profile-info-value">{{ $user['nd_sdt'] ?? 'Chưa cập nhật' }}</span>
            </li>
            <li>
                <span class="profile-info-label">Giới tính</span>
                <span class="profile-info-value">
                    {{ isset($user['nd_gioitinh']) ? ($user['nd_gioitinh'] ? 'Nam' : 'Nữ') : 'Chưa cập nhật' }}
                </span>
            </li>
            <li>
                <span class="profile-info-label">Ngày sinh</span>
                <span class="profile-info-value">
                    {{ isset($user['nd_ngaysinh']) ? date('d/m/Y', strtotime($user['nd_ngaysinh'])) : 'Chưa cập nhật' }}
                </span>
            </li>
            <li>
                <span class="profile-info-label">CCCD</span>
                <span class="profile-info-value">{{ $user['nd_cccd'] ?? 'Chưa cập nhật' }}</span>
            </li>
            <li>
                <span class="profile-info-label">Ngày đăng ký</span>
                <span class="profile-info-value">
                    {{ isset($user['nd_ngaydangky']) ? date('d/m/Y', strtotime($user['nd_ngaydangky'])) : 'Chưa cập nhật' }}
                </span>
            </li>
        </ul>
        <div class="profile-btns">
            <a href="/lich-su-dat-ve" class="btn btn-outline-primary shadow-sm">Lịch sử đặt vé</a>
            <button class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                Đổi mật khẩu
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
<script>
    // Kiểm tra mật khẩu khớp
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Mật khẩu mới không khớp!');
            return false;
        }
        
        if (newPassword.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự!');
            return false;
        }
    });
</script>
@endsection
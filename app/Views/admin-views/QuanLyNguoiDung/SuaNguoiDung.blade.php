@extends('layouts.admin.master')

@section('title', 'Sửa thông tin người dùng')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-gear me-2"></i>Sửa thông tin người dùng</h1>
        <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/sua-nguoi-dung?id={{ $user['nd_id'] }}">
                        <input type="hidden" name="id" value="{{ $user['nd_id'] }}">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">ID người dùng</label>
                                <input type="text" class="form-control" value="{{ $user['nd_id'] }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" value="{{ $user['nd_tendangnhap'] ?? 'N/A' }}" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nd_hoten" 
                                   value="{{ $user['nd_hoten'] }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="nd_email" 
                                       value="{{ $user['nd_email'] }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="nd_sdt" 
                                       value="{{ $user['nd_sdt'] ?? '' }}" placeholder="0123456789">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Giới tính</label>
                                <select class="form-select" name="nd_gioitinh">
                                    {{-- ✅ SỬA: Sử dụng Boolean thay vì string --}}
                                    <option value="">Chọn giới tính</option>
                                    <option value="1" {{ ($user['nd_gioitinh'] ?? '') == '1' ? 'selected' : '' }}>Nam</option>
                                    <option value="0" {{ ($user['nd_gioitinh'] ?? '') == '0' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="nd_ngaysinh" 
                                       value="{{ $user['nd_ngaysinh'] ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Phân quyền</label>
                                <select class="form-select" name="nd_role" 
                                        {{ $user['nd_id'] == $_SESSION['user_id'] ? 'disabled' : '' }}>
                                    <option value="user" {{ ($user['nd_role'] ?? 'user') === 'user' ? 'selected' : '' }}>
                                        User (Khách hàng)
                                    </option>
                                    <option value="admin" {{ ($user['nd_role'] ?? 'user') === 'admin' ? 'selected' : '' }}>
                                        Admin (Quản trị viên)
                                    </option>
                                </select>
                                @if($user['nd_id'] == $_SESSION['user_id'])
                                    <small class="text-muted">Bạn không thể thay đổi role của chính mình</small>
                                    <input type="hidden" name="nd_role" value="{{ $user['nd_role'] }}">
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-select" name="nd_trangthai"
                                        {{ $user['nd_id'] == $_SESSION['user_id'] ? 'disabled' : '' }}>
                                    <option value="active" {{ ($user['nd_trangthai'] ?? 'active') === 'active' ? 'selected' : '' }}>
                                        Hoạt động
                                    </option>
                                    <option value="locked" {{ ($user['nd_trangthai'] ?? 'active') === 'locked' ? 'selected' : '' }}>
                                        Đã khóa
                                    </option>
                                </select>
                                @if($user['nd_id'] == $_SESSION['user_id'])
                                    <small class="text-muted">Bạn không thể khóa tài khoản của chính mình</small>
                                    <input type="hidden" name="nd_trangthai" value="{{ $user['nd_trangthai'] }}">
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CCCD/CMND</label>
                            <input type="text" class="form-control" name="nd_cccd" 
                                   value="{{ $user['nd_cccd'] ?? '' }}" placeholder="Số CCCD/CMND">
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="bi bi-save me-2"></i>Lưu thay đổi
                                </button>
                                <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                                </a>
                            </div>
                            
                            {{-- Reset mật khẩu --}}
                            @if($user['nd_id'] != $_SESSION['user_id'])
                                <div>
                                    {{-- ✅ SỬA: Sử dụng data attributes thay vì inline parameters --}}
                                    <button type="button" class="btn btn-warning btn-sm" 
                                            data-user-id="{{ $user['nd_id'] }}" 
                                            data-user-name="{{ $user['nd_hoten'] }}"
                                            onclick="resetPassword(this)">
                                        <i class="bi bi-key me-2"></i>Reset mật khẩu
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar thông tin --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-info-circle me-2"></i>Thông tin tài khoản
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Ngày đăng ký:</small><br>
                        <strong>{{ date('d/m/Y', strtotime($user['nd_ngaydangky'])) }}</strong>
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Trạng thái hiện tại:</small><br>
                        @if(($user['nd_trangthai'] ?? 'active') === 'active')
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Hoạt động
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="bi bi-lock me-1"></i>Đã khóa
                            </span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <small class="text-muted">Phân quyền:</small><br>
                        @if(($user['nd_role'] ?? 'user') === 'admin')
                            <span class="badge bg-danger">
                                <i class="bi bi-shield-check me-1"></i>Admin
                            </span>
                        @else
                            <span class="badge bg-primary">
                                <i class="bi bi-person me-1"></i>User
                            </span>
                        @endif
                    </div>

                    {{-- ✅ THÊM: Hiển thị giới tính --}}
                    <div class="mb-3">
                        <small class="text-muted">Giới tính:</small><br>
                        @if(isset($user['nd_gioitinh']))
                            @if($user['nd_gioitinh'] == 1)
                                <span class="badge bg-info">Nam</span>
                            @elseif($user['nd_gioitinh'] == 0)
                                <span class="badge bg-info">Nữ</span>
                            @else
                                <span class="badge bg-secondary">Chưa cập nhật</span>
                            @endif
                        @else
                            <span class="badge bg-secondary">Chưa cập nhật</span>
                        @endif
                    </div>

                    <hr>
                    
                    <div class="d-grid gap-2">
                        <a href="/chi-tiet-nguoi-dung?id={{ $user['nd_id'] }}" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-eye me-2"></i>Xem chi tiết
                        </a>
                        
                        @if($user['nd_id'] != $_SESSION['user_id'])
                            <a href="/khoa-nguoi-dung?id={{ $user['nd_id'] }}" 
                               class="btn btn-outline-{{ ($user['nd_trangthai'] ?? 'active') === 'active' ? 'danger' : 'success' }} btn-sm"
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
        </div>
    </div>
</div>

{{-- Modal reset password --}}
<form id="resetPasswordForm" method="POST" action="/reset-mat-khau" style="display: none;">
    <input type="hidden" name="id" id="resetUserId">
</form>
@endsection

{{-- ✅ SỬA: Đưa JavaScript vào section riêng để tránh conflict --}}
@section('page-js')
<script>
// ✅ SỬA: Function nhận element thay vì parameters
function resetPassword(button) {
    const userId = button.getAttribute('data-user-id');
    const userName = button.getAttribute('data-user-name');
    
    const message = 'Bạn có chắc chắn muốn reset mật khẩu cho "' + userName + '"?\nMật khẩu mới sẽ là: 123456';
    
    if (confirm(message)) {
        document.getElementById('resetUserId').value = userId;
        document.getElementById('resetPasswordForm').submit();
    }
}
</script>
@endsection
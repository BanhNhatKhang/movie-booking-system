@extends('layouts.admin.master')

@section('title', 'Thêm nhân viên')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-person-plus me-2"></i>Thêm nhân viên mới</h1>
        <a href="/quan-ly-nhan-vien" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if(isset($_SESSION['error_message']))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $_SESSION['error_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    @endif

    @if(isset($_SESSION['success_message']))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ $_SESSION['success_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    @endif
    
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-2"></i>Thông tin nhân viên
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/them-nhan-vien">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nd_hoten" 
                                       value="{{ $_SESSION['form_data']['nd_hoten'] ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="nd_email" 
                                       value="{{ $_SESSION['form_data']['nd_email'] ?? '' }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nd_tendangnhap" 
                                       value="{{ $_SESSION['form_data']['nd_tendangnhap'] ?? '' }}" required>
                                <small class="text-muted">Dùng để đăng nhập hệ thống</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" name="nd_sdt" 
                                       value="{{ $_SESSION['form_data']['nd_sdt'] ?? '' }}" placeholder="0123456789">
                                <small class="text-muted">10 chữ số</small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="nd_matkhau" required>
                                <small class="text-muted">Tối thiểu 6 ký tự</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="xac_nhan_mat_khau" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Giới tính</label>
                                <select class="form-select" name="nd_gioitinh">
                                    <option value="">Chọn giới tính</option>
                                    <option value="1" {{ ($_SESSION['form_data']['nd_gioitinh'] ?? '') == '1' ? 'selected' : '' }}>Nam</option>
                                    <option value="0" {{ ($_SESSION['form_data']['nd_gioitinh'] ?? '') == '0' ? 'selected' : '' }}>Nữ</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" name="nd_ngaysinh" 
                                       value="{{ $_SESSION['form_data']['nd_ngaysinh'] ?? '' }}">
                                <small class="text-muted">Phải đủ 18 tuổi (không quá 65 tuổi)</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">CCCD/CMND</label>
                            <input type="text" class="form-control" name="nd_cccd" 
                                   value="{{ $_SESSION['form_data']['nd_cccd'] ?? '' }}" placeholder="123456789012">
                            <small class="text-muted">12 chữ số</small>
                        </div>

                        {{-- Role tự động = admin --}}
                        <input type="hidden" name="nd_role" value="admin">
                        
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Lưu ý:</strong> Nhân viên mới sẽ tự động có quyền Admin và có thể đăng nhập ngay.
                        </div>

                        <hr>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-success me-2">
                                    <i class="bi bi-save me-2"></i>Tạo nhân viên
                                </button>
                                <a href="/quan-ly-nhan-vien" class="btn btn-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sidebar hướng dẫn --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-lightbulb me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-2"></i>
                            Role tự động: <strong>Admin</strong>
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-2"></i>
                            Có thể đăng nhập ngay
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check text-success me-2"></i>
                            Quản lý toàn bộ hệ thống
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-info text-primary me-2"></i>
                            Email phải duy nhất
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-info text-primary me-2"></i>
                            Username phải duy nhất
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-info text-warning me-2"></i>
                            Tuổi: 18-65
                        </li>
                        <li>
                            <i class="bi bi-info text-warning me-2"></i>
                            CCCD: 12 số
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">
                        <i class="bi bi-shield-check me-2"></i>Quy tắc validation
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-1">
                            <i class="bi bi-dot"></i>Mật khẩu ≥ 6 ký tự
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-dot"></i>SĐT: 10 chữ số
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-dot"></i>CCCD: 12 chữ số
                        </li>
                        <li class="mb-1">
                            <i class="bi bi-dot"></i>Email format chuẩn
                        </li>
                        <li>
                            <i class="bi bi-dot"></i>Không trùng lặp dữ liệu
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
if (isset($_SESSION['form_data'])) {
    unset($_SESSION['form_data']);
}
?>
@endsection

@section('page-js')
<script>
// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endsection
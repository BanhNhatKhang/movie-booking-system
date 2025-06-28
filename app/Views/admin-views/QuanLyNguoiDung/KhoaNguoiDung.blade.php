{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyNguoiDung\KhoaNguoiDung.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Khóa Người Dùng')

@section('page-css')
    <link rel="stylesheet" href="/static/css/admin/KhoaNguoiDung.css" />
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col">
            <div class="admin-container">
                <div class="admin-header">
                    <h2 class="admin-title">KHÓA TÀI KHOẢN NGƯỜI DÙNG</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/quan-ly-nguoi-dung">Quản lý người dùng</a></li>
                            <li class="breadcrumb-item active">Khóa tài khoản</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="admin-content">
                    @if(isset($_SESSION['success_message']))
                        {{-- Thành công khóa tài khoản --}}
                        <div class="text-center">
                            <div class="icon-lock mb-4">
                                <i class="bi bi-lock-fill text-success" style="font-size: 4rem;"></i>
                            </div>
                            <div class="alert alert-success mb-4">
                                <h4 class="alert-heading">Khóa tài khoản thành công!</h4>
                                <p class="mb-0">{{ $_SESSION['success_message'] }}</p>
                            </div>
                            <a href="/admin/quan-ly-nguoi-dung" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách người dùng
                            </a>
                        </div>
                    @elseif(isset($_SESSION['error_message']))
                        {{-- Lỗi khi khóa tài khoản --}}
                        <div class="text-center">
                            <div class="icon-lock mb-4">
                                <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <div class="alert alert-danger mb-4">
                                <h4 class="alert-heading">Có lỗi xảy ra!</h4>
                                <p class="mb-0">{{ $_SESSION['error_message'] }}</p>
                            </div>
                            <a href="/admin/quan-ly-nguoi-dung" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    @elseif(isset($nguoidung))
                        {{-- Form xác nhận khóa tài khoản --}}
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-lock me-2"></i>Xác nhận khóa tài khoản
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Bạn có chắc chắn muốn khóa tài khoản người dùng sau đây?</p>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>ID:</strong> {{ $nguoidung['id'] ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Họ tên:</strong> {{ $nguoidung['ho_ten'] ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Email:</strong> {{ $nguoidung['email'] ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Số điện thoại:</strong> {{ $nguoidung['so_dien_thoai'] ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Trạng thái hiện tại:</strong> 
                                        <span class="badge bg-success">Hoạt động</span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Ngày đăng ký:</strong> {{ $nguoidung['ngay_dang_ky'] ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Lưu ý:</strong> Sau khi khóa, người dùng sẽ không thể đăng nhập vào hệ thống.
                                </div>

                                <form method="post" action="/admin/khoa-nguoi-dung">
                                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                                    <input type="hidden" name="id" value="{{ $nguoidung['id'] ?? '' }}">
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-warning me-2">
                                            <i class="bi bi-lock me-2"></i>Xác nhận khóa
                                        </button>
                                        <a href="/admin/quan-ly-nguoi-dung" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        {{-- Không tìm thấy người dùng --}}
                        <div class="alert alert-warning text-center">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Không tìm thấy thông tin người dùng cần khóa.
                        </div>
                        <div class="text-center">
                            <a href="/admin/quan-ly-nguoi-dung" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
    <script>
        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('alert-success') || alert.classList.contains('alert-danger')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    </script>
@endsection
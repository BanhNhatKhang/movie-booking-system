{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyNhanVien\XoaNhanVien.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Xóa Nhân Viên')

@section('page-css')
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css" />
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col">
            <div class="admin-container">
                <div class="admin-header">
                    <h2 class="admin-title">XÓA NHÂN VIÊN</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/admin">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="/admin/quan-ly-nhan-vien">Quản lý nhân viên</a></li>
                            <li class="breadcrumb-item active">Xóa nhân viên</li>
                        </ol>
                    </nav>
                </div>
                
                <div class="admin-content">
                    @if(isset($_SESSION['success_message']))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ $_SESSION['success_message'] }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center">
                            <a href="/admin/quan-ly-nhan-vien" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
                            </a>
                        </div>
                    @elseif(isset($_SESSION['error_message']))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            {{ $_SESSION['error_message'] }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <div class="text-center">
                            <a href="/admin/quan-ly-nhan-vien" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Quay lại
                            </a>
                        </div>
                    @elseif(isset($nhanvien))
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Xác nhận xóa nhân viên
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-3">Bạn có chắc chắn muốn xóa nhân viên sau đây?</p>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Họ tên:</strong> {{ $nhanvien['ho_ten'] ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Email:</strong> {{ $nhanvien['email'] ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Số điện thoại:</strong> {{ $nhanvien['so_dien_thoai'] ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Chức vụ:</strong> {{ $nhanvien['chuc_vu'] ?? 'N/A' }}
                                    </div>
                                </div>

                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác!
                                </div>

                                <form method="post" action="/admin/xoa-nhan-vien">
                                    <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
                                    <input type="hidden" name="id" value="{{ $nhanvien['id'] ?? '' }}">
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger me-2">
                                            <i class="bi bi-trash me-2"></i>Xác nhận xóa
                                        </button>
                                        <a href="/admin/quan-ly-nhan-vien" class="btn btn-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Không tìm thấy thông tin nhân viên cần xóa.
                        </div>
                        <div class="text-center">
                            <a href="/admin/quan-ly-nhan-vien" class="btn btn-primary">
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
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection
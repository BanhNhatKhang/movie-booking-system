@extends('layouts.admin.master')

@section('title', 'Khóa/Mở khóa người dùng')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-lock me-2"></i>Khóa/Mở khóa tài khoản</h1>
        <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    @if(isset($nguoidung))
        {{-- Form xác nhận --}}
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    @php $currentStatus = $nguoidung['nd_trangthai'] ?? 'active'; @endphp
                    @if($currentStatus === 'active')
                        <i class="bi bi-lock me-2"></i>Xác nhận khóa tài khoản
                    @else
                        <i class="bi bi-unlock me-2"></i>Xác nhận mở khóa tài khoản
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">
                    @if($currentStatus === 'active')
                        Bạn có chắc chắn muốn <strong class="text-danger">khóa</strong> tài khoản người dùng sau đây?
                    @else
                        Bạn có chắc chắn muốn <strong class="text-success">mở khóa</strong> tài khoản người dùng sau đây?
                    @endif
                </p>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>ID:</strong> {{ $nguoidung['nd_id'] }}
                    </div>
                    <div class="col-md-6">
                        <strong>Họ tên:</strong> {{ $nguoidung['nd_hoten'] }}
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Email:</strong> {{ $nguoidung['nd_email'] }}
                    </div>
                    <div class="col-md-6">
                        <strong>Số điện thoại:</strong> {{ $nguoidung['nd_sdt'] ?? 'Chưa cập nhật' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Trạng thái hiện tại:</strong> 
                        @if($currentStatus === 'active')
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Đã khóa</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Ngày đăng ký:</strong> {{ date('d/m/Y', strtotime($nguoidung['nd_ngaydangky'])) }}
                    </div>
                </div>

                <div class="alert alert-{{ $currentStatus === 'active' ? 'warning' : 'info' }}">
                    <i class="bi bi-{{ $currentStatus === 'active' ? 'exclamation-triangle' : 'info-circle' }} me-2"></i>
                    <strong>Lưu ý:</strong> 
                    @if($currentStatus === 'active')
                        Sau khi khóa, người dùng sẽ không thể đăng nhập vào hệ thống.
                    @else
                        Sau khi mở khóa, người dùng có thể đăng nhập lại bình thường.
                    @endif
                </div>

                <form method="POST" action="/khoa-nguoi-dung">
                    <input type="hidden" name="id" value="{{ $nguoidung['nd_id'] }}">
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-{{ $currentStatus === 'active' ? 'danger' : 'success' }} me-2">
                            @if($currentStatus === 'active')
                                <i class="bi bi-lock me-2"></i>Xác nhận khóa
                            @else
                                <i class="bi bi-unlock me-2"></i>Xác nhận mở khóa
                            @endif
                        </button>
                        <a href="/quan-ly-nguoi-dung" class="btn btn-secondary">
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
            Không tìm thấy thông tin người dùng.
        </div>
        <div class="text-center">
            <a href="/quan-ly-nguoi-dung" class="btn btn-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    @endif
</div>
@endsection
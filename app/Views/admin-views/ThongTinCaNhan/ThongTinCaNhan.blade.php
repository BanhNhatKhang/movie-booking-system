@extends('layouts.admin.master')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container py-4 content">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-person-badge me-2"></i>Thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Họ tên:</strong></div>
                        <div class="col-sm-8">{{ $admin['nd_hoten'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Email:</strong></div>
                        <div class="col-sm-8">{{ $admin['nd_email'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Số điện thoại:</strong></div>
                        <div class="col-sm-8">{{ $admin['nd_sdt'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>CCCD:</strong></div>
                        <div class="col-sm-8">{{ $admin['nd_cccd'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Ngày sinh:</strong></div>
                        <div class="col-sm-8">
                            @if($admin['nd_ngaysinh'])
                                {{ date('d/m/Y', strtotime($admin['nd_ngaysinh'])) }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Giới tính:</strong></div>
                        <div class="col-sm-8">
                            @if(isset($admin['nd_gioitinh']))
                                {{ $admin['nd_gioitinh'] ? 'Nam' : 'Nữ' }}
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Tên đăng nhập:</strong></div>
                        <div class="col-sm-8">{{ $admin['nd_tendangnhap'] ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Vai trò:</strong></div>
                        <div class="col-sm-8">
                            <span class="badge bg-success">{{ ucfirst($admin['nd_role'] ?? 'admin') }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>Trạng thái:</strong></div>
                        <div class="col-sm-8">
                            @if(($admin['nd_trangthai'] ?? 'active') == 'active')
                                <span class="badge bg-success">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Bị khóa</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <a href="/dashboard" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Quay lại Dashboard
                        </a>
                        <a href="/admin/doi-mat-khau" class="btn btn-warning">
                            <i class="bi bi-key me-1"></i>Đổi mật khẩu
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
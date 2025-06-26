{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyNhanVien\QuanLyNhanVien.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Quản lý Nhân viên')

@section('page-css')
    <link rel="stylesheet" href="/static/css/admin/QuanLyNhanVien.css">
@endsection

@section('content')
<div class="container py-4 content">
    <h1>Quản lý Nhân viên</h1><hr>
    
    @if(isset($_SESSION['success_message']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="/admin/them-nhan-vien" class="btn btn-primary text-white text-decoration-none">
            <i class="bi bi-plus-circle"></i> Thêm nhân viên
        </a>
        <form class="d-flex gap-2" method="get" action="/admin/quan-ly-nhan-vien">
            <input type="text" name="search" class="form-control" style="min-width:200px;" 
                   placeholder="Tìm theo tên, email, số điện thoại..." 
                   value="{{ $search ?? '' }}">
            <button class="btn btn-outline-primary" type="submit">
                <i class="bi bi-search"></i> Tìm kiếm
            </button>
            @if($search ?? '')
                <a href="/admin/quan-ly-nhan-vien" class="btn btn-outline-secondary">
                    <i class="bi bi-x"></i> Xóa bộ lọc
                </a>
            @endif
        </form>
    </div>
    
    <!-- Danh sách nhân viên -->
    <div class="table-responsive p-3">
        <table class="table align-middle table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Chức vụ</th>
                    <th>Trạng thái</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nhanViens as $nv)
                <tr>
                    <td>{{ $nv['id'] }}</td>
                    <td>{{ $nv['ho_ten'] }}</td>
                    <td>{{ $nv['email'] }}</td>
                    <td>{{ $nv['so_dien_thoai'] }}</td>
                    <td>{{ $nv['chuc_vu'] }}</td>
                    <td>
                        @if($nv['trang_thai'] == 1)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-danger">Ngưng hoạt động</span>
                        @endif
                    </td>
                    <td>
                        <a href="/admin/sua-nhan-vien?id={{ $nv['id'] }}" 
                           class="btn btn-warning btn-sm me-1" title="Sửa">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="/admin/xoa-nhan-vien?id={{ $nv['id'] }}" 
                           class="btn btn-danger btn-sm" title="Xóa">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        @if($search ?? '')
                            Không tìm thấy nhân viên nào với từ khóa "{{ $search }}"
                        @else
                            Chưa có nhân viên nào trong hệ thống
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
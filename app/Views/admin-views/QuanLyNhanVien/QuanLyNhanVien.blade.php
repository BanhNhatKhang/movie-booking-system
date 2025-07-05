@extends('layouts.admin.master')

@section('title', 'Quản lý Nhân viên')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people-fill me-2"></i>Quản lý Nhân viên</h1>
        <a href="/them-nhan-vien" class="btn btn-success">
            <i class="bi bi-person-plus me-2"></i>Thêm nhân viên
        </a>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Bộ lọc tìm kiếm</h5>
        </div>
        <div class="card-body">
            <form class="row g-3 align-items-end" method="GET">
                <div class="col-md-3">
                    <label class="form-label">Tìm kiếm tên/email/SĐT</label>
                    <input type="text" class="form-control" name="q" placeholder="Nhập tên, email hoặc số điện thoại..." 
                           value="{{ $search }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sắp xếp theo</label>
                    <select class="form-select" name="sort">
                        <option value="newest" {{ ($sort ?? 'newest') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="name" {{ ($sort ?? '') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="status" {{ ($sort ?? '') == 'status' ? 'selected' : '' }}>Trạng thái</option>
                        <option value="id" {{ ($sort ?? '') == 'id' ? 'selected' : '' }}>ID</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Trạng thái</label>
                    <select class="form-select" name="status">
                        <option value="">Tất cả</option>
                        <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ $status == 'inactive' ? 'selected' : '' }}>Đã khóa</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="bi bi-search me-2"></i>Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách nhân viên -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">
                <i class="bi bi-table me-2"></i>Danh sách nhân viên 
            </h5>
            <span class="badge bg-secondary">{{ $totalNhanViens }} người</span>
        </div>
        <div class="card-body">
            @if(!empty($nhanViens))
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nhanViens as $nv)
                            <tr>
                                <td>
                                    <strong>{{ $nv['nd_id'] }}</strong>
                                    <br><small class="text-muted">{{ $nv['nd_tendangnhap'] ?? 'N/A' }}</small>
                                </td>
                                <td>
    <div class="d-flex align-items-center">
        <div>
            <strong>{{ $nv['nd_hoten'] }}</strong>
            @if($nv['nd_id'] === $_SESSION['user_id'])
                <span class="badge bg-primary ms-1">
                    <i class="bi bi-person-check me-1"></i>Bạn
                </span>
            @endif
            <br><span class="badge bg-danger">
                <i class="bi bi-shield-check me-1"></i>Admin
            </span>
        </div>
    </div>
</td>
                                <td>{{ $nv['nd_email'] }}</td>
                                <td>
                                    @if(!empty($nv['nd_sdt']))
                                        <span class="badge bg-secondary">
                                            <i class="bi bi-telephone me-1"></i>{{ $nv['nd_sdt'] }}
                                        </span>
                                    @else
                                        <span class="text-muted">Chưa cập nhật</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($nv['nd_trangthai'] ?? 'active') === 'active')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Hoạt động
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Đã khóa
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="/chi-tiet-nhan-vien?id={{ $nv['nd_id'] }}" 
                                           class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="/sua-nhan-vien?id={{ $nv['nd_id'] }}" 
                                           class="btn btn-warning btn-sm" title="Sửa thông tin">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($nv['nd_id'] !== $_SESSION['user_id'])
                                            <a href="/khoa-nhan-vien?id={{ $nv['nd_id'] }}" 
                                               class="btn btn-{{ ($nv['nd_trangthai'] ?? 'active') === 'active' ? 'danger' : 'success' }} btn-sm" 
                                               title="{{ ($nv['nd_trangthai'] ?? 'active') === 'active' ? 'Khóa tài khoản' : 'Kích hoạt' }}"
                                               onclick="return confirm('Bạn có chắc chắn?')">
                                                @if(($nv['nd_trangthai'] ?? 'active') === 'active')
                                                    <i class="bi bi-lock"></i>
                                                @else
                                                    <i class="bi bi-unlock"></i>
                                                @endif
                                            </a>
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled title="Không thể khóa chính mình">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-person-x text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">Không có nhân viên nào</h5>
                    <p class="text-muted">Chưa có user nào có role = Admin</p>
                </div>
            @endif
            
            <!-- Phân trang -->
            @if($totalPages > 1)
                @include('layouts.admin.Pagination', [
                    'currentPage' => $currentPage,
                    'totalPages' => $totalPages,
                    'totalItems' => $totalNhanViens,
                    'itemsPerPage' => 15,
                    'itemName' => 'nhân viên',
                    'search' => $search,
                    'status' => $status
                ])
            @endif
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 14px;
}
</style>
@endsection
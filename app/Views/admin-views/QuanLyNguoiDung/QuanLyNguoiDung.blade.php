@extends('layouts.admin.master')

@section('title', 'Quản lý Khách hàng')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyNguoiDung.css">
    <link rel="stylesheet" href="/static/css/admin/Pagination.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-people me-2"></i>Quản lý Khách hàng</h1>
    </div>
    
    <!-- Bộ lọc -->
    <div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0"><i class="bi bi-funnel me-2"></i>Bộ lọc tìm kiếm</h5>
    </div>
    <div class="card-body">
        <form class="row g-3 align-items-end" method="GET">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm tên/email/SĐT</label>
                <input type="text" class="form-control" name="q" placeholder="Nhập tên, email hoặc số điện thoại..." 
                    value="{{ $search }}">
            </div>
        
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select class="form-select" name="status">
                    <option value="">Tất cả</option>
                    <option value="active" {{ $status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="locked" {{ $status == 'locked' ? 'selected' : '' }}>Đã khóa</option>
                </select>
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
            
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-search me-2"></i>Tìm kiếm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Danh sách khách hàng -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center mb-4">
        <h5 class="card-title mb-0"><i class="bi bi-table me-2"></i>Danh sách khách hàng</h5>
        <div>
            <span class="badge bg-primary me-2">{{ count($users) }} khách hàng</span>
        </div>
    </div>
    <div class="card-body">
        @if(!empty($users))
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
                        @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user['nd_id'] }}</strong></td>
                            
                            <td>{{ $user['nd_hoten'] }}</td>
                            <td>{{ $user['nd_email'] }}</td>
                            
                            <td>
                                @if(!empty($user['nd_sdt']))
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-telephone me-1"></i>{{ $user['nd_sdt'] }}
                                    </span>
                                @else
                                    <span class="text-muted">Chưa cập nhật</span>
                                @endif
                            </td>
                            
                            <td>
                                @if(($user['nd_trangthai'] ?? 'active') === 'active')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Hoạt động
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="bi bi-lock me-1"></i>Đã khóa
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="/chi-tiet-nguoi-dung?id={{ $user['nd_id'] }}" 
                                       class="btn btn-info btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="/sua-nguoi-dung?id={{ $user['nd_id'] }}" 
                                       class="btn btn-warning btn-sm" title="Sửa thông tin">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="/khoa-nguoi-dung?id={{ $user['nd_id'] }}" 
                                       class="btn btn-{{ ($user['nd_trangthai'] ?? 'active') === 'active' ? 'danger' : 'success' }} btn-sm" 
                                       title="{{ ($user['nd_trangthai'] ?? 'active') === 'active' ? 'Khóa tài khoản' : 'Mở khóa' }}"
                                       onclick="return confirm('Bạn có chắc chắn?')">
                                        @if(($user['nd_trangthai'] ?? 'active') === 'active')
                                            <i class="bi bi-lock"></i>
                                        @else
                                            <i class="bi bi-unlock"></i>
                                        @endif
                                    </a>
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
                <h5 class="text-muted mt-3">Không tìm thấy khách hàng</h5>
                <p class="text-muted">Thử thay đổi bộ lọc tìm kiếm</p>
            </div>
        @endif
        
        <!-- Phân trang -->
        @include('layouts.admin.Pagination', [
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
            'totalItems' => $totalUsers,
            'itemsPerPage' => 15,
            'itemName' => 'khách hàng',
            'search' => $search,
            'status' => $status
        ])
    </div>
</div>
</div>
@endsection

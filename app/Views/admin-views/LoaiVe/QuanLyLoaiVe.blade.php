@extends('layouts.admin.master')

@section('title', 'Quản lý loại vé')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="content" style="background-color: white;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Quản lý loại vé</h1>
        <a href="/them-loai-ve" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Thêm loại vé
        </a>
    </div>
    
    <hr>
    
    <!-- Thông báo -->
    @if(isset($_SESSION['success_message']))
        <div class="alert alert-success alert-dismissible fade show">
            {{ $_SESSION['success_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    @endif
    
    @if(isset($_SESSION['error_message']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    @endif
    
    <!-- Form tìm kiếm -->
    <div class="row mb-3">
        <div class="col-md-6">
            <form method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           value="{{ $filters['search'] ?? '' }}" 
                           placeholder="Tìm kiếm theo mã hoặc tên loại vé...">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Danh sách loại vé -->
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Mã loại vé</th>
                    <th>Tên loại vé</th>
                    <th>Giá tiền</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($loaiVeList as $loaiVe)
                    <tr>
                        <td><strong>{{ $loaiVe['lv_maloaive'] }}</strong></td>
                        <td>{{ $loaiVe['lv_tenloaive'] }}</td>
                        <td><strong>{{ number_format($loaiVe['lv_giatien'], 0, ',', '.') }} VNĐ</strong></td>
                        <td>
                            <a href="/sua-loai-ve?id={{ $loaiVe['lv_maloaive'] }}" 
                               class="btn btn-warning btn-sm" title="Sửa">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $loaiVe['lv_maloaive'] }}"
                                    data-name="{{ $loaiVe['lv_tenloaive'] }}"
                                    title="Xóa">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Không có loại vé nào
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Phân trang -->
    @include('layouts.admin.Pagination', [
        'currentPage' => $currentPage,
        'totalPages' => $totalPages,
        'totalItems' => $totalLoaiVe,
        'itemsPerPage' => 10,
        'itemName' => 'loại vé',
        'search' => $filters['search'] ?? ''
    ])
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa loại vé <strong id="deleteItemName"></strong>?</p>
                <p class="text-danger">Thao tác này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" action="/xoa-loai-ve" style="display: inline;">
                    <input type="hidden" name="id" id="deleteId">
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sử dụng event delegation cho các nút xóa
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteItemName').textContent = name;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        });
    });
});
</script>
@endsection
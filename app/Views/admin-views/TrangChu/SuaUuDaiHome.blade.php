@extends('layouts.admin.master')

@section('title', 'Sửa ưu đãi')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/SuaUuDaiHome.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Sửa ưu đãi: {{ $uuDai['udtc_mauudai'] ?? 'N/A' }}</h4>
    
    <!-- Thông báo lỗi -->
    @if(isset($_GET['error']))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            @if($_GET['error'] == 'empty_data')
                Vui lòng điền đầy đủ thông tin!
            @elseif($_GET['error'] == 'name_exists')
                Tên ưu đãi đã tồn tại!
            @elseif($_GET['error'] == 'upload_failed')
                Upload ảnh thất bại!
            @elseif($_GET['error'] == 'update_failed')
                Cập nhật ưu đãi thất bại!
            @elseif($_GET['error'] == 'system_error')
                Lỗi hệ thống, vui lòng thử lại!
            @else
                Có lỗi xảy ra!
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="POST" enctype="multipart/form-data" action="/cap-nhat-uu-dai-home">
        <input type="hidden" name="id" value="{{ $uuDai['udtc_mauudai'] }}">
        
        <div class="mb-3">
            <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
            
            @if($uuDai['udtc_anhuudai'])
                <div class="mb-2">
                    <img src="{{ $uuDai['udtc_anhuudai'] }}" 
                         alt="Ảnh hiện tại" 
                         class="current-image"
                         style="max-width: 200px; max-height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                    <p class="text-muted mt-1 mb-0"><small>Ảnh hiện tại</small></p>
                </div>
            @endif
            
            <input type="file" 
                   class="form-control" 
                   id="anhUuDai" 
                   name="anhUuDai" 
                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" />
            <div class="form-text">Để trống nếu không muốn thay đổi ảnh. Định dạng: JPG, PNG, GIF, WebP. Kích thước tối đa: 5MB</div>
            
            <!-- Preview ảnh mới -->
            <div class="image-preview-container mt-2" style="display: none;">
                <div class="image-preview" id="imagePreview">
                    <button type="button" class="btn btn-sm btn-danger remove-preview" id="removePreview">
                        <i class="bi bi-x"></i> Hủy ảnh mới
                    </button>
                    <img class="preview-img mt-2" 
                         id="previewImg" 
                         src="" 
                         alt="Preview"
                         style="max-width: 200px; max-height: 150px; object-fit: cover; border: 1px solid #ddd; border-radius: 4px;">
                    <p class="text-muted mt-1 mb-0"><small>Ảnh mới sẽ thay thế</small></p>
                </div>
            </div>
        </div>
        
        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-save"></i> Cập nhật
            </button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary ms-2">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/static/js/admin/SuaUuDaiHome.js"></script>
@endsection
@extends('layouts.admin.master')

@section('title', 'Thêm ưu đãi')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <link href="/static/css/admin/ThemUuDaiHome.css" rel="stylesheet">
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Thêm ưu đãi mới</h4>
    
    <!-- thông báo lỗi -->
    @if(isset($_GET['error']))
        @if($_GET['error'] == 'empty_name')
            <div class="alert alert-danger">Vui lòng nhập tên ưu đãi!</div>
        @elseif($_GET['error'] == 'name_exists')
            <div class="alert alert-danger">Tên ưu đãi đã tồn tại!</div>
        @elseif($_GET['error'] == 'upload_failed')
            <div class="alert alert-danger">Upload ảnh thất bại! Vui lòng kiểm tra định dạng và kích thước file.</div>
        @elseif($_GET['error'] == 'create_failed')
            <div class="alert alert-danger">Thêm ưu đãi thất bại!</div>
        @elseif($_GET['error'] == 'system_error')
            <div class="alert alert-danger">Lỗi hệ thống, vui lòng thử lại!</div>
        @endif
    @endif

    <form method="POST" enctype="multipart/form-data" action="/luu-uu-dai-home">
        <div class="mb-3">
            <label class="form-label fw-bold">Ảnh ưu đãi <span class="text-danger">*</span></label>
            <input type="file" class="form-control" name="anhUuDai" id="anhUuDai" accept="image/*" required>
            <small class="text-muted">Chỉ chấp nhận file ảnh (JPG, PNG). Tối đa 5MB.</small>
            
            <!-- preview ảnh -->
            <div class="image-preview-container">
                <div class="image-preview" id="imagePreview">
                    <button type="button" class="remove-preview" id="removePreview">
                        <i class="bi bi-x"></i>
                    </button>
                    <img class="preview-img" id="previewImg" src="" alt="Preview">
                </div>
            </div>
        </div>
        
        
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-lg"></i> Thêm ưu đãi
            </button>
            <a href="/quan-ly-trang-chu" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/static/js/admin/ThemUuDaiHome.js"></script>
@endsection
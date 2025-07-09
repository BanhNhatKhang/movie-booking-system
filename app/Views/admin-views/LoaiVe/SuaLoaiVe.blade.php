@extends('layouts.admin.master')

@section('title', 'Sửa loại vé')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="content" style="background-color: white;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sửa loại vé: {{ $loaiVe['lv_tenloaive'] }}</h1>
        <a href="/quan-ly-loai-ve" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại
        </a>
    </div>
    
    <hr>
    
    <!-- Thông báo lỗi -->
    @if(isset($_SESSION['error_message']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    @endif
    
    <!-- Form sửa loại vé -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin loại vé</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/cap-nhat-loai-ve">
                        <input type="hidden" name="id" value="{{ $loaiVe['lv_maloaive'] }}">
                        
                        <div class="mb-3">
                            <label for="ma_loai_ve" class="form-label">Mã loại vé</label>
                            <input type="text" class="form-control" id="ma_loai_ve" 
                                   value="{{ $loaiVe['lv_maloaive'] }}" disabled>
                            <div class="form-text">Mã loại vé không thể thay đổi</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="ten_loai_ve" class="form-label">
                                Tên loại vé <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="ten_loai_ve" name="ten_loai_ve" 
                                   value="{{ $loaiVe['lv_tenloaive'] }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="gia_tien" class="form-label">
                                Giá tiền (VNĐ) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control" id="gia_tien" name="gia_tien" 
                                   value="{{ $loaiVe['lv_giatien'] }}" required min="1000" step="1000">
                            <div class="form-text">Giá tiền phải lớn hơn 0</div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/quan-ly-loai-ve" class="btn btn-secondary me-md-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Cập nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\UuDai\ThemUuDai.blade.php --}}

@extends('layouts.admin.master')

@section('title', 'Thêm ưu đãi mới')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Thêm ưu đãi mới</h1>
        <a href="/quan-ly-uu-dai" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
    <hr>
    
    {{-- Error Messages --}}
    @if(isset($error))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ $error }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <form action="/them-uu-dai" method="POST" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-8">
            {{-- Tên ưu đãi --}}
            <div class="mb-3">
                <label for="tenUuDai" class="form-label">Tên ưu đãi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tenUuDai" name="tenUuDai" 
                       value="{{ $oldData['tenUuDai'] ?? '' }}" required>
            </div>
            
            {{-- Loại ưu đãi --}}
            <div class="mb-3">
                <label for="loaiUuDai" class="form-label">Loại ưu đãi <span class="text-danger">*</span></label>
                <select class="form-select" id="loaiUuDai" name="loaiUuDai" required>
                    <option value="">Chọn loại ưu đãi</option>
                    <option value="COMBO" {{ ($oldData['loaiUuDai'] ?? '') === 'COMBO' ? 'selected' : '' }}>COMBO</option>\
                    <option value="THÀNH VIÊN" {{ ($oldData['loaiUuDai'] ?? '') === 'THÀNH VIÊN' ? 'selected' : '' }}>THÀNH VIÊN</option>
                    <option value="GIẢM GIÁ" {{ ($oldData['loaiUuDai'] ?? '') === 'GIẢM GIÁ' ? 'selected' : '' }}>GIẢM GIÁ</option>
                    <option value="SINH NHẬT" {{ ($oldData['loaiUuDai'] ?? '') === 'SINH NHẬT' ? 'selected' : '' }}>SINH NHẬT</option>
                    <option value="SỚM" {{ ($oldData['loaiUuDai'] ?? '') === 'SỚM' ? 'selected' : '' }}>SỚM</option>
                    <option value="NGÂN HÀNG" {{ ($oldData['loaiUuDai'] ?? '') === 'NGÂN HÀNG' ? 'selected' : '' }}>NGÂN HÀNG</option>
                </select>
            </div>
            
            {{-- Nội dung chi tiết --}}
            <div class="mb-3">
                <label for="noiDungChiTiet" class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                <textarea class="form-control" id="noiDungChiTiet" name="noiDungChiTiet" rows="4" 
                          placeholder="Mô tả chi tiết về ưu đãi..." required>{{ $oldData['noiDungChiTiet'] ?? '' }}</textarea>
            </div>
            
            {{-- Thời gian --}}
            <div class="row">
                <div class="col-md-6">
                    <label for="dateUuDai" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dateUuDai" name="dateUuDai" 
                           value="{{ $oldData['dateUuDai'] ?? '' }}" required>
                </div>
                <div class="col-md-6">
                    <label for="dateUuDaiEnd" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dateUuDaiEnd" name="dateUuDaiEnd" 
                           value="{{ $oldData['dateUuDaiEnd'] ?? '' }}" required>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Upload ảnh với preview --}}
            <div class="mb-3">
                <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
                <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" 
                       accept="image/*" onchange="previewImage(this)">
                <div class="form-text">Chọn ảnh JPG, PNG, GIF hoặc WebP. Tối đa 5MB.</div>
            </div>
            
            {{-- Preview ảnh --}}
            <div class="mb-3">
                <div id="imagePreview" class="border rounded p-3 text-center bg-light" style="min-height: 200px;">
                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 mb-0">Preview ảnh ưu đãi</p>
                </div>
            </div>
            
            {{-- Trạng thái --}}
            <div class="mb-3">
                <label for="trangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select" id="trangThai" name="trangThai" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="Sắp diễn ra" {{ ($oldData['trangThai'] ?? '') === 'Sắp diễn ra' ? 'selected' : '' }}>Sắp diễn ra</option>
                    <option value="Đang diễn ra" {{ ($oldData['trangThai'] ?? '') === 'Đang diễn ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="Kết thúc" {{ ($oldData['trangThai'] ?? '') === 'Kết thúc' ? 'selected' : '' }}>Kết thúc</option>
                </select>
                <div class="form-text">
                    <i class="bi bi-info-circle"></i> Trạng thái sẽ được tự động cập nhật theo thời gian
                </div>
            </div>
        </div>
        
        {{-- Submit buttons --}}
        <div class="col-12">
            <hr class="my-4">
            <div class="d-flex gap-2 justify-content-end">
                <a href="/quan-ly-uu-dai" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm ưu đãi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/static/js/admin/ThemUuDai.js"></script>
@endsection
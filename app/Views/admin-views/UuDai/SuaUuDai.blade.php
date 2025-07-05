{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\UuDai\SuaUuDai.blade.php --}}

@extends('layouts.admin.master')

@section('title', 'Sửa ưu đãi')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sửa ưu đãi: {{ $uuDai['ud_tenuudai'] ?? 'N/A' }}</h1>
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
    
    @if(!$uuDai)
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> Không tìm thấy ưu đãi để chỉnh sửa.
        </div>
    @else
    <form action="/sua-uu-dai?id={{ $uuDai['ud_mauudai'] }}" method="POST" enctype="multipart/form-data" class="row g-3">
        <input type="hidden" name="id" value="{{ $uuDai['ud_mauudai'] }}">
        
        <div class="col-md-8">
            {{-- Mã ưu đãi (readonly) --}}
            <div class="mb-3">
                <label for="maUuDai" class="form-label">Mã ưu đãi</label>
                <input type="text" class="form-control" id="maUuDai" value="{{ $uuDai['ud_mauudai'] }}" readonly>
            </div>
            
            {{-- Tên ưu đãi --}}
            <div class="mb-3">
                <label for="tenUuDai" class="form-label">Tên ưu đãi <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="tenUuDai" name="tenUuDai" 
                       value="{{ $oldData['tenUuDai'] ?? $uuDai['ud_tenuudai'] ?? '' }}" required>
            </div>
            
            {{-- Loại ưu đãi --}}
            <div class="mb-3">
                <label for="loaiUuDai" class="form-label">Loại ưu đãi <span class="text-danger">*</span></label>
                <select class="form-select" id="loaiUuDai" name="loaiUuDai" required>
                    <option value="">Chọn loại ưu đãi</option>
                    <option value="COMBO" {{ ($oldData['loaiUuDai'] ?? $uuDai['ud_loaiuudai'] ?? '') === 'COMBO' ? 'selected' : '' }}>COMBO</option>
                    <option value="GIẢM GIÁ" {{ ($oldData['loaiUuDai'] ?? $uuDai['ud_loaiuudai'] ?? '') === 'GIẢM GIÁ' ? 'selected' : '' }}>GIẢM GIÁ</option>
                    <option value="SINH NHẬT" {{ ($oldData['loaiUuDai'] ?? $uuDai['ud_loaiuudai'] ?? '') === 'SINH NHẬT' ? 'selected' : '' }}>SINH NHẬT</option>
                    <option value="SỚM" {{ ($oldData['loaiUuDai'] ?? $uuDai['ud_loaiuudai'] ?? '') === 'SỚM' ? 'selected' : '' }}>SỚM</option>
                    <option value="NGÂN HÀNG" {{ ($oldData['loaiUuDai'] ?? $uuDai['ud_loaiuudai'] ?? '') === 'NGÂN HÀNG' ? 'selected' : '' }}>NGÂN HÀNG</option>
                </select>
            </div>
            
            {{-- Nội dung chi tiết --}}
            <div class="mb-3">
                <label for="noiDungChiTiet" class="form-label">Nội dung chi tiết <span class="text-danger">*</span></label>
                <textarea class="form-control" id="noiDungChiTiet" name="noiDungChiTiet" rows="4" 
                          placeholder="Mô tả chi tiết về ưu đãi..." required>{{ $oldData['noiDungChiTiet'] ?? $uuDai['ud_noidung'] ?? '' }}</textarea>
            </div>
            
            {{-- Thời gian --}}
            <div class="row">
                <div class="col-md-6">
                    <label for="dateUuDai" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dateUuDai" name="dateUuDai" 
                           value="{{ $oldData['dateUuDai'] ?? $uuDai['ud_thoigianbatdau'] ?? '' }}" required>
                </div>
                <div class="col-md-6">
                    <label for="dateUuDaiEnd" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="dateUuDaiEnd" name="dateUuDaiEnd" 
                           value="{{ $oldData['dateUuDaiEnd'] ?? $uuDai['ud_thoigianketthuc'] ?? '' }}" required>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            {{-- Upload ảnh với preview --}}
            <div class="mb-3">
                <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
                <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" 
                       accept="image/*" onchange="previewImage(this)">
                <div class="form-text">Chọn ảnh mới để thay thế (JPG, PNG, GIF, WebP. Tối đa 5MB)</div>
            </div>
            
            {{-- Preview ảnh --}}
            <div class="mb-3">
                <div id="imagePreview" class="border rounded p-3 text-center bg-light" style="min-height: 200px;">
                    @if(isset($uuDai['ud_anhuudai']) && $uuDai['ud_anhuudai'])
                        <img src="{{ $uuDai['ud_anhuudai'] }}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px; object-fit: cover;"
                             alt="Current image"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display: none;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2 mb-0">Ảnh hiện tại không tải được</p>
                        </div>
                        <p class="text-muted mt-2 mb-0 small">
                            <i class="bi bi-check-circle text-success"></i> Ảnh hiện tại
                        </p>
                    @else
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2 mb-0">Chưa có ảnh</p>
                    @endif
                </div>
            </div>
            
            {{-- Trạng thái --}}
            <div class="mb-3">
                <label for="trangThai" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                <select class="form-select" id="trangThai" name="trangThai" required>
                    <option value="">Chọn trạng thái</option>
                    <option value="Sắp diễn ra" {{ ($oldData['trangThai'] ?? $uuDai['ud_trangthai'] ?? '') === 'Sắp diễn ra' ? 'selected' : '' }}>Sắp diễn ra</option>
                    <option value="Đang diễn ra" {{ ($oldData['trangThai'] ?? $uuDai['ud_trangthai'] ?? '') === 'Đang diễn ra' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="Kết thúc" {{ ($oldData['trangThai'] ?? $uuDai['ud_trangthai'] ?? '') === 'Kết thúc' ? 'selected' : '' }}>Kết thúc</option>
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
                    <i class="bi bi-check-circle"></i> Cập nhật ưu đãi
                </button>
            </div>
        </div>
    </form>
    @endif
</div>
@endsection

@section('page-js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Preview ảnh trước khi upload
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.innerHTML = `
                        <img src="${e.target.result}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px; object-fit: cover;"
                             alt="Preview">
                        <p class="text-muted mt-2 mb-0 small">
                            <i class="bi bi-check-circle text-success"></i> Ảnh mới được chọn: ${input.files[0].name}
                        </p>
                    `;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        // Validate dates
        document.getElementById('dateUuDai').addEventListener('change', function() {
            const startDate = this.value;
            const endDateInput = document.getElementById('dateUuDaiEnd');
            
            // Set minimum end date
            endDateInput.min = startDate;
            
            // Clear end date if it's before start date
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = '';
            }
        });
        
        // Auto-update status based on dates
        function updateStatus() {
            const startDate = document.getElementById('dateUuDai').value;
            const endDate = document.getElementById('dateUuDaiEnd').value;
            const statusSelect = document.getElementById('trangThai');
            const today = new Date().toISOString().split('T')[0];
            
            if (startDate && endDate) {
                if (today < startDate) {
                    statusSelect.value = 'Sắp diễn ra';
                } else if (today > endDate) {
                    statusSelect.value = 'Kết thúc';
                } else {
                    statusSelect.value = 'Đang diễn ra';
                }
            }
        }
        
        document.getElementById('dateUuDai').addEventListener('change', updateStatus);
        document.getElementById('dateUuDaiEnd').addEventListener('change', updateStatus);
    </script>
@endsection
@extends('layouts.admin.master')

@section('title', 'Thêm lịch chiếu')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h2>Thêm lịch chiếu mới</h2>
    <hr>

    {{-- Thông báo --}}
    @if(isset($_SESSION['error_message']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @php unset($_SESSION['error_message']); @endphp
    @endif

    <form method="POST" action="/them-lich-chieu">
        <input type="hidden" name="csrf_token" value="{{ $_SESSION['csrf_token'] ?? '' }}">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="p_maphim" class="form-label">Chọn phim <span class="text-danger">*</span></label>
                <select class="form-select" id="p_maphim" name="p_maphim" required>
                    <option value="">-- Chọn phim --</option>
                    @foreach($phimList ?? [] as $phim)
                        <option value="{{ $phim['p_maphim'] }}" 
                                {{ ($_SESSION['old_input']['p_maphim'] ?? '') === $phim['p_maphim'] ? 'selected' : '' }}>
                            {{ $phim['p_tenphim'] }} ({{ $phim['p_trangthai'] }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="col-md-6 mb-3">
                <label for="pc_maphongchieu" class="form-label">Chọn phòng chiếu <span class="text-danger">*</span></label>
                <select class="form-select" id="pc_maphongchieu" name="pc_maphongchieu" required>
                    <option value="">-- Chọn phòng chiếu --</option>
                    @foreach($phongChieuList ?? [] as $phongChieu)
                        <option value="{{ $phongChieu['pc_maphongchieu'] }}" 
                                {{ ($_SESSION['old_input']['pc_maphongchieu'] ?? '') === $phongChieu['pc_maphongchieu'] ? 'selected' : '' }}>
                            {{ $phongChieu['pc_tenphong'] }} ({{ $phongChieu['pc_loaiphong'] }})
                        </option>
                    @endforeach
                </select>
                @if(empty($phongChieuList))
                    <div class="form-text text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                        Chưa có phòng chiếu nào. <a href="/quan-ly-phong-ghe">Tạo phòng chiếu</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="lc_ngaychieu" class="form-label">Ngày chiếu <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="lc_ngaychieu" name="lc_ngaychieu" 
                       value="{{ $_SESSION['old_input']['lc_ngaychieu'] ?? '' }}"
                       min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="lc_giobatdau" class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="lc_giobatdau" name="lc_giobatdau" 
                       value="{{ $_SESSION['old_input']['lc_giobatdau'] ?? '' }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="lc_trangthai" class="form-label">Trạng thái</label>
            <select class="form-select" id="lc_trangthai" name="lc_trangthai" required>
                <option value="Sắp chiếu" {{ ($_SESSION['old_input']['lc_trangthai'] ?? 'Sắp chiếu') === 'Sắp chiếu' ? 'selected' : '' }}>Sắp chiếu</option>
                <option value="Đang chiếu" {{ ($_SESSION['old_input']['lc_trangthai'] ?? '') === 'Đang chiếu' ? 'selected' : '' }}>Đang chiếu</option>
                <option value="Hủy" {{ ($_SESSION['old_input']['lc_trangthai'] ?? '') === 'Hủy' ? 'selected' : '' }}>Hủy</option>
            </select>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Lưu ý:</strong>
            <ul class="mb-0 mt-2">
                <li>Ngày chiếu không được là ngày trong quá khứ</li>
                <li>Hệ thống sẽ kiểm tra trùng lịch với các suất chiếu khác trong cùng phòng</li>
                <li>Khoảng cách tối thiểu giữa các suất chiếu là 30 phút</li>
                <li>Trạng thái sẽ được cập nhật tự động theo thời gian</li>
            </ul>
        </div>

        <!-- Debug composite key -->
        <div id="debug-composite" class="alert alert-secondary" style="display: none;">
            <strong>Mã lịch chiếu sẽ tạo:</strong> <span id="composite-preview"></span>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Thêm lịch chiếu
            </button>
            <a href="/quan-ly-lich-chieu" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>

{{-- Clear old input after use --}}
@php 
if (isset($_SESSION['old_input'])) {
    unset($_SESSION['old_input']);
}
@endphp
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const phimSelect = document.getElementById('p_maphim');
    const phongSelect = document.getElementById('pc_maphongchieu');
    const ngayInput = document.getElementById('lc_ngaychieu');
    const gioInput = document.getElementById('lc_giobatdau');
    
    function generateCompositeKey() {
        const maphim = phimSelect.value;
        const maphong = phongSelect.value;
        const ngay = ngayInput.value;
        const gio = gioInput.value;
        
        if (!maphim || !maphong || !ngay || !gio) {
            return '';
        }
        
        const phimNumber = maphim.substring(1); // P001 -> 001
        const phongNumber = maphong.substring(2); // PC001 -> 001
        const dateFormat = ngay.replace(/-/g, ''); // 2025-07-06 -> 20250706
        const timeFormat = gio.replace(':', ''); // 09:20 -> 0920
        
        const compositeKey = `LC${phimNumber}${phongNumber}${dateFormat}${timeFormat}`;
        return compositeKey;
    }
    
    // Tạo hidden input
    const form = document.querySelector('form');
    let hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'lc_malichchieu_composite';
    hiddenInput.id = 'lc_malichchieu_composite';
    form.appendChild(hiddenInput);
    
    function updateCompositeKey() {
        const compositeKey = generateCompositeKey();
        hiddenInput.value = compositeKey;
        
        // Show preview
        const debugDiv = document.getElementById('debug-composite');
        const previewSpan = document.getElementById('composite-preview');
        
        if (compositeKey) {
            previewSpan.textContent = compositeKey;
            debugDiv.style.display = 'block';
        } else {
            debugDiv.style.display = 'none';
        }
    }
    
    // Listen for changes
    phimSelect.addEventListener('change', updateCompositeKey);
    phongSelect.addEventListener('change', updateCompositeKey);
    ngayInput.addEventListener('change', updateCompositeKey);
    gioInput.addEventListener('input', updateCompositeKey);
    
    // Initial update
    updateCompositeKey();
    
    // Form validation
    form.addEventListener('submit', function(e) {
        updateCompositeKey();
        
        const finalKey = hiddenInput.value;
        if (!finalKey) {
            e.preventDefault();
            alert('Không thể tạo mã lịch chiếu. Vui lòng kiểm tra lại thông tin!');
            return;
        }
        
        // Check if room is selected
        if (!phongSelect.value) {
            e.preventDefault();
            alert('Vui lòng chọn phòng chiếu!');
            phongSelect.focus();
            return;
        }
    });
});
</script>
@endsection
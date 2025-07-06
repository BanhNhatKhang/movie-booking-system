@extends('layouts.admin.master')

@section('title', 'Sửa lịch chiếu')

@section('page-css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
@endsection

@section('content')
<div class="container py-4 content">
    <h2>Sửa lịch chiếu: {{ $lichChieu['lc_malichchieu'] ?? 'N/A' }}</h2>
    <hr>

    {{-- Thông báo --}}
    @if(isset($_SESSION['error_message']))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $_SESSION['error_message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @php unset($_SESSION['error_message']); @endphp
    @endif

    <form method="POST" action="/sua-lich-chieu">
        <input type="hidden" name="csrf_token" value="{{ $csrf_token }}">
        <input type="hidden" name="id" value="{{ $lichChieu['lc_malichchieu'] ?? '' }}">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="p_maphim" class="form-label">Chọn phim <span class="text-danger">*</span></label>
                <select class="form-select" id="p_maphim" name="p_maphim" required>
                    <option value="">-- Chọn phim --</option>
                    @foreach($phimList ?? [] as $phim)
                        <option value="{{ $phim['p_maphim'] }}" 
                                {{ ($lichChieu['p_maphim'] ?? '') === $phim['p_maphim'] ? 'selected' : '' }}>
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
                                {{ ($lichChieu['pc_maphongchieu'] ?? '') === $phongChieu['pc_maphongchieu'] ? 'selected' : '' }}>
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
                       value="{{ $lichChieu['lc_ngaychieu'] ?? '' }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="lc_giobatdau" class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="lc_giobatdau" name="lc_giobatdau" 
                       value="{{ date('H:i', strtotime($lichChieu['lc_giobatdau'] ?? '')) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="lc_trangthai" class="form-label">Trạng thái</label>
            <select class="form-select" id="lc_trangthai" name="lc_trangthai" required>
                <option value="Sắp chiếu" {{ ($lichChieu['lc_trangthai'] ?? '') === 'Sắp chiếu' ? 'selected' : '' }}>Sắp chiếu</option>
                <option value="Đang chiếu" {{ ($lichChieu['lc_trangthai'] ?? '') === 'Đang chiếu' ? 'selected' : '' }}>Đang chiếu</option>
                <option value="Đã chiếu" {{ ($lichChieu['lc_trangthai'] ?? '') === 'Đã chiếu' ? 'selected' : '' }}>Đã chiếu</option>
                <option value="Hủy" {{ ($lichChieu['lc_trangthai'] ?? '') === 'Hủy' ? 'selected' : '' }}>Hủy</option>
            </select>
        </div>

        <!-- Hiển thị thông tin phòng hiện tại nếu có -->
        @if(isset($lichChieu['pc_maphongchieu']))
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                <strong>Phòng chiếu hiện tại:</strong> 
                @foreach($phongChieuList ?? [] as $phongChieu)
                    @if($phongChieu['pc_maphongchieu'] === $lichChieu['pc_maphongchieu'])
                        {{ $phongChieu['pc_tenphong'] }} ({{ $phongChieu['pc_loaiphong'] }})
                        @break
                    @endif
                @endforeach
            </div>
        @endif

        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i>
            <strong>Chú ý:</strong> 
            <ul class="mb-0 mt-2">
                <li>Việc thay đổi lịch chiếu có thể ảnh hưởng đến các vé đã bán</li>
                <li>Thay đổi phòng chiếu sẽ ảnh hưởng đến sơ đồ ghế đã đặt</li>
                <li>Vui lòng cân nhắc kỹ trước khi lưu thay đổi</li>
            </ul>
        </div>
        <div class="text-end">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Lưu thay đổi
            </button>
            <a href="/quan-ly-lich-chieu" class="btn btn-secondary ms-2">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
    </form>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-danger, .alert-success');
        alerts.forEach(alert => {
            if (alert.querySelector('.btn-close')) {
                alert.querySelector('.btn-close').click();
            }
        });
    }, 5000);

    // Confirm before submit
    const form = document.querySelector('form');
    const phongSelect = document.getElementById('pc_maphongchieu');
    const originalPhong = phongSelect.value;

    form.addEventListener('submit', function(e) {
        let confirmMessage = 'Bạn có chắc chắn muốn lưu các thay đổi này không?';
        
        // Special warning if room is changed
        if (phongSelect.value !== originalPhong) {
            confirmMessage = 'CẢNH BÁO: Bạn đang thay đổi phòng chiếu!\n' +
                           'Điều này có thể ảnh hưởng đến các vé đã đặt.\n\n' +
                           'Bạn có chắc chắn muốn tiếp tục không?';
        }
        
        if (!confirm(confirmMessage)) {
            e.preventDefault();
            return;
        }
    });

    // Validate room selection
    form.addEventListener('submit', function(e) {
        if (!phongSelect.value) {
            e.preventDefault();
            alert('Vui lòng chọn phòng chiếu!');
            phongSelect.focus();
            return;
        }
    });
});

function setTime(time) {
    document.getElementById('lc_giobatdau').value = time;
    document.getElementById('time-suggestions').innerHTML = 
        '<span class="text-success"><i class="bi bi-check"></i> Đã chọn ' + time + '</span>';
}

function suggestRoom() {
    const roomSelect = document.getElementById('pc_maphongchieu');
    const currentRoom = roomSelect.value;
    
    // Chuyển sang phòng khác
    for (let option of roomSelect.options) {
        if (option.value && option.value !== currentRoom) {
            roomSelect.value = option.value;
            alert('Đã chuyển sang ' + option.text);
            break;
        }
    }
}
</script>
@endsection
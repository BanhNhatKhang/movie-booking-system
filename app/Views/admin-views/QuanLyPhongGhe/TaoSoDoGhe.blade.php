{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyPhongGhe\TaoSoDoGhe.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Tạo Sơ Đồ Ghế')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="/static/css/admin/QuanLyPhongGhe.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
@endsection

@section('content')
<div class="container py-4 content">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="bi bi-grid-3x3-gap-fill text-primary"></i> Tạo Sơ Đồ Ghế
                    </h1>
                    <p class="text-muted mb-0">Thiết lập phòng chiếu và bố trí ghế</p>
                </div>
                <a href="/quan-ly-phong-ghe" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Success/Error thông báo -->
    @if(isset($_GET['error']))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            @if($_GET['error'] == 'missing_data')
                Vui lòng điền đầy đủ thông tin phòng chiếu và tạo sơ đồ ghế!
            @elseif($_GET['error'] == 'room_exists')
                Mã phòng "{{ $_GET['ma_phong'] ?? '' }}" đã tồn tại. Vui lòng chọn mã khác!
            @elseif($_GET['error'] == 'invalid_seat_data')
                Dữ liệu sơ đồ ghế không hợp lệ. Vui lòng tạo lại!
            @elseif($_GET['error'] == 'create_room_failed')
                Không thể tạo phòng chiếu. Vui lòng thử lại!
            @elseif($_GET['error'] == 'create_seats_failed')
                Không thể tạo ghế. Vui lòng thử lại!
            @else
                Có lỗi xảy ra. Vui lòng thử lại!
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form method="post" action="/luu-so-do-ghe" id="form-tao-so-do">
        <!-- Thông tin phòng chiếu -->
        <div class="form-section">
            <h4 class="text-primary mb-4">
                <i class="bi bi-building"></i> Thông tin Phòng Chiếu
            </h4>
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Mã phòng chiếu <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="ma_phong" id="ma_phong" 
                           placeholder="Ví dụ: PC001" required maxlength="10">
                    <div class="room-code-status" id="room-code-status"></div>
                    <small class="text-muted">Mã phòng duy nhất, 3-10 ký tự (chữ và số)</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Tên phòng <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" name="ten_phong" id="ten_phong" 
                           placeholder="Ví dụ: Phòng VIP 1" required maxlength="50">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        Loại phòng <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="loai_phong" id="loai_phong" required>
                        <option value="">-- Chọn loại phòng --</option>
                        <option value="3D">3D</option>
                        <option value="IMAX">IMAX</option>
                        <option value="4DX">4DX</option>
                        <option value="Ultra 4DX">Ultra 4DX</option>
                        <option value="Standard">Standard</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Cấu hình sơ đồ ghế -->
        <div class="form-section">
            <h4 class="text-primary mb-4">
                <i class="bi bi-grid"></i> Cấu hình Sơ Đồ Ghế
            </h4>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Số dòng ghế <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" name="so_dong" id="so_dong" required>
                        <option value="">-- Chọn số dòng --</option>
                        @for($i = 3; $i <= 15; $i++)
                            <option value="{{ $i }}">
                                {{ $i }} dòng ({{ chr(64+1) }}-{{ chr(64+$i) }})
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Số ghế mỗi dòng <span class="text-danger">*</span>
                    </label>
                    <input type="number" class="form-control" name="so_ghe_moi_dong" id="so_ghe_moi_dong" 
                           min="6" max="20" placeholder="Từ 6 đến 20 ghế" required>
                    <small class="text-muted">Khuyến nghị: 10-16 ghế mỗi dòng</small>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <button type="button" class="btn btn-primary me-2" id="btn-tao-so-do">
                        <i class="bi bi-arrow-clockwise"></i> Tạo sơ đồ ghế
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="btn-reset">
                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                    <div class="loading-spinner ms-2 d-inline-block">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview sơ đồ ghế -->
        <div class="form-section" id="seat-preview-section" style="display: none;">
            <h4 class="text-primary mb-4">
                <i class="bi bi-eye"></i> Thiết Kế Sơ Đồ Ghế
            </h4>
            
            <!-- Toolbar chọn loại ghế -->
            <div class="mb-4 p-3 bg-white rounded border">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <label class="form-label fw-bold mb-0">Chọn loại ghế:</label>
                    </div>
                    <div class="col-auto">
                        <div class="btn-group" role="group">
                            <input type="radio" class="btn-check" name="seat_type_selector" 
                                   id="type_normal" value="normal" checked>
                            <label class="btn btn-outline-primary" for="type_normal">
                                <i class="bi bi-square"></i> Thường
                            </label>
                            
                            <input type="radio" class="btn-check" name="seat_type_selector" 
                                   id="type_vip" value="vip">
                            <label class="btn btn-outline-warning" for="type_vip">
                                <i class="bi bi-star"></i> VIP
                            </label>
                            
                            <input type="radio" class="btn-check" name="seat_type_selector" 
                                   id="type_luxury" value="luxury">
                            <label class="btn btn-outline-info" for="type_luxury">
                                <i class="bi bi-gem"></i> Luxury
                            </label>
                            
                            <input type="radio" class="btn-check" name="seat_type_selector" 
                                   id="type_disabled" value="disabled">
                            <label class="btn btn-outline-secondary" for="type_disabled">
                                <i class="bi bi-x"></i> Vô hiệu
                            </label>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-success btn-sm" id="btn-auto-layout">
                            <i class="bi bi-magic"></i> Tự động bố trí
                        </button>
                    </div>
                </div>
                <small class="d-block mt-2 text-muted">
                    <i class="bi bi-info-circle"></i> 
                    Click vào các ghế để thay đổi loại. Sử dụng "Tự động bố trí" để thiết lập nhanh.
                </small>
            </div>
            
            <!-- Sơ đồ ghế -->
            <div class="text-center mb-4">
                <div class="screen-display">
                    <i class="bi bi-camera-reels"></i> MÀN HÌNH
                </div>
                <div id="seat-map" class="mt-3"></div>
            </div>
            
            <!-- Thống kê ghế -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card text-center bg-primary text-white statistics-card">
                        <div class="card-body py-3">
                            <i class="bi bi-square fs-3 mb-2"></i>
                            <div id="count-normal" class="fs-4 fw-bold">0</div>
                            <small>Ghế Thường</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-warning text-dark statistics-card">
                        <div class="card-body py-3">
                            <i class="bi bi-star fs-3 mb-2"></i>
                            <div id="count-vip" class="fs-4 fw-bold">0</div>
                            <small>Ghế VIP</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-info text-white statistics-card">
                        <div class="card-body py-3">
                            <i class="bi bi-gem fs-3 mb-2"></i>
                            <div id="count-luxury" class="fs-4 fw-bold">0</div>
                            <small>Ghế Luxury</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center bg-success text-white statistics-card">
                        <div class="card-body py-3">
                            <i class="bi bi-check-circle fs-3 mb-2"></i>
                            <div id="count-total" class="fs-4 fw-bold">0</div>
                            <small>Tổng Ghế</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center mt-4" id="action-buttons" style="display: none;">
            <button type="submit" class="btn btn-success btn-lg me-3" id="btn-save">
                <i class="bi bi-check-circle"></i> Lưu Sơ Đồ Ghế
            </button>
            <a href="/quan-ly-phong-ghe" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>
        
        <!-- Hidden input chứa dữ liệu ghế -->
        <input type="hidden" name="seat_data" id="seat_data">
    </form>
</div>
@endsection

@section('page-js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let seatData = {};
    let currentSeatType = 'normal';
    let checkRoomTimeout;
    
    // Kiểm tra phòng trống
    $('#ma_phong').on('input', function() {
        const maPhong = $(this).val().trim().toUpperCase();
        $(this).val(maPhong);
        
        clearTimeout(checkRoomTimeout);
        
        if (maPhong.length >= 3) {
            checkRoomTimeout = setTimeout(() => {
                checkRoomAvailability(maPhong);
            }, 500);
        } else {
            $('#room-code-status').html('');
        }
    });
    
    function checkRoomAvailability(maPhong) {
        $.get('/check-room-available', { ma_phong: maPhong })
        .done(function(data) {
            const statusDiv = $('#room-code-status');
            if (data.available) {
                statusDiv.html('<small class="text-success"><i class="bi bi-check-circle"></i> ' + data.message + '</small>');
            } else {
                statusDiv.html('<small class="text-danger"><i class="bi bi-x-circle"></i> ' + data.message + '</small>');
            }
        })
        .fail(function() {
            $('#room-code-status').html('<small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Không thể kiểm tra</small>');
        });
    }
    
    // Tạo sơ đồ chỗ ngồi
    $('#btn-tao-so-do').click(function() {
        const soDong = parseInt($('#so_dong').val());
        const soGheMoiDong = parseInt($('#so_ghe_moi_dong').val());
        
        if (!soDong || !soGheMoiDong) {
            alert('Vui lòng nhập đầy đủ số dòng và số ghế mỗi dòng!');
            return;
        }
        
        $('.loading-spinner').show();
        
        setTimeout(() => {
            generateSeatMap(soDong, soGheMoiDong);
            $('#seat-preview-section').slideDown();
            $('#action-buttons').slideDown();
            $('.loading-spinner').hide();
        }, 1000);
    });
    
    // Reset form
    $('#btn-reset').click(function() {
        if (confirm('Bạn có chắc muốn reset toàn bộ sơ đồ?')) {
            seatData = {};
            $('#seat-preview-section').slideUp();
            $('#action-buttons').slideUp();
            $('#so_dong, #so_ghe_moi_dong').val('');
        }
    });
    
    // Bố cục tự động
    $('#btn-auto-layout').click(function() {
        if (Object.keys(seatData).length === 0) {
            alert('Vui lòng tạo sơ đồ ghế trước!');
            return;
        }
        
        autoAssignSeatTypes();
        updateSeatDisplay();
        updateStatistics();
    });
    
    // Thay đổi chọn loại ghế
    $('input[name="seat_type_selector"]').change(function() {
        currentSeatType = $(this).val();
    });
    
    // Submit form
    $('#form-tao-so-do').submit(function(e) {
        e.preventDefault();
        
        // validate mã phòng
        const maPhong = $('#ma_phong').val().trim();
        if (!maPhong || maPhong.length < 3) {
            alert('Mã phòng phải có ít nhất 3 ký tự!');
            $('#ma_phong').focus();
            return false;
        }
        
        // Validate dữ liệu ghế
        if (Object.keys(seatData).length === 0) {
            alert('Vui lòng tạo sơ đồ ghế trước!');
            return false;
        }
        
        // đếm chỗ ngồi đang hoạt động
        const activeSeats = Object.values(seatData).filter(seat => seat.type !== 'disabled');
        if (activeSeats.length === 0) {
            alert('Phải có ít nhất 1 ghế hoạt động!');
            return false;
        }
        
        // thiết lập dữ liệu ghế
        $('#seat_data').val(JSON.stringify(seatData));
        
        // hiển thị loading
        $('#btn-save').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...');
        
        // Submit
        this.submit();
    });
    
    function generateSeatMap(soDong, soGheMoiDong) {
        const maPhong = $('#ma_phong').val().trim();
        
        if (!maPhong) {
            alert('Vui lòng nhập mã phòng trước!');
            $('#ma_phong').focus();
            return;
        }
        
        seatData = {};
        let html = '';
        
        for (let i = 0; i < soDong; i++) {
            const rowLetter = String.fromCharCode(65 + i);
            html += '<div class="mb-2">';
            html += '<span class="row-label me-3">' + rowLetter + '</span>';
            
            for (let j = 1; j <= soGheMoiDong; j++) {
                const seatNumber = String(j).padStart(2, '0');
                const seatCode = rowLetter + seatNumber; 
                const fullSeatCode = `${maPhong}_${seatCode}`; 
                const seatType = getSeatTypeByPosition(i, j, soDong, soGheMoiDong);
                
                // tạo dữ liệu ghế 
                seatData[seatCode] = {
                    code: fullSeatCode,       
                    type: seatType,
                    row: rowLetter,
                    column: j,
                    room: maPhong,
                    display: seatCode          
                };
                
                html += `<span class="seat-preview seat-${seatType}" data-seat="${seatCode}" 
                           onclick="changeSeatType('${seatCode}')" 
                           title="Click để thay đổi: ${seatCode}">
                           ${seatCode}
                     </span>`;
            }
            html += '</div>';
        }
        
        $('#seat-map').html(html);
        updateStatistics();
    }
    
    function getSeatTypeByPosition(rowIndex, colIndex, totalRows, totalCols) {
        // tự động gán dựa trên vị trí
        if (rowIndex < Math.floor(totalRows * 0.3)) {
            return 'normal';
        } else if (rowIndex < Math.floor(totalRows * 0.7)) {
            return 'vip';
        } else {
            return 'luxury';
        }
    }
    
    function autoAssignSeatTypes() {
        Object.keys(seatData).forEach(seatCode => {
            const seat = seatData[seatCode];
            const rowIndex = seat.row.charCodeAt(0) - 65;
            const totalRows = Math.max(...Object.values(seatData).map(s => s.row.charCodeAt(0) - 65)) + 1;
            
            seat.type = getSeatTypeByPosition(rowIndex, seat.column, totalRows, 20);
        });
    }
    
    function updateSeatDisplay() {
        Object.keys(seatData).forEach(seatCode => {
            $(`.seat-preview[data-seat="${seatCode}"]`)
                .removeClass('seat-normal seat-vip seat-luxury seat-disabled')
                .addClass('seat-' + seatData[seatCode].type);
        });
    }
    
    window.changeSeatType = function(seatCode) {
        if (seatData[seatCode]) {
            seatData[seatCode].type = currentSeatType;
            $(`.seat-preview[data-seat="${seatCode}"]`)
                .removeClass('seat-normal seat-vip seat-luxury seat-disabled')
                .addClass('seat-' + currentSeatType);
            updateStatistics();
        }
    };
    
    function updateStatistics() {
        const counts = {
            normal: 0,
            vip: 0,
            luxury: 0,
            disabled: 0,
            total: 0
        };
        
        Object.values(seatData).forEach(seat => {
            if (seat.type !== 'disabled') {
                counts.total++;
            }
            counts[seat.type]++;
        });
        
        $('#count-normal').text(counts.normal);
        $('#count-vip').text(counts.vip);
        $('#count-luxury').text(counts.luxury);
        $('#count-total').text(counts.total);
    }
    
    // tự động tạo mã phòng
    $('#ten_phong').on('input', function() {
        const tenPhong = $(this).val();
        if (tenPhong && !$('#ma_phong').val()) {
            const maPhong = 'PC' + String(Math.floor(Math.random() * 900) + 100);
            $('#ma_phong').val(maPhong);
            checkRoomAvailability(maPhong);
        }
    });
});
</script>
@endsection
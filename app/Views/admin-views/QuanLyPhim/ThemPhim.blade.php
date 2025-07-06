{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyPhim\ThemPhim.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Thêm phim mới')

@section('page-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .form-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .section-title {
            color: #495057;
            font-weight: 600;
            margin-bottom: 15px;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
        }
        .required {
            color: #dc3545;
        }
    </style>
@endsection

@section('content')
<div class="container py-4 content" style="background-color: white;">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="mb-1">
                        <i class="bi bi-film text-primary"></i> Thêm Phim Mới
                    </h1>
                    <p class="text-muted mb-0">Thêm phim mới vào hệ thống rạp chiếu</p>
                </div>
                <a href="/quan-ly-phim" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Error/Success Messages -->
    @if(isset($_GET['error']))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            @if($_GET['error'] == 'missing_fields')
                Vui lòng điền đầy đủ thông tin bắt buộc!
            @elseif($_GET['error'] == 'movie_exists')
                Mã phim hoặc tên phim đã tồn tại trong hệ thống!
            @elseif($_GET['error'] == 'upload_failed')
                Upload poster thất bại. Vui lòng thử lại!
            @elseif($_GET['error'] == 'save_failed')
                Lưu phim thất bại. Vui lòng thử lại!
            @else
                Có lỗi xảy ra. Vui lòng thử lại!
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" action="/luu-phim" id="form-them-phim">
        
        <!--  Section 1: Thông tin cơ bản -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-info-circle"></i> Thông tin cơ bản
            </h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Mã phim <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="movie_id" 
                               value="{{ $_POST['movie_id'] ?? '' }}" 
                               placeholder="Ví dụ: P001, P002..." 
                               pattern="[A-Z]\d{3,}" 
                               title="Mã phim phải bắt đầu bằng chữ cái và theo sau là số"
                               required>
                        <small class="text-muted">Mã định danh duy nhất cho phim</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Tên phim <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="name" 
                               value="{{ $_POST['name'] ?? '' }}" 
                               placeholder="Nhập tên phim" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Thể loại <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control" name="genre" 
                               value="{{ $_POST['genre'] ?? '' }}" 
                               placeholder="Ví dụ: Hành động, Kinh dị, Hài kịch" required>
                        <small class="text-muted">Có thể nhập nhiều thể loại, cách nhau bằng dấu phẩy</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Thời lượng (phút) <span class="required">*</span>
                        </label>
                        <input type="number" class="form-control" name="duration" 
                               value="{{ $_POST['duration'] ?? '' }}" 
                               min="1" max="500" placeholder="120" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">
                    Ngày phát hành <span class="required">*</span>
                </label>
                <input type="date" class="form-control" name="release_date" 
                       value="{{ $_POST['release_date'] ?? '' }}" required>
                <small class="text-muted">Ngày phim được phát hành lần đầu</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả phim</label>
                <textarea class="form-control" name="description" rows="4" 
                          placeholder="Nhập mô tả nội dung, cốt truyện của phim...">{{ $_POST['description'] ?? '' }}</textarea>
            </div>
        </div>

        <!--  Section 2: Thông tin sản xuất -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-camera-reels"></i> Thông tin sản xuất
            </h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Đạo diễn</label>
                        <input type="text" class="form-control" name="director" 
                               value="{{ $_POST['director'] ?? '' }}" 
                               placeholder="Tên đạo diễn">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select class="form-select" name="status" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="active" {{ ($_POST['status'] ?? '') == 'active' ? 'selected' : '' }}>
                                Đang chiếu
                            </option>
                            <option value="coming_soon" {{ ($_POST['status'] ?? '') == 'coming_soon' ? 'selected' : '' }}>
                                Sắp chiếu
                            </option>
                            <option value="ended" {{ ($_POST['status'] ?? '') == 'ended' ? 'selected' : '' }}>
                                Đã kết thúc
                            </option>
                            <option value="suspended" {{ ($_POST['status'] ?? '') == 'suspended' ? 'selected' : '' }}>
                                Tạm ngưng
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Diễn viên</label>
                <textarea class="form-control" name="actors" rows="3" 
                          placeholder="Nhập tên các diễn viên chính, cách nhau bằng dấu phẩy hoặc xuống dòng...">{{ $_POST['actors'] ?? '' }}</textarea>
                <small class="text-muted">Ví dụ: Tom Cruise, Angelina Jolie, Will Smith</small>
            </div>
        </div>

        <!-- ✅ Section 3: Media & Links -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-play-circle"></i> Media & Trailer
            </h4>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Trailer (link YouTube)</label>
                <input type="url" class="form-control" name="trailer" 
                       value="{{ $_POST['trailer'] ?? '' }}" 
                       placeholder="https://www.youtube.com/watch?v=...">
                <small class="text-muted">Nhập link YouTube của trailer phim</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Upload Poster</label>
                <input type="file" class="form-control" name="poster" 
                       accept="image/jpeg,image/png,image/jpg,image/webp"
                       id="poster-input">
                <small class="text-muted">
                    Chọn file ảnh poster (JPG, PNG, WEBP). Tối đa 5MB. 
                    Kích thước khuyến nghị: 300x450px
                </small>
                
                <!-- Preview poster -->
                <div id="poster-preview" class="mt-3" style="display: none;">
                    <img id="preview-img" src="" alt="Poster preview" 
                         style="max-width: 200px; max-height: 300px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                </div>
            </div>
        </div>

        <!-- ✅ Action Buttons -->
        <div class="d-flex gap-2 justify-content-end">
            <a href="/quan-ly-phim" class="btn btn-secondary btn-lg">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle"></i> Thêm phim
            </button>
        </div>
    </form>
</div>
@endsection

@section('page-js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // ✅ Poster preview functionality
    const posterInput = document.getElementById('poster-input');
    const previewDiv = document.getElementById('poster-preview');
    const previewImg = document.getElementById('preview-img');
    
    posterInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Chỉ chấp nhận file ảnh định dạng JPG, PNG, WEBP!');
                posterInput.value = '';
                previewDiv.style.display = 'none';
                return;
            }
            
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File ảnh không được vượt quá 5MB!');
                posterInput.value = '';
                previewDiv.style.display = 'none';
                return;
            }
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewDiv.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewDiv.style.display = 'none';
        }
    });
    
    // ✅ Form validation
    document.getElementById('form-them-phim').addEventListener('submit', function(e) {
        // Required fields validation
        const movieId = document.querySelector('input[name="movie_id"]').value.trim();
        const tenPhim = document.querySelector('input[name="name"]').value.trim();
        const theLoai = document.querySelector('input[name="genre"]').value.trim();
        const thoiLuong = parseInt(document.querySelector('input[name="duration"]').value);
        const ngayPhatHanh = document.querySelector('input[name="release_date"]').value;
        const trangThai = document.querySelector('select[name="status"]').value;

        if (!movieId || !tenPhim || !theLoai || !thoiLuong || !ngayPhatHanh || !trangThai) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc (có dấu *)!');
            return;
        }

        // Movie ID format validation
        const movieIdPattern = /^[A-Z]\d{3,}$/;
        if (!movieIdPattern.test(movieId)) {
            e.preventDefault();
            alert('Mã phim phải bắt đầu bằng chữ cái viết hoa và theo sau là ít nhất 3 chữ số!\nVí dụ: P001, M123');
            return;
        }

        // Duration validation
        if (thoiLuong < 1 || thoiLuong > 500) {
            e.preventDefault();
            alert('Thời lượng phim phải từ 1 đến 500 phút!');
            return;
        }

        // Release date validation
        const releaseDate = new Date(ngayPhatHanh);
        const minDate = new Date('1900-01-01');
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() + 5); // Allow 5 years in future

        if (releaseDate < minDate || releaseDate > maxDate) {
            e.preventDefault();
            alert('Ngày phát hành không hợp lệ!');
            return;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...';
    });

    // ✅ Auto-format movie ID
    document.querySelector('input[name="movie_id"]').addEventListener('input', function(e) {
        // Convert to uppercase
        e.target.value = e.target.value.toUpperCase();
    });

    // ✅ Auto-format genre (capitalize first letters)
    document.querySelector('input[name="genre"]').addEventListener('blur', function(e) {
        const genres = e.target.value.split(',').map(genre => {
            return genre.trim().toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
        });
        e.target.value = genres.join(', ');
    });
});
</script>
@endsection
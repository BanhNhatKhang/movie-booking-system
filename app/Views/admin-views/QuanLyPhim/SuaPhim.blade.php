{{-- filepath: d:\Server\ct27501-project-BanhNhatKhang\app\Views\admin-views\QuanLyPhim\SuaPhim.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Sửa phim')

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
                        <i class="bi bi-pencil-square text-warning"></i> Sửa Phim
                    </h1>
                    <p class="text-muted mb-0">Chỉnh sửa thông tin phim: {{ $movie['name'] ?? '' }}</p>
                </div>
                <a href="/quan-ly-phim" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Error Messages -->
    @if(isset($_GET['error']))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i>
            @if($_GET['error'] == 'missing_fields')
                Vui lòng điền đầy đủ thông tin bắt buộc!
            @elseif($_GET['error'] == 'movie_name_exists')
                Tên phim đã tồn tại trong hệ thống!
            @elseif($_GET['error'] == 'upload_failed')
                Upload poster thất bại!
            @elseif($_GET['error'] == 'save_failed')
                Lưu thay đổi thất bại!
            @else
                Có lỗi xảy ra!
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Form -->
    <form method="POST" enctype="multipart/form-data" action="/cap-nhat-phim">
        <input type="hidden" name="id" value="{{ $movie['id'] ?? '' }}">
        
        <!-- ✅ Section 1: Thông tin cơ bản -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-info-circle"></i> Thông tin cơ bản
            </h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mã phim</label>
                        <input type="text" class="form-control" value="{{ $movie['id'] ?? '' }}" disabled>
                        <small class="text-muted">Mã phim không thể thay đổi</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên phim <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" 
                               value="{{ $movie['name'] ?? '' }}" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thể loại <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="genre" 
                               value="{{ $movie['genre'] ?? '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thời lượng (phút) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="duration" 
                               value="{{ $movie['duration'] ?? '' }}" min="1" max="500" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Ngày phát hành <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="release" 
                       value="{{ $movie['release'] ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Mô tả phim</label>
                <textarea class="form-control" name="desc" rows="4">{{ $movie['desc'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- ✅ Section 2: Thông tin sản xuất -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-camera-reels"></i> Thông tin sản xuất
            </h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Đạo diễn</label>
                        <input type="text" class="form-control" name="director" 
                               value="{{ $movie['director'] ?? '' }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="active" {{ ($movie['status'] ?? '') == 'active' ? 'selected' : '' }}>
                                Đang chiếu
                            </option>
                            <option value="coming_soon" {{ ($movie['status'] ?? '') == 'coming_soon' ? 'selected' : '' }}>
                                Sắp chiếu
                            </option>
                            <option value="ended" {{ ($movie['status'] ?? '') == 'ended' ? 'selected' : '' }}>
                                Đã kết thúc
                            </option>
                            <option value="suspended" {{ ($movie['status'] ?? '') == 'suspended' ? 'selected' : '' }}>
                                Tạm ngưng
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Diễn viên</label>
                <textarea class="form-control" name="actors" rows="3">{{ $movie['actors'] ?? '' }}</textarea>
            </div>
        </div>

        <!-- ✅ Section 3: Media -->
        <div class="form-section">
            <h4 class="section-title">
                <i class="bi bi-play-circle"></i> Media & Trailer
            </h4>
            
            <div class="mb-3">
                <label class="form-label fw-bold">Trailer (link YouTube)</label>
                <input type="url" class="form-control" name="trailer" 
                       value="{{ $movie['trailer'] ?? '' }}">
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Poster hiện tại</label><br>
                @if(!empty($movie['poster']))
                    <img src="{{ $movie['poster'] }}" style="max-width:200px; max-height:300px; border-radius: 8px;" 
                         alt="Poster hiện tại">
                @else
                    <div class="alert alert-info">Chưa có poster</div>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Đổi poster mới (tùy chọn)</label>
                <input type="file" class="form-control" name="poster" 
                       accept="image/jpeg,image/png,image/jpg,image/webp">
                <small class="text-muted">Chỉ upload nếu muốn thay đổi poster hiện tại</small>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex gap-2 justify-content-end">
            <a href="/quan-ly-phim" class="btn btn-secondary btn-lg">
                <i class="bi bi-x-circle"></i> Hủy
            </a>
            <button type="submit" class="btn btn-warning btn-lg">
                <i class="bi bi-check-circle"></i> Lưu thay đổi
            </button>
        </div>
    </form>
</div>
@endsection

@section('page-js')
@endsection
{{-- filepath: c:\Servers\test\app\Views\admin-views\TrangChu\SuaUuDaiHome.blade.php --}}
@extends('layouts.admin.master')

@section('title', 'Sửa ưu đãi')

@section('page-css')
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
    <style>
        .image-preview-container {
            position: relative;
            margin-top: 10px;
        }
        
        .image-preview {
            max-width: 300px;
            max-height: 200px;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 10px;
            background-color: #f8f9fa;
            display: none;
        }
        
        .preview-img {
            width: 100%;
            height: auto;
            max-height: 180px;
            object-fit: contain;
            border-radius: 4px;
        }
        
        .current-image {
            max-width: 250px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
        }
        
        .remove-preview {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            font-size: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .remove-preview:hover {
            background: #c82333;
        }
    </style>
@endsection

@section('content')
<div class="container bg-white shadow-sm p-4 rounded mt-4">
    <h4 class="mb-4">Sửa ưu đãi: {{ $uuDai['udtc_tenuudai'] ?? 'N/A' }}</h4>
    
    <!-- thông báo lỗi -->
    @if(isset($_GET['error']))
        @if($_GET['error'] == 'empty_data')
            <div class="alert alert-danger">Vui lòng điền đầy đủ thông tin!</div>
        @elseif($_GET['error'] == 'name_exists')
            <div class="alert alert-danger">Tên ưu đãi đã tồn tại!</div>
        @elseif($_GET['error'] == 'upload_failed')
            <div class="alert alert-danger">Upload ảnh thất bại!</div>
        @elseif($_GET['error'] == 'update_failed')
            <div class="alert alert-danger">Cập nhật ưu đãi thất bại!</div>
        @elseif($_GET['error'] == 'system_error')
            <div class="alert alert-danger">Lỗi hệ thống, vui lòng thử lại!</div>
        @endif
    @endif

    <form method="POST" enctype="multipart/form-data" action="/cap-nhat-uu-dai-home">
        <input type="hidden" name="id" value="{{ $uuDai['udtc_mauudai'] }}">
        
        <div class="mb-3">
            <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
            
            @if($uuDai['udtc_anhuudai'])
                <div class="mb-2">
                    <img src="{{ $uuDai['udtc_anhuudai'] }}" alt="Ảnh hiện tại" class="current-image">
                    <p class="text-muted mt-1 mb-0">Ảnh hiện tại</p>
                </div>
            @endif
            
            <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" 
                   accept="image/jpeg,image/jpg,image/png" />
            <div class="form-text">Để trống nếu không muốn thay đổi ảnh. Định dạng: JPG, PNG. Kích thước tối đa: 5MB</div>
            
            <!-- preview ảnh -->
            <div class="image-preview-container">
                <div class="image-preview" id="imagePreview">
                    <button type="button" class="remove-preview" id="removePreview">
                        <i class="bi bi-x"></i>
                    </button>
                    <img class="preview-img" id="previewImg" src="" alt="Preview">
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('anhUuDai');
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            const removeBtn = document.getElementById('removePreview');
            
            // xử lý chọn file
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                
                if (file) {
                    // Validate file type
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                    if (!allowedTypes.includes(file.type)) {
                        alert('Vui lòng chọn file ảnh định dạng JPG, JPEG hoặc PNG!');
                        fileInput.value = '';
                        return;
                    }
                    
                    // Validate file size (5MB)
                    const maxSize = 5 * 1024 * 1024; 
                    if (file.size > maxSize) {
                        alert('Kích thước file không được vượt quá 5MB!');
                        fileInput.value = '';
                        return;
                    }
                    
                    // tạo FileReader để đọc file
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    
                    reader.readAsDataURL(file);
                } else {
                    // ẩn preview nếu không chọn
                    imagePreview.style.display = 'none';
                }
            });
            
            // xử lý xóa preview
            removeBtn.addEventListener('click', function() {
                fileInput.value = '';
                imagePreview.style.display = 'none';
                previewImg.src = '';
            });
        });
    </script>
@endsection
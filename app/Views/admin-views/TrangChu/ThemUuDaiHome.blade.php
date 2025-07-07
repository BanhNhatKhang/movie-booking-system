@extends('layouts.admin.master')

@section('title', 'Thêm ưu đãi')

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
        
        .file-upload-info {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>
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
            <input type="file" class="form-control" name="anhUuDai" accept="image/*" required>
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
            
            // validate gửi biểu mẫu
            document.querySelector('form').addEventListener('submit', function(e) {
                const tenUuDai = document.getElementById('tenUuDai').value.trim();
                const anhUuDai = fileInput.files[0];
                
                if (!tenUuDai) {
                    e.preventDefault();
                    alert('Vui lòng nhập tên ưu đãi!');
                    document.getElementById('tenUuDai').focus();
                    return;
                }
                
                if (!anhUuDai) {
                    e.preventDefault();
                    alert('Vui lòng chọn ảnh ưu đãi!');
                    fileInput.focus();
                    return;
                }
            });
        });
    </script>
@endsection
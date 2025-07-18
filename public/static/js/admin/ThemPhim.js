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
});
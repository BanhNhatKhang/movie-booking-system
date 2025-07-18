document.addEventListener('DOMContentLoaded', function() {
    const statusOptions = document.querySelectorAll('.status-option');
    const form = document.getElementById('status-form');
    const submitBtn = document.getElementById('submit-btn');

    // Form submission
    form.addEventListener('submit', function(e) {
        const selectedStatus = document.querySelector('input[name="status"]:checked');
        
        if (!selectedStatus) {
            e.preventDefault();
            alert('Vui lòng chọn trạng thái mới!');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang cập nhật...';
        
        
        const statusText = selectedStatus.closest('.status-option').querySelector('strong').textContent;
        const movieName = '{{ addslashes($phim["name"] ?? "Phim không xác định") }}';
        
        if (!confirm('Bạn có chắc muốn đổi trạng thái phim "' + movieName + '" thành "' + statusText + '"?')) {
            e.preventDefault();
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Đổi thành "' + statusText + '"';
            return;
        }
    });

    // Status option click handlers
    statusOptions.forEach(option => {
        option.addEventListener('click', function() {
            statusOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            
            const radio = this.querySelector('input[type="radio"]');
            if (radio) {
                radio.checked = true;
            }
            
            const statusText = this.querySelector('strong').textContent;
            submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Đổi thành "' + statusText + '"';
        });
    });

    // Set initial selected option
    const checkedRadio = document.querySelector('input[name="status"]:checked');
    if (checkedRadio) {
        const selectedOption = checkedRadio.closest('.status-option');
        selectedOption.classList.add('selected');
        
        const statusText = selectedOption.querySelector('strong').textContent;
        submitBtn.innerHTML = '<i class="bi bi-check-circle"></i> Đổi thành "' + statusText + '"';
    }
});

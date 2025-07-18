document.addEventListener("DOMContentLoaded", function () {
  const posterInput = document.querySelector('input[name="poster"]');
  const currentPosterContainer = document.querySelector(
    ".current-poster-container"
  );

  // Tạo container cho preview nếu chưa có
  let previewContainer = document.getElementById("poster-preview-container");
  if (!previewContainer) {
    previewContainer = document.createElement("div");
    previewContainer.id = "poster-preview-container";
    previewContainer.className = "poster-preview-container mt-3";
    previewContainer.style.display = "none";
    posterInput.parentNode.appendChild(previewContainer);
  }

  // Xử lý preview ảnh khi chọn file mới
  posterInput.addEventListener("change", function (e) {
    const file = e.target.files[0];

    if (file) {
      // Validate file type
      const allowedTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "image/webp",
      ];
      if (!allowedTypes.includes(file.type)) {
        alert("Vui lòng chọn file ảnh định dạng JPG, PNG hoặc WEBP!");
        posterInput.value = "";
        hidePreview();
        return;
      }

      // Validate file size (5MB)
      const maxSize = 5 * 1024 * 1024;
      if (file.size > maxSize) {
        alert("Kích thước file không được vượt quá 5MB!");
        posterInput.value = "";
        hidePreview();
        return;
      }

      // Hiển thị preview
      showPreview(file);

      // Ẩn poster cũ và hiển thị thông báo
      hideCurrentPoster();
    } else {
      hidePreview();
      showCurrentPoster();
    }
  });

  function showPreview(file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      previewContainer.innerHTML = `
                <div class="preview-wrapper">
                    <div class="alert alert-info mb-3">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Poster mới được chọn:</strong> Poster cũ sẽ được thay thế khi lưu thay đổi.
                    </div>
                    <div class="new-poster-preview">
                        <label class="form-label fw-bold text-success">
                            <i class="bi bi-image"></i> Poster mới
                        </label>
                        <div class="poster-preview-image">
                            <img src="${e.target.result}" alt="Poster mới" 
                                 style="max-width: 200px; max-height: 300px; border-radius: 8px; border: 3px solid #28a745;">
                            <button type="button" class="btn btn-sm btn-danger remove-preview-btn" 
                                    style="position: absolute; top: 5px; right: 5px; border-radius: 50%; width: 30px; height: 30px;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;

      previewContainer.style.display = "block";

      // Thêm event listener cho nút xóa preview
      const removeBtn = previewContainer.querySelector(".remove-preview-btn");
      removeBtn.addEventListener("click", function () {
        posterInput.value = "";
        hidePreview();
        showCurrentPoster();
      });
    };

    reader.readAsDataURL(file);
  }

  function hidePreview() {
    previewContainer.style.display = "none";
    previewContainer.innerHTML = "";
  }

  function hideCurrentPoster() {
    const currentPosterImg = document.querySelector(".current-poster-img");
    const currentPosterLabel = document.querySelector(".current-poster-label");

    if (currentPosterImg) {
      currentPosterImg.style.opacity = "0.3";
      currentPosterImg.style.filter = "grayscale(100%)";
    }

    // Thêm thông báo sẽ bị thay thế
    let replaceNotice = document.querySelector(".replace-notice");
    if (!replaceNotice) {
      replaceNotice = document.createElement("div");
      replaceNotice.className = "replace-notice alert alert-warning mt-2";
      replaceNotice.innerHTML = `
                <i class="bi bi-exclamation-triangle"></i> 
                Poster này sẽ được thay thế bởi poster mới
            `;

      const currentPosterContainer = currentPosterImg
        ? currentPosterImg.parentNode
        : null;
      if (currentPosterContainer) {
        currentPosterContainer.appendChild(replaceNotice);
      }
    }
  }

  function showCurrentPoster() {
    const currentPosterImg = document.querySelector(".current-poster-img");

    if (currentPosterImg) {
      currentPosterImg.style.opacity = "1";
      currentPosterImg.style.filter = "none";
    }

    // Xóa thông báo thay thế
    const replaceNotice = document.querySelector(".replace-notice");
    if (replaceNotice) {
      replaceNotice.remove();
    }
  }

  // Validation form trước khi submit
  document.querySelector("form").addEventListener("submit", function (e) {
    const name = document.querySelector('input[name="name"]').value.trim();
    const genre = document.querySelector('input[name="genre"]').value.trim();
    const duration = document.querySelector('input[name="duration"]').value;
    const release = document.querySelector('input[name="release"]').value;

    if (!name) {
      e.preventDefault();
      alert("Vui lòng nhập tên phim!");
      document.querySelector('input[name="name"]').focus();
      return;
    }

    if (!genre) {
      e.preventDefault();
      alert("Vui lòng nhập thể loại!");
      document.querySelector('input[name="genre"]').focus();
      return;
    }

    if (!duration || duration < 1) {
      e.preventDefault();
      alert("Vui lòng nhập thời lượng hợp lệ!");
      document.querySelector('input[name="duration"]').focus();
      return;
    }

    if (!release) {
      e.preventDefault();
      alert("Vui lòng chọn ngày phát hành!");
      document.querySelector('input[name="release"]').focus();
      return;
    }

    // Xác nhận nếu có thay đổi poster
    const newPoster = posterInput.files[0];
    if (newPoster) {
      if (
        !confirm(
          "Bạn có chắc chắn muốn thay đổi poster? Poster cũ sẽ bị xóa vĩnh viễn."
        )
      ) {
        e.preventDefault();
        return;
      }
    }
  });

  // Auto dismiss alerts
  setTimeout(function () {
    const alerts = document.querySelectorAll(".alert-dismissible");
    alerts.forEach(function (alert) {
      const closeBtn = alert.querySelector(".btn-close");
      if (closeBtn) {
        closeBtn.click();
      }
    });
  }, 5000);
});

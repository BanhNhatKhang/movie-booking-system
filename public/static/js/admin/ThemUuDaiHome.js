document.addEventListener("DOMContentLoaded", function () {
  const fileInput = document.getElementById("anhUuDai");
  const imagePreview = document.getElementById("imagePreview");
  const previewImg = document.getElementById("previewImg");
  const removeBtn = document.getElementById("removePreview");

  // xử lý chọn file
  fileInput.addEventListener("change", function (e) {
    const file = e.target.files[0];

    if (file) {
      // Validate file type
      const allowedTypes = ["image/jpeg", "image/jpg", "image/png"];
      if (!allowedTypes.includes(file.type)) {
        alert("Vui lòng chọn file ảnh định dạng JPG, JPEG hoặc PNG!");
        fileInput.value = "";
        return;
      }

      // Validate file size (5MB)
      const maxSize = 5 * 1024 * 1024;
      if (file.size > maxSize) {
        alert("Kích thước file không được vượt quá 5MB!");
        fileInput.value = "";
        return;
      }

      // tạo FileReader để đọc file
      const reader = new FileReader();

      reader.onload = function (e) {
        previewImg.src = e.target.result;
        imagePreview.style.display = "block";
      };

      reader.readAsDataURL(file);
    } else {
      // ẩn preview nếu không chọn
      imagePreview.style.display = "none";
    }
  });

  // xử lý xóa preview
  removeBtn.addEventListener("click", function () {
    fileInput.value = "";
    imagePreview.style.display = "none";
    previewImg.src = "";
  });

  // validate gửi biểu mẫu
  document.querySelector("form").addEventListener("submit", function (e) {
    const tenUuDai = document.getElementById("tenUuDai").value.trim();
    const anhUuDai = fileInput.files[0];

    if (!tenUuDai) {
      e.preventDefault();
      alert("Vui lòng nhập tên ưu đãi!");
      document.getElementById("tenUuDai").focus();
      return;
    }

    if (!anhUuDai) {
      e.preventDefault();
      alert("Vui lòng chọn ảnh ưu đãi!");
      fileInput.focus();
      return;
    }
  });
});

// Preview ảnh trước khi upload
function previewImage(input) {
  const preview = document.getElementById("imagePreview");

  if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      preview.innerHTML = `
                        <img src="${e.target.result}" 
                             class="img-fluid rounded" 
                             style="max-height: 300px; object-fit: cover;"
                             alt="Preview">
                        <p class="text-muted mt-2 mb-0 small">
                            <i class="bi bi-check-circle text-success"></i> Ảnh mới được chọn: ${input.files[0].name}
                        </p>
                    `;
    };

    reader.readAsDataURL(input.files[0]);
  }
}

// Validate dates
document.getElementById("dateUuDai").addEventListener("change", function () {
  const startDate = this.value;
  const endDateInput = document.getElementById("dateUuDaiEnd");

  // Set minimum end date
  endDateInput.min = startDate;

  // Clear end date if it's before start date
  if (endDateInput.value && endDateInput.value < startDate) {
    endDateInput.value = "";
  }
});

// Auto-update status based on dates
function updateStatus() {
  const startDate = document.getElementById("dateUuDai").value;
  const endDate = document.getElementById("dateUuDaiEnd").value;
  const statusSelect = document.getElementById("trangThai");
  const today = new Date().toISOString().split("T")[0];

  if (startDate && endDate) {
    if (today < startDate) {
      statusSelect.value = "Sắp diễn ra";
    } else if (today > endDate) {
      statusSelect.value = "Kết thúc";
    } else {
      statusSelect.value = "Đang diễn ra";
    }
  }
}

document.getElementById("dateUuDai").addEventListener("change", updateStatus);
document
  .getElementById("dateUuDaiEnd")
  .addEventListener("change", updateStatus);

document.addEventListener("DOMContentLoaded", function () {
  const phimSelect = document.getElementById("p_maphim");
  const phongSelect = document.getElementById("pc_maphongchieu");
  const ngayInput = document.getElementById("lc_ngaychieu");
  const gioInput = document.getElementById("lc_giobatdau");

  function generateCompositeKey() {
    const maphim = phimSelect.value;
    const maphong = phongSelect.value;
    const ngay = ngayInput.value;
    const gio = gioInput.value;

    if (!maphim || !maphong || !ngay || !gio) {
      return "";
    }

    const phimNumber = maphim.substring(1); // P001 -> 001
    const phongNumber = maphong.substring(2); // PC001 -> 001
    const dateFormat = ngay.replace(/-/g, ""); // 2025-07-06 -> 20250706
    const timeFormat = gio.replace(":", ""); // 09:20 -> 0920

    const compositeKey = `LC${phimNumber}${phongNumber}${dateFormat}${timeFormat}`;
    return compositeKey;
  }

  // Tạo hidden input
  const form = document.querySelector("form");
  let hiddenInput = document.createElement("input");
  hiddenInput.type = "hidden";
  hiddenInput.name = "lc_malichchieu_composite";
  hiddenInput.id = "lc_malichchieu_composite";
  form.appendChild(hiddenInput);

  function updateCompositeKey() {
    const compositeKey = generateCompositeKey();
    hiddenInput.value = compositeKey;

    // Show preview
    const debugDiv = document.getElementById("debug-composite");
    const previewSpan = document.getElementById("composite-preview");

    if (compositeKey) {
      previewSpan.textContent = compositeKey;
      debugDiv.style.display = "block";
    } else {
      debugDiv.style.display = "none";
    }
  }

  // Listen for changes
  phimSelect.addEventListener("change", updateCompositeKey);
  phongSelect.addEventListener("change", updateCompositeKey);
  ngayInput.addEventListener("change", updateCompositeKey);
  gioInput.addEventListener("input", updateCompositeKey);

  // Initial update
  updateCompositeKey();

  // Form validation
  form.addEventListener("submit", function (e) {
    updateCompositeKey();

    const finalKey = hiddenInput.value;
    if (!finalKey) {
      e.preventDefault();
      alert("Không thể tạo mã lịch chiếu. Vui lòng kiểm tra lại thông tin!");
      return;
    }

    // Check if room is selected
    if (!phongSelect.value) {
      e.preventDefault();
      alert("Vui lòng chọn phòng chiếu!");
      phongSelect.focus();
      return;
    }
  });
});

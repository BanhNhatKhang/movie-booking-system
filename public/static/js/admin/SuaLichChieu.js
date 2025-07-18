document.addEventListener("DOMContentLoaded", function () {
  // Auto dismiss alerts
  setTimeout(function () {
    const alerts = document.querySelectorAll(".alert-danger, .alert-success");
    alerts.forEach((alert) => {
      if (alert.querySelector(".btn-close")) {
        alert.querySelector(".btn-close").click();
      }
    });
  }, 5000);

  // Confirm before submit
  const form = document.querySelector("form");
  const phongSelect = document.getElementById("pc_maphongchieu");
  const originalPhong = phongSelect.value;

  form.addEventListener("submit", function (e) {
    let confirmMessage = "Bạn có chắc chắn muốn lưu các thay đổi này không?";

    // Special warning if room is changed
    if (phongSelect.value !== originalPhong) {
      confirmMessage =
        "CẢNH BÁO: Bạn đang thay đổi phòng chiếu!\n" +
        "Điều này có thể ảnh hưởng đến các vé đã đặt.\n\n" +
        "Bạn có chắc chắn muốn tiếp tục không?";
    }

    if (!confirm(confirmMessage)) {
      e.preventDefault();
      return;
    }
  });

  // Validate room selection
  form.addEventListener("submit", function (e) {
    if (!phongSelect.value) {
      e.preventDefault();
      alert("Vui lòng chọn phòng chiếu!");
      phongSelect.focus();
      return;
    }
  });
});

function setTime(time) {
  document.getElementById("lc_giobatdau").value = time;
  document.getElementById("time-suggestions").innerHTML =
    '<span class="text-success"><i class="bi bi-check"></i> Đã chọn ' +
    time +
    "</span>";
}

function suggestRoom() {
  const roomSelect = document.getElementById("pc_maphongchieu");
  const currentRoom = roomSelect.value;

  // Chuyển sang phòng khác
  for (let option of roomSelect.options) {
    if (option.value && option.value !== currentRoom) {
      roomSelect.value = option.value;
      alert("Đã chuyển sang " + option.text);
      break;
    }
  }
}

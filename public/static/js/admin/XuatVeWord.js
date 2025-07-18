// Xử lý sự kiện in vé
document.getElementById("printBtn")?.addEventListener("click", function () {
  // Hiển thị hộp thoại xác nhận
  if (
    confirm("Bạn có chắc chắn muốn in vé này? Vé sẽ được đánh dấu là đã in.")
  ) {
    // Gửi form để cập nhật trạng thái
    document.getElementById("printForm").submit();
  }
});

// THÊM ĐOẠN NÀY - Tự động ẩn thông báo sau 3000ms
setTimeout(function () {
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach(function (alert) {
    const bsAlert = new bootstrap.Alert(alert);
    bsAlert.close();
  });
}, 3000);

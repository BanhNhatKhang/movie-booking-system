// Demo: Lấy thông tin ghế và giá từ localStorage/sessionStorage hoặc truyền qua URL nếu cần
// Ở đây sẽ để cứng như demo, bạn có thể thay bằng dữ liệu thực tế
document.getElementById("btnMomo").onclick = function () {
  // Giả lập thanh toán thành công
  this.disabled = true;
  this.textContent = "Đang xử lý...";
  setTimeout(() => {
    document.getElementById("paySuccess").style.display = "block";
    this.style.display = "none";
  }, 1800);
};

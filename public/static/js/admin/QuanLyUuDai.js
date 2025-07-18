document.addEventListener("DOMContentLoaded", function () {
  // Handle delete confirmation
  const deleteBtns = document.querySelectorAll(".delete-btn");

  deleteBtns.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();

      const name = this.getAttribute("data-name");
      const href = this.getAttribute("href");

      if (confirm(`Bạn có chắc chắn muốn xóa ưu đãi "${name}" không?`)) {
        window.location.href = href;
      }
    });
  });

  // Auto dismiss alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert");
  alerts.forEach((alert) => {
    setTimeout(() => {
      if (alert.querySelector(".btn-close")) {
        alert.querySelector(".btn-close").click();
      }
    }, 5000);
  });
});

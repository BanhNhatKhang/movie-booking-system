document.addEventListener("DOMContentLoaded", function () {
  // Auto-dismiss alerts after 5 seconds
  const alerts = document.querySelectorAll(".alert-dismissible");
  alerts.forEach((alert) => {
    setTimeout(() => {
      const bsAlert = new bootstrap.Alert(alert);
      bsAlert.close();
    }, 5000);
  });

  // Confirm status change
  const statusButtons = document.querySelectorAll(
    'a[href*="doi-trang-thai-phim"]'
  );
  statusButtons.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      const movieName =
        this.closest("tr").querySelector(".text-primary").textContent;
      if (!confirm(`Bạn có chắc muốn đổi trạng thái phim "${movieName}"?`)) {
        e.preventDefault();
      }
    });
  });

  // Enhanced search with enter key
  const searchInput = document.querySelector('input[name="q"]');
  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        this.form.submit();
      }
    });
  }
});

// Lọc ưu đãi theo trạng thái
document.addEventListener("DOMContentLoaded", function () {
  const filterButtons = document.querySelectorAll(".filter-btn");
  const offerItems = document.querySelectorAll(".offer-item");

  // sự kiện click cho các nút lọc
  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const filter = this.getAttribute("data-filter");

      // Cập nhật trạng thái active
      filterButtons.forEach((btn) => btn.classList.remove("active"));
      this.classList.add("active");

      // Lọc các ưu đãi
      offerItems.forEach((item) => {
        const status = item.getAttribute("data-status");

        if (filter === "all") {
          item.style.display = "block";
        } else if (filter === status) {
          item.style.display = "block";
        } else {
          item.style.display = "none";
        }
      });
    });
  });

  // Hiệu ứng chuyển trang
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });
});

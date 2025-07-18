document.addEventListener("DOMContentLoaded", function () {
  // Sử dụng event delegation cho các nút xóa
  document.querySelectorAll(".delete-btn").forEach(function (button) {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      const name = this.getAttribute("data-name");

      document.getElementById("deleteId").value = id;
      document.getElementById("deleteItemName").textContent = name;
      new bootstrap.Modal(document.getElementById("deleteModal")).show();
    });
  });
});

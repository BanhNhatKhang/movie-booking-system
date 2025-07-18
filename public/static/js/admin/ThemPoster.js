document
  .getElementById("pt_anhposter")
  .addEventListener("change", function (e) {
    const [file] = this.files;
    if (file) {
      const preview = document.getElementById("previewImg");
      preview.src = URL.createObjectURL(file);
      preview.style.display = "block";
    }
  });

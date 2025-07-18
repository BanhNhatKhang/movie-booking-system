document
  .getElementById("pt_anhposter")
  .addEventListener("change", function (e) {
    const [file] = this.files;
    if (file) {
      let preview = document.getElementById("previewImg");
      if (!preview) {
        preview = document.createElement("img");
        preview.id = "previewImg";
        preview.style =
          "display:block;max-width:150px;max-height:150px;border:1px solid #ccc;margin-top:10px;";
        this.parentNode.appendChild(preview);
      }
      preview.src = URL.createObjectURL(file);
      preview.style.display = "block";
    }
  });

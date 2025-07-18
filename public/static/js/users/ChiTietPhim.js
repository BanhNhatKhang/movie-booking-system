document.addEventListener("DOMContentLoaded", function () {
  // Trailer modal functionality
  const modal = document.getElementById("trailerModal");
  const iframe = document.getElementById("trailerVideo");
  const closeBtn = document.getElementById("closeTrailerBtn");
  const openBtns = document.querySelectorAll(".openTrailerBtn");

  // Function to convert YouTube URL to embed URL
  function getEmbedUrl(url) {
    if (!url) return "";

    if (url.includes("youtube.com/watch?v=")) {
      const videoId = url.split("v=")[1].split("&")[0];
      return `https://www.youtube.com/embed/${videoId}`;
    }

    if (url.includes("youtu.be/")) {
      const videoId = url.split("youtu.be/")[1].split("?")[0];
      return `https://www.youtube.com/embed/${videoId}`;
    }

    if (url.includes("youtube.com/embed/")) {
      return url;
    }

    return url;
  }

  openBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const originalUrl = btn.getAttribute("data-trailer");
      let embedUrl = getEmbedUrl(originalUrl);

      if (embedUrl.includes("youtube.com/embed/")) {
        embedUrl += "?autoplay=1&rel=0";
      }

      iframe.src = embedUrl;
      modal.style.display = "block";
    });
  });

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none";
    iframe.src = "";
  });

  window.addEventListener("click", (event) => {
    if (event.target == modal) {
      modal.style.display = "none";
      iframe.src = "";
    }
  });
});

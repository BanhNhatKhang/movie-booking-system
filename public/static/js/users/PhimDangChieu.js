document.addEventListener("DOMContentLoaded", function () {
  // Set poster backgrounds
  const movieCards = document.querySelectorAll(".movie-card[data-poster]");
  movieCards.forEach((card) => {
    const posterUrl = card.getAttribute("data-poster");
    if (posterUrl) {
      card.style.backgroundImage = `url('${posterUrl}')`;
    }
  });

  // Existing trailer modal code...
  const modal = document.getElementById("trailerModal");
  const iframe = document.getElementById("trailerVideo");
  const closeBtn = document.getElementById("closeTrailerBtn");
  const openBtns = document.querySelectorAll(".openTrailerBtn");

  // hàm chuyển đổi url ytb
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

      // thêm tham số auto
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

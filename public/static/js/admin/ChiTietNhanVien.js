document.addEventListener("DOMContentLoaded", function () {
  const progressBars = document.querySelectorAll(
    ".progress-dynamic[data-progress]"
  );
  progressBars.forEach((bar) => {
    const progress = bar.getAttribute("data-progress");
    setTimeout(() => {
      bar.style.width = progress + "%";
    }, 100); // Delay nhỏ để có animation
  });
});

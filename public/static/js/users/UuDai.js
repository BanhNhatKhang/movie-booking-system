document.addEventListener("DOMContentLoaded", function () {
  const filterBtns = document.querySelectorAll(".filter-btn");
  const offerCards = document.querySelectorAll(".offer-card");

  console.log("=== FILTER DEBUG ===");
  console.log("Total cards found:", offerCards.length);
  offerCards.forEach((card, index) => {
    console.log(`Card ${index + 1}: data-status="${card.dataset.status}"`);
  });

  filterBtns.forEach((btn) => {
    btn.addEventListener("click", function () {
      const filter = this.dataset.filter;
      console.log(" Filter clicked:", filter);

      filterBtns.forEach((b) => b.classList.remove("active"));
      this.classList.add("active");

      // lọc cards
      let visibleCount = 0;
      offerCards.forEach((card, index) => {
        const cardContainer = card.closest(".col-lg-4, .col-md-6");
        const cardStatus = card.dataset.status;
        let shouldShow = false;

        console.log(
          `Card ${index + 1}: status="${cardStatus}", filter="${filter}"`
        );

        if (filter === "all") {
          shouldShow = true;
        } else {
          switch (filter) {
            case "ongoing":
              shouldShow = cardStatus === "dang-dien-ra";
              break;
            case "upcoming":
              shouldShow = cardStatus === "sap-dien-ra";
              break;
            case "expired":
              shouldShow = cardStatus === "ket-thuc";
              break;
          }
        }

        console.log(`  → Should show: ${shouldShow}`);

        if (shouldShow) {
          cardContainer.style.display = "block";
          visibleCount++;
        } else {
          cardContainer.style.display = "none";
        }
      });

      console.log(
        `Filter "${filter}" completed - Showing ${visibleCount} cards`
      );
    });
  });
});

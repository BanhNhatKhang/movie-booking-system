document.addEventListener("DOMContentLoaded", function () {
  console.log("=== CHON GHE SCRIPT LOADED ===");

  // Lấy dữ liệu từ hidden script tag
  const appDataElement = document.getElementById("app-data");
  if (!appDataElement) {
    console.error("Không tìm thấy app-data element");
    return;
  }

  let appData;
  try {
    appData = JSON.parse(appDataElement.textContent);
    console.log("App data parsed:", appData);
  } catch (e) {
    console.error("Error parsing app data:", e);
    return;
  }

  const giaBanVe = appData.giaBanVe || {};
  const lichChieuId = appData.lichChieuId || "";

  console.log("GiaBanVe:", giaBanVe);
  console.log("LichChieuId:", lichChieuId);

  // Biến lưu trữ ghế đã chọn
  let selectedSeats = [];

  // Tìm tất cả ghế có thể chọn
  const availableSeats = document.querySelectorAll(".seat:not([disabled])");
  console.log("Found available seats:", availableSeats.length);

  // Debug: Kiểm tra ghế đầu tiên
  if (availableSeats.length > 0) {
    const firstSeat = availableSeats[0];
    console.log("First seat element:", firstSeat);
    console.log("First seat data:", {
      seat: firstSeat.getAttribute("data-seat"),
      display: firstSeat.getAttribute("data-display"),
      type: firstSeat.getAttribute("data-type"),
      price: firstSeat.getAttribute("data-price"),
    });
  }

  // Xử lý click chọn ghế
  availableSeats.forEach(function (btn, index) {
    console.log(`Setting up seat ${index}:`, btn.getAttribute("data-seat"));

    btn.addEventListener("click", function (e) {
      console.log("=== SEAT CLICKED ===");
      console.log("Event:", e);
      console.log("Target:", e.target);
      console.log("Seat clicked:", this.getAttribute("data-seat"));

      const seatCode = this.getAttribute("data-seat");
      const displayCode = this.getAttribute("data-display");
      const seatType = this.getAttribute("data-type");
      const seatPrice = parseInt(this.getAttribute("data-price")) || 0;

      console.log("Seat data:", {
        code: seatCode,
        display: displayCode,
        type: seatType,
        price: seatPrice,
      });

      if (this.classList.contains("selected")) {
        console.log("Deselecting seat:", seatCode);
        // Bỏ chọn ghế
        this.classList.remove("selected");
        selectedSeats = selectedSeats.filter((seat) => seat.code !== seatCode);
      } else {
        console.log("Selecting seat:", seatCode);
        // Chọn ghế (giới hạn tối đa 8 ghế)
        if (selectedSeats.length >= 8) {
          alert("Bạn chỉ có thể chọn tối đa 8 ghế trong một lần đặt!");
          return;
        }

        this.classList.add("selected");
        selectedSeats.push({
          code: seatCode,
          display: displayCode,
          type: seatType,
          price: seatPrice,
        });
      }

      console.log("Selected seats after update:", selectedSeats);
      updateSummary();
    });
  });

  // Cập nhật thông tin tóm tắt
  function updateSummary() {
    console.log("Updating summary...");

    const selectedSeatsDiv = document.getElementById("selectedSeats");
    const totalPriceDiv = document.getElementById("totalPrice");
    const nextBtn = document.getElementById("btn-next");

    if (!selectedSeatsDiv || !totalPriceDiv || !nextBtn) {
      console.error("Missing summary elements");
      return;
    }

    if (selectedSeats.length === 0) {
      selectedSeatsDiv.innerHTML =
        '<em class="text-muted">Chưa chọn ghế nào</em>';
      totalPriceDiv.innerHTML = "<strong>Tổng tiền: 0đ</strong>";
      nextBtn.disabled = true;
    } else {
      // Hiển thị danh sách ghế
      const seatList = selectedSeats
        .map(
          (seat) => `<span class="badge bg-primary me-1">${seat.display}</span>`
        )
        .join("");
      selectedSeatsDiv.innerHTML = seatList;

      // Tính tổng tiền
      const total = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
      totalPriceDiv.innerHTML = `<strong>Tổng tiền: ${total.toLocaleString()}đ</strong>`;

      nextBtn.disabled = false;
    }

    console.log("Summary updated");
  }

  // ✅ Global function để thanh toán
  window.goToPayment = function () {
    console.log("=== GO TO PAYMENT CALLED ===");

    if (selectedSeats.length === 0) {
      alert("Vui lòng chọn ít nhất một ghế!");
      return;
    }

    console.log("Selected seats:", selectedSeats);
    console.log("LichChieu ID:", lichChieuId);

    // Kiểm tra dữ liệu
    if (!lichChieuId) {
      alert("Lỗi: Không tìm thấy thông tin lịch chiếu!");
      return;
    }

    // ✅ TẠO FORM POST ĐẾN /thanh-toan
    const form = document.createElement("form");
    form.method = "POST";
    form.action = "/thanh-toan"; // ✅ ĐÚNG ROUTE
    form.style.display = "none";

    const fields = {
      lich_chieu: lichChieuId,
      seats: selectedSeats.map((seat) => seat.code).join(","),
      seat_displays: selectedSeats.map((seat) => seat.display).join(","),
      seat_types: selectedSeats.map((seat) => seat.type).join(","),
      seat_prices: selectedSeats.map((seat) => seat.price).join(","),
      total: selectedSeats.reduce((sum, seat) => sum + seat.price, 0),
    };

    Object.keys(fields).forEach((key) => {
      const input = document.createElement("input");
      input.type = "hidden";
      input.name = key;
      input.value = fields[key];
      form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
  };

  // Test click trực tiếp
  console.log("=== TESTING CLICK EVENTS ===");
  setTimeout(() => {
    const testSeat = document.querySelector(".seat:not([disabled])");
    if (testSeat) {
      console.log("Test seat found:", testSeat);
      console.log("Test seat clickable:", !testSeat.disabled);
      console.log("Test seat classes:", testSeat.className);
    } else {
      console.log("No test seat found");
    }
  }, 1000);

  // Khởi tạo
  updateSummary();
  console.log("=== CHON GHE SCRIPT SETUP COMPLETE ===");
});

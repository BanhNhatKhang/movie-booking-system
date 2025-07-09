// Giá ghế theo loại
const seatPrices = {
  normal: 70000,
  vip: 90000,
  luxury: 120000,
  couple: 150000,
};
// Ghế đã bán (không chọn được)
const soldSeats = ["E06", "E08", "F06", "F08"];
// Ghế đang chọn (demo)
let selectedSeats = ["G09", "G10"];
// Cập nhật giao diện khi chọn ghế
document.querySelectorAll(".seat").forEach((btn) => {
  btn.addEventListener("click", function () {
    const seat = this.getAttribute("data-seat");
    const type = this.getAttribute("data-type");
    if (this.classList.contains("sold") || this.classList.contains("aisle"))
      return;
    if (this.classList.contains("selected")) {
      this.classList.remove("selected");
      selectedSeats = selectedSeats.filter((s) => s !== seat);
    } else {
      this.classList.add("selected");
      selectedSeats.push(seat);
    }
    updateSummary();
  });
});
function updateSummary() {
  document.getElementById("selectedSeats").textContent = selectedSeats.length
    ? selectedSeats.join(", ")
    : "Chưa chọn ghế";
  let total = 0;
  selectedSeats.forEach((seat) => {
    let type = "normal";
    if (seat.startsWith("A") || seat.startsWith("B") || seat.startsWith("C"))
      type = "normal";
    else if (
      seat.startsWith("D") ||
      seat.startsWith("E") ||
      seat.startsWith("F") ||
      seat.startsWith("G")
    )
      type = "vip";
    else if (seat.startsWith("H")) type = "luxury";
    total += seatPrices[type] || 0;
  });
  document.getElementById("totalPrice").textContent =
    "Giá vé: " + total.toLocaleString("vi-VN") + " VNĐ";
}
updateSummary();
document.getElementById("btn-next").onclick = function (e) {
  e.preventDefault();

  // Các cặp luxury bên trái (chẵn-lẻ): H02-H03, H04-H05, ...
  const leftLuxuryPairs = [
    ["H02", "H03"],
    ["H04", "H05"],
    ["H06", "H07"],
  ];
  // Các cặp luxury bên phải (lẻ-chẵn): H09-H10, H11-H12, ...
  const rightLuxuryPairs = [
    ["H09", "H10"],
    ["H11", "H12"],
    ["H13", "H14"],
  ];

  // Lọc các ghế luxury được chọn
  const luxurySeats = selectedSeats.filter((seat) => seat.startsWith("H"));

  // Nếu chỉ chọn 1 ghế luxury -> cảnh báo
  if (luxurySeats.length === 1) {
    showCoupleSeatAlert();
    return;
  }

  // Kiểm tra các cặp luxury bên trái
  for (const [a, b] of leftLuxuryPairs) {
    const aSelected = luxurySeats.includes(a);
    const bSelected = luxurySeats.includes(b);
    if ((aSelected && !bSelected) || (!aSelected && bSelected)) {
      showCoupleSeatAlert();
      return;
    }
  }
  // Kiểm tra các cặp luxury bên phải
  for (const [a, b] of rightLuxuryPairs) {
    const aSelected = luxurySeats.includes(a);
    const bSelected = luxurySeats.includes(b);
    if ((aSelected && !bSelected) || (!aSelected && bSelected)) {
      showCoupleSeatAlert();
      return;
    }
  }

  // Nếu hợp lệ, chuyển trang
  const seats = selectedSeats.join(",");
  let total = 0;
  selectedSeats.forEach((seat) => {
    let type = "normal";
    if (seat.startsWith("A") || seat.startsWith("B") || seat.startsWith("C"))
      type = "normal";
    else if (
      seat.startsWith("D") ||
      seat.startsWith("E") ||
      seat.startsWith("F") ||
      seat.startsWith("G")
    )
      type = "vip";
    else if (seat.startsWith("H")) type = "luxury";
    total += seatPrices[type] || 0;
  });
  window.location.href =
    "/static/html/users/ThanhToan/ThanhToan.php?seats=" +
    encodeURIComponent(seats) +
    "&total=" +
    total;
};

// Hàm cảnh báo
function showCoupleSeatAlert() {
  if (document.getElementById("coupleAlert")) return;
  const alert = document.createElement("div");
  alert.id = "coupleAlert";
  alert.style.position = "fixed";
  alert.style.top = "0";
  alert.style.left = "0";
  alert.style.width = "100vw";
  alert.style.height = "100vh";
  alert.style.background = "rgba(0,0,0,0.7)";
  alert.style.zIndex = "9999";
  alert.innerHTML = `
      <div style="max-width:400px;margin:100px auto;background:#23232b;color:#fff;padding:32px 24px;border-radius:12px;box-shadow:0 2px 16px #000;position:relative;text-align:center;">
          <div style="font-size:2.5rem;color:#ffb74d;"><i class="bi bi-exclamation-circle"></i></div>
          <div style="font-size:1.2rem;margin:18px 0 10px 0;font-weight:bold;">Cảnh báo</div>
          <div style="margin-bottom:18px;">VUI LÒNG CHỌN GHẾ ĐÔI LUXURY LIỀN KỀ ĐÚNG QUY ĐỊNH.</div>
          <button id="closeCoupleAlert" class="btn btn-danger px-4">OK</button>
      </div>
  `;
  document.body.appendChild(alert);
  document.getElementById("closeCoupleAlert").onclick = function () {
    alert.remove();
  };
}

// Bỏ hết logic giữ ghế, chỉ giữ logic chọn ghế cơ bản
function selectSeat(seatElement) {
  const seatCode = seatElement.dataset.seatCode;
  const seatType = seatElement.dataset.seatType;

  if (seatElement.classList.contains("selected")) {
    // Bỏ chọn ghế
    seatElement.classList.remove("selected");
    selectedSeats = selectedSeats.filter((seat) => seat !== seatCode);
  } else {
    // Chọn ghế
    seatElement.classList.add("selected");
    selectedSeats.push(seatCode);
  }

  updateSelectedSeatsDisplay();
  updateTotalPrice();
}

// Tiếp tục thanh toán - không cần kiểm tra giữ ghế
function continueToPay() {
  if (selectedSeats.length === 0) {
    alert("Vui lòng chọn ít nhất một ghế!");
    return;
  }

  // Tạo URL thanh toán
  const seatDisplays = selectedSeats.map((seatCode) => {
    const seatElement = document.querySelector(`[data-seat-code="${seatCode}"]`);
    return seatElement ? seatElement.textContent.trim() : seatCode;
  });

  const params = new URLSearchParams({
    lich_chieu: appData.lichChieuId,
    seats: selectedSeats.join(","),
    seat_displays: seatDisplays.join(","),
    total: totalPrice,
  });

  window.location.href = "/thanh-toan?" + params.toString();
}

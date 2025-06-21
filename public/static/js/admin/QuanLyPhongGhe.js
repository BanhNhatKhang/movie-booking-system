// Demo: chọn ghế để thao tác
let selectedSeats = [];
document.querySelectorAll(".seat-demo").forEach((seat) => {
  seat.addEventListener("click", function () {
    this.classList.toggle("seat-outline");
    const code = this.getAttribute("data-seat");
    if (!code) return;
    if (selectedSeats.includes(code)) {
      selectedSeats = selectedSeats.filter((s) => s !== code);
    } else {
      selectedSeats.push(code);
    }
  });
});
// Đổi loại ghế
document.getElementById("btn-vip").onclick = function () {
  selectedSeats.forEach((code) => {
    let el = document.querySelector('.seat-demo[data-seat="' + code + '"]');
    if (el && !el.classList.contains("seat-locked")) {
      el.classList.remove("seat-normal", "seat-luxury");
      el.classList.add("seat-vip");
      el.setAttribute("data-type", "vip");
    }
  });
  clearSelection();
  updateStat();
};
document.getElementById("btn-normal").onclick = function () {
  selectedSeats.forEach((code) => {
    let el = document.querySelector('.seat-demo[data-seat="' + code + '"]');
    if (el && !el.classList.contains("seat-locked")) {
      el.classList.remove("seat-vip", "seat-luxury");
      el.classList.add("seat-normal");
      el.setAttribute("data-type", "normal");
    }
  });
  clearSelection();
  updateStat();
};
document.getElementById("btn-luxury").onclick = function () {
  selectedSeats.forEach((code) => {
    let el = document.querySelector('.seat-demo[data-seat="' + code + '"]');
    if (el && !el.classList.contains("seat-locked")) {
      el.classList.remove("seat-normal", "seat-vip");
      el.classList.add("seat-luxury");
      el.setAttribute("data-type", "luxury");
    }
  });
  clearSelection();
  updateStat();
};
// Khóa/mở khóa ghế (có lưu loại ghế cũ)
document.getElementById("btn-lock").onclick = function () {
  selectedSeats.forEach((code) => {
    let el = document.querySelector('.seat-demo[data-seat="' + code + '"]');
    if (el) {
      el.setAttribute("data-prev-type", el.getAttribute("data-type"));
      el.classList.remove(
        "seat-normal",
        "seat-vip",
        "seat-luxury",
        "seat-booked",
        "seat-selected"
      );
      el.classList.add("seat-locked");
      el.setAttribute("data-status", "locked");
    }
  });
  selectedSeats = [];
  clearSelection();
  updateStat();
};
document.getElementById("btn-unlock").onclick = function () {
  selectedSeats.forEach((code) => {
    let el = document.querySelector('.seat-demo[data-seat="' + code + '"]');
    if (el && el.classList.contains("seat-locked")) {
      let prevType = el.getAttribute("data-prev-type") || "normal";
      el.classList.remove(
        "seat-locked",
        "seat-normal",
        "seat-vip",
        "seat-luxury"
      );
      if (prevType === "vip") {
        el.classList.add("seat-vip");
      } else if (prevType === "luxury") {
        el.classList.add("seat-luxury");
      } else {
        el.classList.add("seat-normal");
      }
      el.setAttribute("data-status", "available");
      el.setAttribute("data-type", prevType);
      el.removeAttribute("data-prev-type");
    }
  });
  selectedSeats = [];
  clearSelection();
  updateStat();
};
function clearSelection() {
  document
    .querySelectorAll(".seat-outline")
    .forEach((el) => el.classList.remove("seat-outline"));
  selectedSeats = [];
}
// Thống kê tỷ lệ lấp đầy
function updateStat() {
  let total = 0,
    booked = 0,
    locked = 0,
    vip = 0,
    normal = 0,
    luxury = 0,
    selected = 0;
  document.querySelectorAll(".seat-demo[data-seat]").forEach((el) => {
    total++;
    if (el.classList.contains("seat-locked")) locked++;
    else if (el.classList.contains("seat-booked")) booked++;
    else if (el.classList.contains("seat-selected")) selected++;
    if (el.classList.contains("seat-vip")) vip++;
    else if (el.classList.contains("seat-normal")) normal++;
    else if (el.classList.contains("seat-luxury")) luxury++;
  });
  let fillRate =
    total - locked > 0 ? ((booked / (total - locked)) * 100).toFixed(1) : 0;
  document.getElementById(
    "stat"
  ).innerHTML = `Tổng ghế: <b>${total}</b> | Đã bán: <b>${booked}</b> | Đang chọn: <b>${selected}</b> | Đang khóa: <b>${locked}</b> | VIP: <b>${vip}</b> | Thường: <b>${normal}</b> | LUXURY: <b>${luxury}</b><br>
            <span class="text-primary">Tỷ lệ lấp đầy: <b>${fillRate}%</b></span>`;
}
updateStat();

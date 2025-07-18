$(document).ready(function () {
  let seatData = {};
  let currentSeatType = "normal";
  let checkRoomTimeout;

  // Kiểm tra phòng trống
  $("#ma_phong").on("input", function () {
    const maPhong = $(this).val().trim().toUpperCase();
    $(this).val(maPhong);

    clearTimeout(checkRoomTimeout);

    if (maPhong.length >= 3) {
      checkRoomTimeout = setTimeout(() => {
        checkRoomAvailability(maPhong);
      }, 500);
    } else {
      $("#room-code-status").html("");
    }
  });

  function checkRoomAvailability(maPhong) {
    $.get("/check-room-available", { ma_phong: maPhong })
      .done(function (data) {
        const statusDiv = $("#room-code-status");
        if (data.available) {
          statusDiv.html(
            '<small class="text-success"><i class="bi bi-check-circle"></i> ' +
              data.message +
              "</small>"
          );
        } else {
          statusDiv.html(
            '<small class="text-danger"><i class="bi bi-x-circle"></i> ' +
              data.message +
              "</small>"
          );
        }
      })
      .fail(function () {
        $("#room-code-status").html(
          '<small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Không thể kiểm tra</small>'
        );
      });
  }

  // Tạo sơ đồ chỗ ngồi
  $("#btn-tao-so-do").click(function () {
    const soDong = parseInt($("#so_dong").val());
    const soGheMoiDong = parseInt($("#so_ghe_moi_dong").val());

    if (!soDong || !soGheMoiDong) {
      alert("Vui lòng nhập đầy đủ số dòng và số ghế mỗi dòng!");
      return;
    }

    $(".loading-spinner").show();

    setTimeout(() => {
      generateSeatMap(soDong, soGheMoiDong);
      $("#seat-preview-section").slideDown();
      $("#action-buttons").slideDown();
      $(".loading-spinner").hide();
    }, 1000);
  });

  // Reset form
  $("#btn-reset").click(function () {
    if (confirm("Bạn có chắc muốn reset toàn bộ sơ đồ?")) {
      seatData = {};
      $("#seat-preview-section").slideUp();
      $("#action-buttons").slideUp();
      $("#so_dong, #so_ghe_moi_dong").val("");
    }
  });

  // Bố cục tự động
  $("#btn-auto-layout").click(function () {
    if (Object.keys(seatData).length === 0) {
      alert("Vui lòng tạo sơ đồ ghế trước!");
      return;
    }

    autoAssignSeatTypes();
    updateSeatDisplay();
    updateStatistics();
  });

  // Thay đổi chọn loại ghế
  $('input[name="seat_type_selector"]').change(function () {
    currentSeatType = $(this).val();
  });

  // Submit form
  $("#form-tao-so-do").submit(function (e) {
    e.preventDefault();

    // validate mã phòng
    const maPhong = $("#ma_phong").val().trim();
    if (!maPhong || maPhong.length < 3) {
      alert("Mã phòng phải có ít nhất 3 ký tự!");
      $("#ma_phong").focus();
      return false;
    }

    // Validate dữ liệu ghế
    if (Object.keys(seatData).length === 0) {
      alert("Vui lòng tạo sơ đồ ghế trước!");
      return false;
    }

    // đếm chỗ ngồi đang hoạt động
    const activeSeats = Object.values(seatData).filter(
      (seat) => seat.type !== "disabled"
    );
    if (activeSeats.length === 0) {
      alert("Phải có ít nhất 1 ghế hoạt động!");
      return false;
    }

    // thiết lập dữ liệu ghế
    $("#seat_data").val(JSON.stringify(seatData));

    // hiển thị loading
    $("#btn-save")
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...'
      );

    // Submit
    this.submit();
  });

  function generateSeatMap(soDong, soGheMoiDong) {
    const maPhong = $("#ma_phong").val().trim();

    if (!maPhong) {
      alert("Vui lòng nhập mã phòng trước!");
      $("#ma_phong").focus();
      return;
    }

    seatData = {};
    let html = "";

    for (let i = 0; i < soDong; i++) {
      const rowLetter = String.fromCharCode(65 + i);
      html += '<div class="mb-2">';
      html += '<span class="row-label me-3">' + rowLetter + "</span>";

      for (let j = 1; j <= soGheMoiDong; j++) {
        const seatNumber = String(j).padStart(2, "0");
        const seatCode = rowLetter + seatNumber;
        const fullSeatCode = `${maPhong}_${seatCode}`;
        const seatType = getSeatTypeByPosition(i, j, soDong, soGheMoiDong);

        // ✅ CHỈ LƯU MỘT MÃ DUY NHẤT:
        seatData[fullSeatCode] = {
          // Key: "PC001_J01"
          code: fullSeatCode, // Code: "PC001_J01"
          type: seatType,
          row: rowLetter,
          column: j,
          room: maPhong,
          display: seatCode, // Display: "J01"
        };

        html += `<span class="seat-preview seat-${seatType}" data-seat="${fullSeatCode}" 
                           onclick="changeSeatType('${fullSeatCode}')" 
                           title="Click để thay đổi: ${seatCode}">
                           ${seatCode}
                     </span>`;
      }
      html += "</div>";
    }

    $("#seat-map").html(html);
    updateStatistics();
  }

  function getSeatTypeByPosition(rowIndex, colIndex, totalRows, totalCols) {
    // tự động gán dựa trên vị trí
    if (rowIndex < Math.floor(totalRows * 0.3)) {
      return "normal";
    } else if (rowIndex < Math.floor(totalRows * 0.7)) {
      return "vip";
    } else {
      return "luxury";
    }
  }

  function autoAssignSeatTypes() {
    Object.keys(seatData).forEach((seatCode) => {
      const seat = seatData[seatCode];
      const rowIndex = seat.row.charCodeAt(0) - 65;
      const totalRows =
        Math.max(
          ...Object.values(seatData).map((s) => s.row.charCodeAt(0) - 65)
        ) + 1;

      seat.type = getSeatTypeByPosition(rowIndex, seat.column, totalRows, 20);
    });
  }

  function updateSeatDisplay() {
    Object.keys(seatData).forEach((seatCode) => {
      $(`.seat-preview[data-seat="${seatCode}"]`)
        .removeClass("seat-normal seat-vip seat-luxury seat-disabled")
        .addClass("seat-" + seatData[seatCode].type);
    });
  }

  window.changeSeatType = function (seatCode) {
    if (seatData[seatCode]) {
      seatData[seatCode].type = currentSeatType;
      $(`.seat-preview[data-seat="${seatCode}"]`)
        .removeClass("seat-normal seat-vip seat-luxury seat-disabled")
        .addClass("seat-" + currentSeatType);
      updateStatistics();
    }
  };

  function updateStatistics() {
    const counts = {
      normal: 0,
      vip: 0,
      luxury: 0,
      disabled: 0,
      total: 0,
    };

    Object.values(seatData).forEach((seat) => {
      if (seat.type !== "disabled") {
        counts.total++;
      }
      counts[seat.type]++;
    });

    $("#count-normal").text(counts.normal);
    $("#count-vip").text(counts.vip);
    $("#count-luxury").text(counts.luxury);
    $("#count-total").text(counts.total);
  }

  // tự động tạo mã phòng
  $("#ten_phong").on("input", function () {
    const tenPhong = $(this).val();
    if (tenPhong && !$("#ma_phong").val()) {
      const maPhong = "PC" + String(Math.floor(Math.random() * 900) + 100);
      $("#ma_phong").val(maPhong);
      checkRoomAvailability(maPhong);
    }
  });
});

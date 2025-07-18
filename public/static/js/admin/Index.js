// Lấy dữ liệu từ thẻ script JSON
var revenueLabels = JSON.parse(
  document.getElementById("revenue-labels").textContent
);
var revenueData = JSON.parse(
  document.getElementById("revenue-data").textContent
);
var movieLabels = JSON.parse(
  document.getElementById("movie-labels").textContent
);
var movieData = JSON.parse(document.getElementById("movie-data").textContent);

function exportTableToExcel(tableID, filename = "") {
  var wb = XLSX.utils.table_to_book(document.getElementById(tableID), {
    sheet: "Sheet 1",
  });
  return XLSX.writeFile(wb, filename ? filename + ".xlsx" : "excel-data.xlsx");
}

// Biểu đồ doanh thu theo tháng
new Chart(document.getElementById("revenueChart"), {
  type: "line",
  data: {
    labels: revenueLabels,
    datasets: [
      {
        label: "Doanh thu (triệu)",
        data: revenueData,
        borderColor: "#007bff",
        backgroundColor: "rgba(0,123,255,0.1)",
        tension: 0.3,
        fill: true,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
    },
  },
});

// Biểu đồ tỷ lệ đặt vé theo phim
new Chart(document.getElementById("movieChart"), {
  type: "doughnut",
  data: {
    labels: movieLabels,
    datasets: [
      {
        data: movieData,
        backgroundColor: [
          "#6610f2",
          "#fd7e14",
          "#20c997",
          "#ffc107",
          "#dc3545",
        ],
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: "bottom",
      },
    },
  },
});

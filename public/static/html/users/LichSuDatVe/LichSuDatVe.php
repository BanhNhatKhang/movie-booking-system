<?php
session_start();
if (isset($_SESSION['user'])) {
    include __DIR__ . '/../../../layouts/users/HeaderLogin.php';
} else {
    include __DIR__ . '/../../../layouts/users/Header.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử đặt vé</title>
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css">
    <link rel="stylesheet" href="/static/css/users/ThongTinCaNhan.css">
</head>
<body style="background-color: rgb(40, 40, 40)">
<main>
  <div class="container py-4">
    <div class="card shadow-sm rounded-4 p-4">
      <h4 class="fw-bold mb-4 text-danger">Lịch sử đặt vé</h4>
      <div class="table-responsive">
        <table class="table table-bordered align-middle table-hover mb-0 rounded-4 overflow-hidden">
          <thead class="custom-thead text-white text-center">
            <tr>
              <th>STT</th>
              <th>Ngày đặt</th>
              <th>Tên phim</th>
              <th>Suất chiếu</th>
              <th>Ghế</th>
              <th>Phòng</th>
              <th>Giá tiền</th>
              <th>Điểm tích lũy</th>
              <th>Mã vé</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center">1</td>
              <td>01/06/2025 14:23</td>
              <td>Avengers: Endgame</td>
              <td>19:30 - 15/06/2025</td>
              <td>A5, A6</td>
              <td class="text-center">2</td>
              <td class="text-success fw-semibold">160.000đ</td>
              <td class="text-warning fw-semibold text-center">160</td>
              <td class="text-center">KH123456</td>
            </tr>             
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<?php
    include __DIR__ . '/../../../layouts/users/Footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>
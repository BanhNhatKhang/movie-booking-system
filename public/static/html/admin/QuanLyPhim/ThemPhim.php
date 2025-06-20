<?php
// Xử lý thêm phim (giả lập, thực tế lưu vào DB)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name = $_POST['name'] ?? '';
    $genre = $_POST['genre'] ?? '';
    $duration = $_POST['duration'] ?? '';
    $release = $_POST['release'] ?? '';
    $desc = $_POST['desc'] ?? '';
    $trailer = $_POST['trailer'] ?? '';
    // Xử lý upload poster (giả lập)
    $poster = '/static/imgs/demo_new.jpg';
    // Thực tế: Lưu vào DB, upload file poster
    header('Location: QuanLyPhim.php?msg=add_success');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm phim mới</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5" style="max-width:600px">
    <h3 class="mb-4">Thêm phim mới</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên phim</label>
            <input type="text" class="form-control" name="name" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <input type="text" class="form-control" name="genre" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời lượng (phút)</label>
            <input type="number" class="form-control" name="duration" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày khởi chiếu</label>
            <input type="date" class="form-control" name="release" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="desc" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Trailer (link YouTube)</label>
            <input type="url" class="form-control" name="trailer">
        </div>
        <div class="mb-3">
            <label class="form-label">Poster</label>
            <input type="file" class="form-control" name="poster">
        </div>
        <button class="btn btn-success" type="submit">Thêm phim</button>
        <a href="QuanLyPhim.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
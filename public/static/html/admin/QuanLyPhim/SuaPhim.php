<?php
// Dữ liệu mẫu, thực tế lấy từ DB theo id
$id = $_GET['id'] ?? 1;
$movie = [
    'id'=>$id,
    'name'=>'Thanh Gươm Diệt Quỷ',
    'genre'=>'Hành động, Phiêu lưu',
    'duration'=>120,
    'release'=>'2024-06-28',
    'desc'=>'Một bộ phim hành động hấp dẫn...',
    'trailer'=>'https://youtube.com/demo',
    'poster'=>'/static/imgs/demo1.jpg',
    'status'=>'showing'
];
// Xử lý cập nhật phim (giả lập)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form và cập nhật vào DB
    header('Location: QuanLyPhim.php?msg=edit_success');
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa phim</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5" style="max-width:600px">
    <h3 class="mb-4">Sửa phim</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên phim</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($movie['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thể loại</label>
            <input type="text" class="form-control" name="genre" value="<?= htmlspecialchars($movie['genre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Thời lượng (phút)</label>
            <input type="number" class="form-control" name="duration" value="<?= htmlspecialchars($movie['duration']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ngày khởi chiếu</label>
            <input type="date" class="form-control" name="release" value="<?= htmlspecialchars($movie['release']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="desc" rows="3"><?= htmlspecialchars($movie['desc']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Trailer (link YouTube)</label>
            <input type="url" class="form-control" name="trailer" value="<?= htmlspecialchars($movie['trailer']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Poster hiện tại</label><br>
            <img src="<?= htmlspecialchars($movie['poster']) ?>" style="max-width:120px">
        </div>
        <div class="mb-3">
            <label class="form-label">Đổi poster mới (nếu muốn)</label>
            <input type="file" class="form-control" name="poster">
        </div>
        <button class="btn btn-primary" type="submit">Lưu thay đổi</button>
        <a href="QuanLyPhim.php" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
</body>
</html>
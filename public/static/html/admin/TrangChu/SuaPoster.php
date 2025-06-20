<?php 
    $activePage ='home';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sửa poster</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/public/static/css/admin/LayoutAdmin.css" rel="stylesheet">
</head>

<body>
    <div class="admin-layout">
        <?php include '../../../layouts/admin/Sidebar.php'; ?>

        <div class="main-content">
            <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>

            <main class="container">
                <div class="container bg-white shadow-sm p-4 rounded">
                    <h4 class="mb-4">Sửa poster</h4>
                    <form>
                        <div class="mb-3">
                            <label for="anhPoster" class="form-label">Ảnh poster</label>
                            <input type="file" class="form-control" id="anhPoster" name="anhPoster" required />
                        </div>

                        <div class="mb-3">
                            <label for="lienKetPhim" class="form-label">Liên kết đến phim</label>
                            <select class="form-select" id="lienKetPhim" name="lienKetPhim" required>
                                <option value="">-- Chọn phim liên kết --</option>
                                <option value="1">Spider-Man: No Way Home</option>
                                <option value="2">Inside Out 2</option>
                                <!-- Dữ liệu từ backend -->
                            </select>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Sửa poster</button>
                            <a href="QuanLyTrangChu.php" class="btn btn-secondary ms-2">Hủy</a>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

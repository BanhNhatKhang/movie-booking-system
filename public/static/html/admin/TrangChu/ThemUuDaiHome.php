<?php 
    $activePage ='home';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thêm ưu đãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">
</head>

<body>
    <div class="admin-layout">
        <?php include '../../../layouts/admin/Sidebar.php'; ?>

        <div class="main-content">
            <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>

            <main class="container">
                <div class="container bg-white shadow-sm p-4 rounded">
                    <h4 class="mb-4">Thêm ưu đãi mới</h4>

                    <form>
                        <div class="mb-3">
                            <label for="anhUuDai" class="form-label">Ảnh ưu đãi</label>
                            <input type="file" class="form-control" id="anhUuDai" name="anhUuDai" required />
                        </div>

                        <div class="mb-3">
                            <label for="tenUuDai" class="form-label">Tên ưu đãi</label>
                            <input type="text" class="form-control" id="tenUuDai" name="anhUuDai" required />
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-success">Thêm ưu đãi</button>
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

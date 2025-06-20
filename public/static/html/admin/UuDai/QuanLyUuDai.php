<?php 
    $activePage ='uudai';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Quản lý ưu đãi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/static/css/admin/LayoutAdmin.css" rel="stylesheet">

</head>

<body>
    <div class="admin-layout">
        <?php include '../../../layouts/admin/Sidebar.php'; ?>

        <div class="main-content">
            <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>

            <main>
                <div class="container py-4 content">
                    <h1>Quản lý ưu đãi</h1>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <button class="btn btn-primary">
                            <a href="ThemUuDai.php" class="text-white text-decoration-none"><i class="bi bi-plus-circle"></i> Thêm ưu đãi mới</a>
                        </button>
                        <form class="d-flex gap-2 w-75 justify-content-end flex-wrap">
                        <select class="form-select w-auto" style="min-width:140px;">
                                <option value="">Tên ưu đãi</option>
                                <option>Combo gia đình siêu tiết kiệm</option>
                                <option>Giảm giá vé thứ 3</option>
                            </select>
                            <select class="form-select w-auto" style="min-width:140px;">
                                <option value="">Loại ưu đãi</option>
                                <option>COMBO</option>
                                <option>GIẢM GIÁ</option>
                                <option>SINH NHẬT</option>
                                <option>SỚM</option>
                                <option>NGÂN HÀNG</option>
                            </select>
                            <select class="form-select w-auto" style="min-width:140px;">
                                <option value="">Trạng thái ưu đãi</option>
                                <option>Đang diễn ra</option>
                                <option>Sắp diễn ra</option>
                                <option>Kết thúc</option>
                            </select>
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>
                    <table class="table table-bordered table-hover shadow-sm text-center">
                        <thead class="table-dark">
                            <tr>
                                <th>STT</th>
                                <th>Ảnh ưu đãi</th>
                                <th>Tên ưu đãi</th>
                                <th>Loại ưu đãi</th>
                                <th>Nội dung chi tiết</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <img src="uudai1.jpg" alt="Ưu đãi" width="100">
                                </td>
                                <td>Combo gia đình siêu tiết kiệm</td>
                                <td>COMBO</td>
                                <td class="text-break" style="max-width:220px">Combo 1 bắp lớn + 2 nước lớn chỉ với 99.000đ. Áp dụng cho tất cả các suất chiếu và rạp trên toàn quốc.</td>
                                <td class="text-break" style="max-width:100px;">01/06/2024 - 31/12/2024</td>
                                <td>
                                    <span class="badge bg-success">Đang diễn ra</span>
                                </td>
                                <td>
                                    <a href="SuaUuDai.php" class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="XoaUuDai.php?id=5"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa ưu đãi này không?')"
                                    class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>
                                    <img src="uudai2.jpg" alt="Ưu đãi" width="100">
                                </td>
                                <td>Giảm giá vé thứ 3</td>
                                <td>GIẢM GIÁ</td>
                                <td class="text-break" style="max-width:220px;">Mua 1 tặng 1 cho tất cả các suất chiếu vào thứ 3 hàng tuần. Áp dụng cho tất cả các rạp trên toàn quốc.</td>
                                <td class="text-break" style="max-width:100px;">01/06/2024 - 31/08/2024</td>
                                <td>
                                    <span class="badge bg-warning text-dark">Sắp diễn ra</span>
                                </td>
                                <td>
                                    <a href="SuaUuDai.php" class="btn btn-sm btn-warning" title="Sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="XoaUuDai.php?id=5"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa ưu đãi này không?')"
                                    class="btn btn-sm btn-danger" title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

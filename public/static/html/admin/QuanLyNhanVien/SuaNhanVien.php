<?php
$activePage = 'NhanVien';
$id = $_GET['id'] ?? 0;
$staff = [
    'id' => $id,
    'email' => 'nhanvien'.$id.'@khfcinema.vn',
    'name' => 'Tên mẫu '.$id,
    'phone' => '09'.str_repeat($id, 8)
];
$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    if ($email && $name && $phone) {
        $success = "Cập nhật thông tin thành công!";
        $staff = ['id'=>$id, 'email'=>$email, 'name'=>$name, 'phone'=>$phone];
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa nhân viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/static/css/admin/LayoutAdmin.css">
</head>
<body>
<div class="admin-layout">
    <?php include '../../../layouts/admin/Sidebar.php'; ?>
    <div class="main-content">
        <?php include '../../../layouts/admin/HeaderAdmin.php'; ?>
        <main>
            <div class="container py-4">
                <h2>Sửa nhân viên</h2>
                <?php if($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                    <a href="QuanLyNhanVien.php" class="btn btn-primary">Quay lại danh sách</a>
                <?php else: ?>
                    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
                    <form method="post" class="w-50">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($staff['email']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Tên</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($staff['name']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($staff['phone']) ?>" required>
                        </div>
                        <button class="btn btn-success" type="submit">Lưu thay đổi</button>
                        <a href="QuanLyNhanVien.php" class="btn btn-secondary">Hủy</a>
                    </form>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
</body>
</html>
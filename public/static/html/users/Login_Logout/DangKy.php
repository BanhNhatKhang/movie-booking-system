<?php
session_start();
include __DIR__ . '/../../../includes/db_connect.php';

$error_message = false;
$success_message = false;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $address = $_POST['address'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $cmnd = $_POST['cmnd'] ?? '';
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $phone = $_POST['phone'] ?? '';

    if ($full_name && $dob && $address && $gender && $cmnd && $username && $email && $password && $confirm_password && $phone) {
        if ($password !== $confirm_password) {
            $error_message = 'Mật khẩu nhập lại không khớp!';
        } else {
            // Kiểm tra email hoặc tên đăng nhập đã tồn tại chưa
            $stmt = $pdo->prepare("SELECT 1 FROM nguoi_dung WHERE nd_email = :email OR nd_tendangnhap = :username");
            $stmt->execute([':email' => $email, ':username' => $username]);
            if ($stmt->fetch()) {
                $error_message = 'Email hoặc tên đăng nhập đã tồn tại!';
            } else {
                try {
                    $nd_id = 'ND' . time();
                    $signup = date('Y-m-d');

                    $stmt = $pdo->prepare("INSERT INTO nguoi_dung 
                        (nd_id, nd_hoten, nd_ngaysinh, nd_diachi, nd_gioitinh, nd_sdt, nd_cccd, nd_email, nd_tendangnhap, nd_matkhau, nd_role, nd_dangky)
                        VALUES 
                        (:nd_id, :nd_hoten, :nd_ngaysinh, :nd_diachi, :nd_gioitinh, :nd_sdt, :nd_cccd, :nd_email, :nd_tendangnhap, :nd_matkhau, :nd_role, :nd_dangky)");
                    if ($stmt->execute([
                        ':nd_id' => $nd_id,
                        ':nd_hoten' => $full_name,
                        ':nd_ngaysinh' => $dob,
                        ':nd_diachi' => $address,
                        ':nd_gioitinh' => ($gender == 'Nam') ? true : false,
                        ':nd_sdt' => $phone,
                        ':nd_cccd' => $cmnd,
                        ':nd_email' => $email,
                        ':nd_tendangnhap' => $username,
                        ':nd_matkhau' => password_hash($password, PASSWORD_DEFAULT),
                        ':nd_role' => 'user',
                        ':nd_dangky' => $signup
                    ])) {
                        $_SESSION['user'] = [
                            'id' => $nd_id,
                            'name' => $full_name,
                            'email' => $email,
                            'username' => $username,
                            'phone' => $phone,
                            'gender' => $gender,
                            'signup' => $signup
                        ];
                        header('Location: /index.php');
                        exit();
                    }
                } catch (PDOException $e) {
                    $error_message = 'Lỗi khi đăng ký: ' . $e->getMessage();
                }
            }
        }
    } else {
        $error_message = 'Vui lòng nhập đầy đủ thông tin!';
    }
}

if (isset($_SESSION['user'])) {
    include __DIR__ . '/../../../layouts/users/HeaderLogin.php';
} else {
    include __DIR__ . '/../../../layouts/users/Header.php';
}
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>KHF Cinema - Đăng nhập</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
    />
    <link rel="stylesheet" href="/static/css/users/HeaderFooter.css" />
    <link rel="stylesheet" href="/static/css/users/Login.css" />
  </head>
  <body style="background-color: rgb(40, 40, 40);">
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="auth-container">
                        <div class="container">
                            <div class="auth-card">
                                <div class="auth-header">
                                    <h2 class="auth-title">ĐĂNG KÝ</h2>
                                </div>

                                <div class="auth-body">
                                    <?php if ($error_message): ?>
                                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
                                    <?php endif; ?>
                                    <?php if ($success_message): ?>
                                        <div class="alert alert-success text-center"><?= htmlspecialchars($success_message) ?></div>
                                    <?php endif; ?>
                                    <form method="post">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Họ & tên(*)"
                                            name="full_name"
                                            required
                                        />
                                        </div>
                                        <div class="col-md-6">
                                        <input
                                            type="date"
                                            class="form-control"
                                            placeholder="dd/mm/yyyy"
                                            name="dob"
                                            required
                                        />
                                        </div>
                                    </div>

                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-6">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Địa chỉ(*)"
                                            name="address"
                                            required
                                        />
                                        </div>
                                        <div class="col-md-6 d-flex align-items-center">
                                        <label class="me-3 mb-0 text-white">Giới tính:</label>
                                        <div class="form-check me-3">
                                            <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            id="genderMale"
                                            value="Nam"
                                            checked
                                            />
                                            <label class="form-check-label text-white" for="genderMale"
                                            >Nam</label
                                            >
                                        </div>
                                        <div class="form-check">
                                            <input
                                            class="form-check-input"
                                            type="radio"
                                            name="gender"
                                            id="genderFemale"
                                            value="Nữ"
                                            />
                                            <label
                                            class="form-check-label text-white"
                                            for="genderFemale"
                                            >Nữ</label
                                            >
                                        </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="CCCD(*)"
                                            name="cmnd"
                                            required
                                        />
                                        </div>
                                        <div class="col-md-6">
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Email / Tên đăng nhập"
                                            name="username"
                                            required
                                        />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                        <input
                                            type="email"
                                            class="form-control"
                                            placeholder="Email(*)"
                                            name="email"
                                            required
                                        />
                                        </div>
                                        <div class="col-md-6">
                                        <input
                                            type="password"
                                            class="form-control"
                                            placeholder="Mật khẩu(*)"
                                            name="password"
                                            required
                                        />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                        <input
                                            type="tel"
                                            class="form-control"
                                            placeholder="Điện thoại(*)"
                                            name="phone"
                                            required
                                        />
                                        </div>
                                        <div class="col-md-6">
                                        <input
                                            type="password"
                                            class="form-control"
                                            placeholder="Mật khẩu nhập lại(*)"
                                            name="confirm_password"
                                            required
                                        />
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-danger px-4">
                                        ĐĂNG KÝ
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <p class="text-white">
                                        Đã có tài khoản vui lòng
                                        <a href="#" class="text-danger fw-bold">Đăng nhập!</a>
                                        </p>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php
        include __DIR__ . '/../../../layouts/users/Footer.php';
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

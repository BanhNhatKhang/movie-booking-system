<?php
session_start();
include __DIR__ . '/../../../includes/db_connect.php';

// Xử lý đăng nhập
$error_message = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        // Cho phép đăng nhập bằng email hoặc tên đăng nhập
        $stmt = $pdo->prepare("SELECT * FROM nguoi_dung WHERE nd_email = :username OR nd_tendangnhap = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['nd_matkhau'])) {
            // Đăng nhập thành công, lưu session
            $_SESSION['user'] = [
                'id' => $user['nd_id'],
                'name' => $user['nd_hoten'],
                'email' => $user['nd_email'],
                'username' => $user['nd_tendangnhap'],
                'phone' => $user['nd_sdt'],
                'gender' => $user['nd_gioitinh'],
                'role' => $user['nd_role']
            ];
            if ($user['nd_role'] === 'admin') {
                header('Location: /static/html/admin/Dashboard/index.php');
            } else {
                header('Location: /index.php');
            }
            exit();
        } else {
            $error_message = 'Sai tài khoản hoặc mật khẩu!';
        }
    } else {
        $error_message = 'Vui lòng nhập đầy đủ thông tin!';
    }
}

// Hiển thị header phù hợp
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
                                    <h2 class="auth-title">ĐĂNG NHẬP</h2>
                                </div>
                                <div class="auth-body">
                                    <?php if ($error_message): ?>
                                        <div class="alert alert-danger text-center"><?= htmlspecialchars($error_message) ?></div>
                                    <?php endif; ?>
                                    <form method="post">
                                    <div class="mb-3">
                                        <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Email / Tên đăng nhập"
                                        name="username"
                                        required
                                        />
                                    </div>
                                    <div class="mb-3">
                                        <input
                                        type="password"
                                        class="form-control"
                                        placeholder="Mật khẩu"
                                        name="password"
                                        required
                                        />
                                    </div>
                                    <div class="mb-3 form-check">
                                        <input
                                        type="checkbox"
                                        class="form-check-input"
                                        id="rememberMe"
                                        name="remember"
                                        />
                                        <label class="form-check-label text-white" for="rememberMe">
                                        Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-danger px-4">
                                        ĐĂNG NHẬP
                                        </button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <p class="text-white">
                                        Chưa có tài khoản?
                                        <a href="DangKy.php" class="text-danger fw-bold">Đăng ký!</a>
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

<?php 
    include __DIR__ . '/../../../layouts/users/Header.php';
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
    <link rel="stylesheet" href="/static/css/users/Header-Footer.css" />
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
                                    <form>
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
                                        <a href="#" class="text-danger fw-bold">Đăng ký!</a>
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

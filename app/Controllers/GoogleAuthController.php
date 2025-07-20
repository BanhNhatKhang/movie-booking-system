<?php
namespace App\Controllers;

use App\Core\Database;

class GoogleAuthController
{
    public function loginGoogle()
    {
        require __DIR__ . '/../config/google_oauth.php';

        $GOOGLE_AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
        $params = [
            'response_type' => 'code',
            'state' => $_SESSION['csrf_token'],
            'client_id' => $google_oauth_client_id,
            'redirect_uri' => $google_oauth_redirect_uri,
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent'
        ];
        header('Location: ' . $GOOGLE_AUTH_URL . '?' . http_build_query($params));
        exit;
    }

    public function handleGoogleCallback()
    {
        require __DIR__ . '/../config/google_oauth.php';

        // Hàm giải mã JWT payload
        function decodeJWTPayload($jwt)
        {
            $parts = explode('.', $jwt);
            $payload = $parts[1];
            $payload = str_replace(['-', '_'], ['+', '/'], $payload);
            $payload .= str_repeat('=', 3 - (3 + strlen($payload)) % 4); // padding
            return json_decode(base64_decode($payload), true);
        }

        // Kiểm tra CSRF
        if (!isset($_GET['state']) || $_GET['state'] !== $_SESSION['csrf_token']) {
            $_SESSION['error_message'] = 'Invalid session!';
            header('Location: /dang-nhap');
            exit;
        }

        if (isset($_GET['code']) && !empty($_GET['code'])) {
            // Lấy token từ Google
            $params = [
                'code' => $_GET['code'],
                'client_id' => $google_oauth_client_id,
                'client_secret' => $google_oauth_client_secret,
                'redirect_uri' => $google_oauth_redirect_uri,
                'grant_type' => 'authorization_code'
            ];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            if ($response === false) {
                error_log('cURL error: ' . curl_error($ch));
            }
            curl_close($ch);
            $response = json_decode($response, true);

            if (!isset($response['id_token'])) {
                error_log('Google token response: ' . json_encode($response));
            }

            if (isset($response['id_token']) && !empty($response['id_token'])) {
                $payload = decodeJWTPayload($response['id_token']);
                $google_user_id = $payload['sub'];
                $google_email = $payload['email'];
                $google_is_verified = $payload['email_verified'];
                $google_name = $payload['name'] ?? '';
                $google_picture = $payload['picture'] ?? '';

                // Kết nối DB qua Core\Database
                $db = Database::getInstance()->getConnection();

                // Kiểm tra user đã tồn tại chưa
                $stmt = $db->prepare("SELECT * FROM nguoi_dung WHERE nd_email = :email LIMIT 1");
                $stmt->execute(['email' => $google_email]);
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);

                if (!$user) {
                    // Tạo nd_id tự động
                    $stmt = $db->query("SELECT MAX(CAST(nd_id AS INTEGER)) AS max_id FROM nguoi_dung");
                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    $new_id = strval(($row['max_id'] ?? 0) + 1);

                    $stmt = $db->prepare("INSERT INTO nguoi_dung (
                        nd_id, nd_hoten, nd_email, nd_tendangnhap, nd_matkhau, nd_ngaydangky, nd_trangthai, nd_role
                    ) VALUES (
                        :id, :name, :email, :username, :password, :ngaydangky, :trangthai, :role
                    )");
                    $stmt->execute([
                        'id' => $new_id,
                        'name' => $google_name,
                        'email' => $google_email,
                        'username' => $google_email,
                        'password' => '',
                        'ngaydangky' => date('Y-m-d'),
                        'trangthai' => 'active',
                        'role' => 'user'
                    ]);
                    // Lấy lại user vừa tạo
                    $stmt = $db->prepare("SELECT * FROM nguoi_dung WHERE nd_email = :email LIMIT 1");
                    $stmt->execute(['email' => $google_email]);
                    $user = $stmt->fetch(\PDO::FETCH_ASSOC);
                }

                // Đăng nhập user vào hệ thống
                session_regenerate_id();
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['nd_id'];
                $_SESSION['user_name'] = $user['nd_hoten'];
                $_SESSION['user_role'] = $user['nd_role'];
                $_SESSION['user_email'] = $user['nd_email'];
                $_SESSION['login_type'] = 'google';

                // Kiểm tra thông tin bổ sung
                if (
                    empty($user['nd_ngaysinh']) ||
                    is_null($user['nd_gioitinh']) ||
                    empty($user['nd_sdt']) ||
                    empty($user['nd_cccd'])
                ) {
                    $_SESSION['require_update_info'] = true;
                    header('Location: /cap-nhat-thong-tin');
                    exit;
                } else {
                    unset($_SESSION['require_update_info']);
                    header('Location: /');
                    exit;
                }
            } else {
                $_SESSION['error_message'] = 'Invalid ID token!';
                header('Location: /dang-nhap');
                exit;
            }
        } else {
            $_SESSION['error_message'] = 'No auth code found!';
            header('Location: /dang-nhap');
            exit;
        }
    }

    public function capNhatThongTin()
    {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id'])) {
            header('Location: /dang-nhap');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $nd_ngaysinh = $_POST['nd_ngaysinh'] ?? null;
            $nd_gioitinh = $_POST['nd_gioitinh'] ?? null;
            $nd_sdt      = $_POST['nd_sdt'] ?? null;
            $nd_cccd     = $_POST['nd_cccd'] ?? null;
            $csrf_token  = $_POST['csrf_token'] ?? '';

            // Kiểm tra CSRF
            if (!isset($_SESSION['csrf_token']) || $csrf_token !== $_SESSION['csrf_token']) {
                $_SESSION['error_message'] = 'CSRF token không hợp lệ!';
                header('Location: /cap-nhat-thong-tin');
                exit;
            }

            // Validate dữ liệu
            if (!$nd_ngaysinh || $nd_gioitinh === null || !$nd_sdt || !$nd_cccd) {
                $_SESSION['error_message'] = 'Vui lòng nhập đầy đủ thông tin!';
                header('Location: /cap-nhat-thong-tin');
                exit;
            }

            // Cập nhật vào DB
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("UPDATE nguoi_dung SET nd_ngaysinh = :ngaysinh, nd_gioitinh = :gioitinh, nd_sdt = :sdt, nd_cccd = :cccd WHERE nd_id = :id");
            $stmt->execute([
                'ngaysinh' => $nd_ngaysinh,
                'gioitinh' => $nd_gioitinh,
                'sdt'      => $nd_sdt,
                'cccd'     => $nd_cccd,
                'id'       => $_SESSION['user_id']
            ]);

            // Xóa thông báo lỗi cũ nếu có
            unset($_SESSION['error_message']);

            // Chuyển về trang chủ
            unset($_SESSION['require_update_info']);
            header('Location: /');
            exit;
        } else {
            $blade = new \Jenssegers\Blade\Blade(__DIR__ . '/../Views', __DIR__ . '/../../cache');
            echo $blade->render('users-views.Login.CapNhatThongTin');
            exit;
        }
    }
}
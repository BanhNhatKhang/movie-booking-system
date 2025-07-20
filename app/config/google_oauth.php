<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(16));
}
$client_secret_path = __DIR__ . '/client_secret.json';
$client_secret = json_decode(file_get_contents($client_secret_path), true);

$google_oauth_client_id = $client_secret['web']['client_id'];
$google_oauth_client_secret = $client_secret['web']['client_secret'];
$google_oauth_redirect_uri = $client_secret['web']['redirect_uris'][0];
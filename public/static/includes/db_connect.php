<?php

try {
  $pdo = new PDO('pgsql:host=localhost;dbname=CT275_Project', 'postgres', 'T@McjBZn');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  $error_message = 'Không thể kết nối đến CSDL';
  $reason = $e->getMessage();
  include 'show_error.php';
  exit();
}
?>
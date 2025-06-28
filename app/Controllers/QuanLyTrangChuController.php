<?php

namespace App\Controllers;

use Jenssegers\Blade\Blade;

class QuanLyTrangChuController
{
    public function trangChu()
    {
        $posterModel = new \App\Models\Poster();
        $posters = $posterModel->getAll(); // Lấy tất cả poster từ DB
    
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.QuanLyTrangChu', [
            'posters' => $posters,
            'activePage' => 'home'
        ]);
    }

    public function themPoster()
    {
        $posterModel = new \App\Models\Poster();
        $newId = $posterModel->generateNewId();
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.ThemPoster', [
            'activePage' => 'home',
            'newId' => $newId
        ]);
    }

    public function luuPoster()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $_FILES['anhPoster']; // Đúng tên input trong form
            $imgPath = null;
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $imgPath = '/static/upload/posters/' . uniqid('poster_') . '.' . $ext;
                move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imgPath);
            }
            $posterModel = new \App\Models\Poster();
            $posterModel->create([
                'pt_maposter' => $_POST['pt_maposter'],
                'pt_anhposter' => $imgPath
            ]);
            header('Location: /quan-ly-trang-chu');
        }
    }
    public function danhSachPoster()
    {
        $posterModel = new \App\Models\Poster();
        $posters = $posterModel->getAll();
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.DanhSachPoster', [
            'posters' => $posters,
            'activePage' => 'home'
        ]);
    }
    public function suaPoster()
    {
        $id = $_GET['id'] ?? '';
        $posterModel = new \App\Models\Poster();
        $poster = $posterModel->getById($id);
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.SuaPoster', [
            'poster' => $poster,
            'activePage' => 'home'
        ]);
    }
    public function capNhatPoster()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['pt_maposter'];
        $file = $_FILES['anhPoster'];
        $imgPath = $_POST['old_img'];
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $imgPath = '/static/upload/posters/' . uniqid('poster_') . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imgPath);
        }
        $posterModel = new \App\Models\Poster();
        $posterModel->update($id, ['pt_anhposter' => $imgPath]);
        header('Location: /quan-ly-trang-chu');
    }
}
public function xoaPoster()
{
    $id = $_GET['id'] ?? '';
    $db = \App\Core\Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT COUNT(*) FROM phim WHERE p_maposter = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        header('Location: /quan-ly-trang-chu?error=poster_in_use');
        exit;
    }
    // Lấy đường dẫn ảnh poster
    $posterModel = new \App\Models\Poster();
    $poster = $posterModel->getById($id);
    if ($poster && !empty($poster['pt_anhposter'])) {
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $poster['pt_anhposter'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
    $posterModel->delete($id);
    header('Location: /quan-ly-trang-chu');
}

    public function themUuDai()
    {
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.ThemUuDaiHome', ['activePage' => 'home']);
    }

    public function suauuDai()
    {
        $blade = new \Jenssegers\Blade\Blade(
            realpath(__DIR__ . '/../Views'),
            realpath(__DIR__ . '/../../cache')
        );
        echo $blade->render('admin-views.TrangChu.SuaUuDaiHome', ['activePage' => 'home']);
    }
}
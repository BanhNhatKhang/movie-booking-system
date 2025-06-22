<?php
namespace App\Models;

use App\Core\Database;

class NguoiDung
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

}
<?php
namespace App\Core;

class Database
{
    protected $pdo;

    public function __construct()
    {
        $config = require(__DIR__ . '/../config/database.php');
        $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']};";
        $this->pdo = new \PDO($dsn, $config['username'], $config['password'], $config['options']);
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
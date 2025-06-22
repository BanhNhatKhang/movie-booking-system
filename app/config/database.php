<?php
return [
    'driver'   => 'pgsql',
    'host'     => 'localhost',
    'port'     => 5432,
    'database' => 'CT275_Project',
    'username' => 'postgres',
    'password' => 'T@McjBZn',
    'charset'  => 'utf8',
    'options'  => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];
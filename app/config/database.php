<?php

return [
    'driver'   => 'pgsql',
    'host'     => 'localhost',
    'port'     => 5432,
    'database' => 'CT275_Project',
    // 'database' => 'web_phim',
    'username' => 'postgres',
    'password' => 'T@McjBZn',
    // 'password' => 'doquanghuy401181',
    'options'  => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
];
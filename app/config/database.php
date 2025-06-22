<?php
return [
    'driver'   => 'pgsql',
    'host'     => 'localhost',
    'port'     => 5432,
    'database' => 'web_phim',
    'username' => 'postgres',
    'password' => 'doquanghuy401181',
    'charset'  => 'utf8',
    'options'  => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];
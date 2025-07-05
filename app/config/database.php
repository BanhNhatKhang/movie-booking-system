<?php

return [
    'driver'   => 'pgsql',
    'host'     => 'localhost',
    'port'     => 5432,
    'database' => 'CT275_Project',
    'username' => 'postgres',
    'password' => 'T@McjBZn',
    'options'  => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]
];
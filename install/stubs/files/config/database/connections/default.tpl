<?php
return [
    'driver' => env('DB_TYPE', 'mysql'), //$database_type
    'host' => env('DB_HOST', 'localhost'), //$database_server
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', ''), //$dbase
    'username' => env('DB_USERNAME', ''), //$database_user
    'password' => env('DB_PASSWORD', ''), //$database_password
    'unix_socket' => env('DB_SOCKET', ''),
    'charset' => env('DB_CHARSET', 'utf8mb4'), // $database_connection_charset
    'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'), //$database_collation
    'prefix' => env('DB_PREFIX', ''), //$table_prefix
    'method' => env('DB_METHOD', 'SET NAMES'), //$database_connection_method
    'strict' => env('DB_STRICT', true),
    'engine' => env('DB_ENGINE'),
];

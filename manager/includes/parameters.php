<?php
global $database_server, $dbase, $database_user, $database_password, $table_prefix, $database_connection_charset, $database_connection_method;

return [
    'db' => [
        'server' => $database_server,
        'base' => $dbase,
        'user' => $database_user,
        'password' => $database_password,
        'prefix' => $table_prefix,
        'charset' => $database_connection_charset,
        'method' => $database_connection_method
    ]
];

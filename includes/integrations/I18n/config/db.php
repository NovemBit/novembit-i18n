<?php

use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'connection_params' => [
            'dbname' => DB_NAME,
            'user' => DB_USER,
            'password' => DB_PASSWORD,
            'host' => DB_HOST,
            'driver' => 'pdo_mysql',
            'charset' => 'utf8mb4'
        ]
    ];

if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;
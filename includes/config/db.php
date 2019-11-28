<?php

use NovemBit\i18n\component\db\DB;
use NovemBit\wp\plugins\i18n\Bootstrap;

return
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

<?php

use NovemBit\i18n\system\component\DB;
use NovemBit\wp\plugins\i18n\i18n;

return
    [
        'connection' => i18n::getOption('db_connection', [
            'dsn' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            'username' => DB_USER,
            'password' => DB_PASSWORD,
            'charset' => 'utf8mb4',
            'tablePrefix' => 'i18n_',
            'enableQueryCache' => true,
            'queryCacheDuration' => 10000,
            /*'enableSchemaCache' => true,
            'schemaCacheDuration' => 3000,
            'schemaCache' => 'i18n',*/
        ]),
    ];


//
//use NovemBit\i18n\system\component\DB;
//use NovemBit\wp\plugins\i18n\i18n;
//
//$path = WP_CONTENT_DIR . '/novembit-i18n/DB.db';
//return
//    [
//        'connection' => i18n::getOption('db_connection', [
////            'dsn' => 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
////            'username' => DB_USER,
////            'password' => DB_PASSWORD,
////            'charset' => 'utf8mb4',
//
//            'dsn' => 'sqlite:' . $path,
////            'username' => 'root',
////            'password' => '',
//            'charset' => 'utf8',
//
//            'tablePrefix' => 'i18n_',
//            'enableQueryCache' => true,
//            'queryCacheDuration' => 10000,
//            /*'enableSchemaCache' => true,
//            'schemaCacheDuration' => 3000,
//            'schemaCache' => 'i18n',*/
//        ]),
//    ];
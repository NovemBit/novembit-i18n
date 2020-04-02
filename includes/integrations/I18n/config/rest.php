<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'api_keys'    => new Option(
            [
                'type'    => Option::TYPE_TEXT,
                'method'  => Option::METHOD_MULTIPLE,
                'default' => ['GmYg90HtUsd187I2lJ20k7s0oIhBBBAv']
            ]
        )
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;
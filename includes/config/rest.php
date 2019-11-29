<?php

use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'runtime_dir'=>Bootstrap::RUNTIME_DIR,
        'api_keys' => [
            'demo_key_123',
            'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv'
        ]
    ];
if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
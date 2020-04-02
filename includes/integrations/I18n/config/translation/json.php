<?php

defined('ABSPATH') || exit;

use NovemBit\i18n\component\translation\type\JSON;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'class' => JSON::class,
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'save_translations' => false,
        'fields_to_translate' => [
            '/^price_html$/i' => 'html',
            '/^availability_html$/i' => 'html',
        ]
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;

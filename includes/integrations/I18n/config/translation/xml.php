<?php
defined('ABSPATH') || exit;

use NovemBit\i18n\component\translation\type\XML;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class' => XML::class,
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
];

if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;
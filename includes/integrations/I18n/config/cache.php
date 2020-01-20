<?php

defined('ABSPATH') || exit;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use NovemBit\wp\plugins\i18n\Bootstrap;

$cache_dir = WP_CONTENT_DIR . '/cache';
if (!file_exists($cache_dir)) {
    mkdir($cache_dir);
}
$cache_dir .= '/novembit-i18n';

$filesystemAdapter = new Local($cache_dir);
$filesystem = new Filesystem($filesystemAdapter);
$pool = new FilesystemCachePool($filesystem);

return [
    'runtime_dir' => Bootstrap::RUNTIME_DIR,
    'pool' => $pool
];
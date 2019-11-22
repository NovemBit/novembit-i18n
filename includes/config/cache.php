<?php
/**
 * Runtime directory
 **/

use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;


$cache_dir = WP_CONTENT_DIR . '/cache';
if (!file_exists($cache_dir)) {
    mkdir($cache_dir);
}
$cache_dir .= '/novembit-i18n';

$filesystemAdapter = new Local($cache_dir);
$filesystem = new Filesystem($filesystemAdapter);
$pool = new FilesystemCachePool($filesystem);

return [
    'pool' => $pool
];
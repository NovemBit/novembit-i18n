<?php

/** @var I18n $this */

defined('ABSPATH') || exit;

use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

if (! class_exists(NovemBit\i18n\system\helpers\Languages::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Languages.php";
}

if (! class_exists(NovemBit\i18n\system\helpers\Countries::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Countries.php";
}

$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'languages'   => require_once('localization/languages.php')
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;

<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\system\helpers\Countries;
use NovemBit\i18n\system\helpers\Languages;
use NovemBit\wp\plugins\i18n\Bootstrap;

if ( ! class_exists(NovemBit\i18n\system\helpers\Languages::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Languages.php";
}

if ( ! class_exists(NovemBit\i18n\system\helpers\Countries::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Countries.php";
}

$countries_list = Countries::getMap('alpha2', 'name');
$languages_list = Languages::getMap('alpha1', 'name');

$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'languages'   => require_once('localization/languages.php')
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;

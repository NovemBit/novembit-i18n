<?php

use NovemBit\i18n\component\translation\type\Text;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class' => Text::class,
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
    'save_translations' => true,
    /*'exclusions' => [ "Hello"],*/
];
if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
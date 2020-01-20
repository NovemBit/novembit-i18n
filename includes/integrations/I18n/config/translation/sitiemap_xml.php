<?php
defined('ABSPATH') || exit;

use NovemBit\i18n\component\translation\type\XML;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class' => XML::class,
    'name'=>'sitemap_xml',
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
    'xpath_query_map'=>[
        'accept'=>[
            '/*/*/*[1]/text()'=>['type'=>'url']
        ]
    ]
];
if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
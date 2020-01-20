<?php
defined('ABSPATH') || exit;

use NovemBit\i18n\component\translation\type\XML;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class' => XML::class,
    'name' => 'gpf_xml',
    'runtime_dir' => Bootstrap::RUNTIME_DIR,
    'xpath_query_map' => [
        'accept' => [
            "//[name()='title']/text()" => ['type' => 'text'],
            "//[name()='link']/text()" => ['type' => 'url'],
            "//*/*/*[2]/text()" => ['type' => 'url']
        ]
    ]
];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
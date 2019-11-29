<?php

use NovemBit\i18n\component\translation\type\XML;
use NovemBit\wp\plugins\i18n\Bootstrap;

return [
    'class' => XML::class,
    'name'=>'sitemap_xml',
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
    'cache_pool'=>Bootstrap::getCachePool(),
    'xpath_query_map'=>[
        'accept'=>[
            '/*/*/*[1]/text()'=>['type'=>'url']
        ]
    ]
];
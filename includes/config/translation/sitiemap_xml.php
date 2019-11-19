<?php

use NovemBit\i18n\component\translation\type\XML;

return [
    'class' => XML::class,
    'name'=>'sitemap_xml',
    'xpath_query_map'=>[
        'accept'=>[
            '/*/*/*[1]/text()'=>['type'=>'url']
        ]
    ]
];
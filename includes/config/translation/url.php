<?php

use NovemBit\i18n\component\translation\type\URL;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class' => URL::class,
    'runtime_dir'=>Bootstrap::RUNTIME_DIR,
    'path_translation' => true,
    'url_validation_rules' => [
        'scheme' => [
            '^(https?)?$'
        ],
        'host' => [
            sprintf("^$|^%s$|^%s$",
                preg_quote($_SERVER['HTTP_HOST'] ?? ''),
                preg_quote(parse_url(site_url(), PHP_URL_HOST))
            ),
        ],
        'path' => [
            /**
             * @todo query string
             * */
            '^.*(?<!\.js|\.css|\.map|\.png|\.gif|\.webp|\.jpg|\.sass|\.less)$'
        ]
    ]
];
if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
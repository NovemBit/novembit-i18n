<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\i18n\component\translation\type\URL;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class'            => URL::class,
    'runtime_dir'      => Bootstrap::RUNTIME_DIR,
    'path_separator'   => new Option(
        [
            'default' => '-',
            'type'    => Option::TYPE_TEXT
        ]
    ),
    'path_translation' => new Option(
        [
            'default' => true,
            'type'    => Option::TYPE_BOOL
        ]
    ),

    'path_lowercase'          => new Option(
        [
            'default' => 'true',
            'type'    => Option::TYPE_BOOL
        ]
    ),
    'url_validation_rules'    => [
        'scheme' => [
            '^(https?)?$'
        ],
        'host'   => [
            sprintf(
                "^$|^%s$|^%s$",
                preg_quote($_SERVER['HTTP_HOST'] ?? ''),
                preg_quote(parse_url(site_url(), PHP_URL_HOST))
            ),
        ],
        'path'   => [
            /**
             * @todo query string
             * */
            '^.*(?<!\.js|\.css|\.map|\.png|\.gif|\.webp|\.jpg|\.sass|\.less)$'
        ]
    ],
    'path_exclusion_patterns' => new Option(
        ['type' => Option::TYPE_TEXT, 'method' => Option::METHOD_MULTIPLE]
    )
];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;

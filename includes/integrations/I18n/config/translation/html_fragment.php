<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\i18n\component\translation\type\HTMLFragment;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'class'           => HTMLFragment::class,
        'runtime_dir'     => Bootstrap::RUNTIME_DIR,
        'xpath_query_map' => [
            'ignore' => new Option(
                [
                    'default' => include('html/xpath_query_map_ignore.php'),
                    'type'    => Option::TYPE_TEXT,
                    'method'  => Option::METHOD_MULTIPLE
                ]
            )
            ,
            'accept' => new Option(
                [
                    'default' => include('html/xpath_query_map_accept.php'),
                    'type'        => Option::TYPE_OBJECT,
                    'method'      => Option::METHOD_MULTIPLE,
                    'template'    => [
                        'type' => [
                            'type'   => Option::TYPE_TEXT,
                            'values' => apply_filters(Bootstrap::SLUG . '_translation_content_types', [])
                        ],
                    ],
                    'label'       => 'Test',
                    'description' => 'Test.'
                ]
            ),
        ],
        'cache_result'    => true
    ];

if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;

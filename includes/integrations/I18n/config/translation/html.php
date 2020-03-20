<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\i18n\component\translation\type\HTML;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config = [
    'class'              => HTML::class,
    'runtime_dir'        => Bootstrap::RUNTIME_DIR,
    'title_tag_template' => function (array $params) {
        return sprintf(
            '%s | %s, %s',
            $params['translate'],
            mb_convert_case(
                $params['country_native'] ?? ($params['region_native'] ?? ''),
                MB_CASE_TITLE,
                "UTF-8"
            ),
            mb_convert_case(
                ($params['language_native'] ?? $params['language_name'] ?? ''),
                MB_CASE_TITLE,
                "UTF-8"
            )
        );
    },
    'xpath_query_map'    => [
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
                'default'     => include('html/xpath_query_map_accept.php'),
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
    'save_translations'  => false,
];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;
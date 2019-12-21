<?php

use NovemBit\i18n\component\translation\type\HTMLFragment;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Option;

$config =
    [
        'class' => HTMLFragment::class,
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'xpath_query_map' => [
            'ignore' => new Option('html_fragment_xpath_query_map_ignore',
                include('html/xpath_query_map_ignore.php'),
                ['parent' => Bootstrap::SLUG, 'type' => Option::TYPE_TEXT, 'method' => Option::METHOD_MULTIPLE])
            ,
            'accept' => new Option(
                'html_fragment_xpath_query_map_accept',
                include('html/xpath_query_map_accept.php'),
                [
                    'parent'=>Bootstrap::SLUG,
                    'type' => Option::TYPE_OBJECT,
                    'method' => Option::METHOD_MULTIPLE,
                    'template' => [
                        'type' => ['type' => Option::TYPE_TEXT,
                            'values' => [
                                'text' => 'Text',
                                'url' => 'url',
                                'sitemap_xml' => 'Sitemap XML',
                                'xml' => 'XML',
                                'html' => 'Html',
                                'html_fragment' => 'Html Fragment',
                                'json' => 'JSON',
                                'jsonld' => "Json LD"
                            ]
                        ],
                    ],
                    'label' => 'Test',
                    'description' => 'Test.'
                ]
            ),
        ],
        'cache_result' => true
    ];

if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
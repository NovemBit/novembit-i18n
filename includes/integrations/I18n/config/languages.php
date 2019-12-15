<?php

use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Option;

$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'accept_languages' => new Option(
            'accept_languages',
            [
                'cs',
                'da',
                'el',
                'et',
                'es',
                'hr',
                'ja',
                'ko',
                'nl',
                'bg',
                'pl',
                'pt',
                'ro',
                'sl',
                'sv',
                'fr',
                'it',
                'de',
                'ru',
                'en'
            ], [
                'type' => Option::TYPE_TEXT,
                'method' => Option::METHOD_MULTIPLE,
                'values' => ['en' => 'English', 'fr' => 'French', 'ru' => 'Russian', 'hy' => 'Armenian']
            ]
        ),
        'from_language' => new Option('from_language', 'en',[
            'type' => Option::TYPE_TEXT,
            'method' => Option::METHOD_SINGLE,
            'values' => ['en' => 'English', 'fr' => 'French', 'ru' => 'Russian', 'hy' => 'Armenian']
        ]),
        'localization_config' => [
            'default' => ['language' => 'en', 'country' => 'UK', 'region' => 'Europe'],
            '^.*\.uk$' => ['language' => 'en', 'country' => 'UK'],
            '^.*\.ca$' => ['language' => 'en', 'country' => 'Canada'],
            '^.*\.ro$' => ['language' => 'ro', 'country' => 'Romania'],
            '^.*\.gr$' => ['language' => 'el', 'country' => 'Greece'],
            '^.*\.sg$' => ['language' => 'en', 'country' => 'Singapore'],
            '^.*\.fr$' => ['language' => 'fr', 'country' => 'France'],
            '^.*\.it$' => ['language' => 'it', 'country' => 'Italy'],
            '^.*\.nl$' => ['language' => 'nl', 'country' => 'Netherlands'],
            '^.*\.de$' => ['language' => 'de', 'country' => 'Germany'],
            '^.*\.ru$' => ['language' => 'ru', 'country' => 'Russia'],
            '^.*\.dk$' => ['language' => 'da', 'country' => 'Denmark'],
            '^.*\.cz$' => ['language' => 'cs', 'country' => 'Czech Republic'],
            '^.*\.pl$' => ['language' => 'pl', 'country' => 'Poland'],
            '^.*\.nz$' => ['language' => 'en', 'country' => 'New Zealand'],
            '^.*\.si$' => ['language' => 'sl', 'country' => 'Slovenia'],
            '^.*\.kr$' => ['language' => 'ko', 'country' => 'South Korea'],
            '^.*\.ee$' => ['language' => 'et', 'country' => 'Estonia'],
            '^.*\.eu$' => ['language' => 'en', 'region' => 'Europe'],
            '^.*\.com$' => ['language' => 'en', 'country' => 'UK'],
            '^.*\.net$' => ['language' => 'en', 'country' => 'UK'],
            '^.*\.org$' => ['language' => 'en', 'country' => 'UK'],
        ],
        'path_exclusion_patterns' => [
            '.*\.php',
            '.*wp-admin',
            '.*wp-json',
            '(?<=^search)\/.*$',
            '^aff\/.*$'
        ],

    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;
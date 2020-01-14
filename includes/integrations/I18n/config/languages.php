<?php

use NovemBit\i18n\system\helpers\Languages;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Option;

if(!class_exists(NovemBit\i18n\system\helpers\Languages::class)){
    include "vendor/novembit/i18n/src/system/helpers/Languages.php";
}
$languages = Languages::getLanguages();
$languages_list = [];
foreach ($languages as $language){
    $languages_list[$language['alpha1']] = $language['name'];
}
$config =
    [
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'from_language' => new Option('from_language', 'en',[
            'parent'=>Bootstrap::SLUG,
            'type' => Option::TYPE_TEXT,
            'method' => Option::METHOD_SINGLE,
            'values' => $languages_list,
            'label'=>'From language',
            'description'=>'Website main content language.'
        ]),
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
                'parent'=>Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT,
                'method' => Option::METHOD_MULTIPLE,
                'markup' => Option::MARKUP_CHECKBOX,
                'values' => $languages_list,
                'label'=>'To languages',
                'description'=>'In what languages the site should be translated.'

            ]
        ),

        'localization_config' =>new Option(
            'languages_localization_config',
            [
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
            [
                'parent'=>Bootstrap::SLUG,
                'type' => Option::TYPE_OBJECT,
                'method' => Option::METHOD_MULTIPLE,
                'template' => [
                    'language' => ['type' => Option::TYPE_TEXT, 'values' =>$languages_list],
                    'country' => ['type' => Option::TYPE_TEXT],
                    'region' => ['type' => Option::TYPE_TEXT],
                ],
                'label' => 'localization config',
                'description' => 'Test.'
            ]
        ),

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
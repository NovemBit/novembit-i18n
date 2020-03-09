<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\system\helpers\Countries;
use NovemBit\i18n\system\helpers\Languages;
use NovemBit\wp\plugins\i18n\Bootstrap;

if ( ! class_exists(NovemBit\i18n\system\helpers\Languages::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Languages.php";
}

if ( ! class_exists(NovemBit\i18n\system\helpers\Countries::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Countries.php";
}

$languages_list = Languages::getMap('alpha1', 'name');
$countries_list = Countries::getMap('alpha2', 'name');

$config =
    [
        'runtime_dir'         => Bootstrap::RUNTIME_DIR,
        'from_language'       => new Option(
            'from_language',
            'en',
            [
                'parent'      => Bootstrap::SLUG,
                'type'        => Option::TYPE_TEXT,
                'method'      => Option::METHOD_SINGLE,
                'values'      => $languages_list,
                'label'       => 'From language',
                'description' => 'Website main content language.'
            ]
        ),
        'accept_languages'    => new Option(
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
            ],
            [
                'parent'      => Bootstrap::SLUG,
                'type'        => Option::TYPE_TEXT,
                'method'      => Option::METHOD_MULTIPLE,
                'markup'      => Option::MARKUP_SELECT,
                'main_params' => ['style' => 'grid-template-columns: repeat(1, 1fr);'],
                'values'      => $languages_list,
                'label'       => 'To languages',
                'description' => 'In what languages the site should be translated.'
            ]
        ),
        'localization_config' => new Option(
            'languages_localization_config',
            [
                'default'   => ['language' => 'en', 'country' => 'UK', 'region' => 'Europe'],
                '^.*\.eu$'  => ['language' => 'en', 'region' => 'Europe'],
                '^.*\.uk$'  => ['language' => 'en', 'country' => 'UK'],
                '^.*\.ca$'  => ['language' => 'en', 'country' => 'CA'],
                '^.*\.ro$'  => ['language' => 'ro', 'country' => 'RO'],
                '^.*\.gr$'  => ['language' => 'el', 'country' => 'GR'],
                '^.*\.sg$'  => ['language' => 'en', 'country' => 'SG'],
                '^.*\.fr$'  => ['language' => 'fr', 'country' => 'FR'],
                '^.*\.it$'  => ['language' => 'it', 'country' => 'IT'],
                '^.*\.nl$'  => ['language' => 'nl', 'country' => 'NL'],
                '^.*\.de$'  => ['language' => 'de', 'country' => 'DE'],
                '^.*\.ru$'  => ['language' => 'ru', 'country' => 'RU'],
                '^.*\.dk$'  => ['language' => 'da', 'country' => 'DK'],
                '^.*\.cz$'  => ['language' => 'cs', 'country' => 'CZ'],
                '^.*\.pl$'  => ['language' => 'pl', 'country' => 'PL'],
                '^.*\.nz$'  => ['language' => 'en', 'country' => 'NZ'],
                '^.*\.si$'  => ['language' => 'sl', 'country' => 'SI'],
                '^.*\.kr$'  => ['language' => 'ko', 'country' => 'KP'],
                '^.*\.ee$'  => ['language' => 'et', 'country' => 'EE'],
                '^.*\.com$' => ['language' => 'en', 'country' => 'UK'],
                '^.*\.net$' => ['language' => 'en', 'country' => 'UK'],
                '^.*\.org$' => ['language' => 'en', 'country' => 'UK'],
            ],
            [
                'parent'      => Bootstrap::SLUG,
                'main_params' => ['style' => 'grid-template-columns: repeat(3, 1fr);'],
                'type'        => Option::TYPE_OBJECT,
                'method'      => Option::METHOD_MULTIPLE,
                'template'    => [
                    'language'         => [
                        'type'   => Option::TYPE_TEXT,
                        'values' => $languages_list,
                        'label'  => 'Language'
                    ],
                    'accept_languages' => [
                        'type'        => Option::TYPE_TEXT,
                        'method'      => Option::METHOD_MULTIPLE,
                        'markup'      => Option::MARKUP_SELECT,
                        'values'      => $languages_list,
                        'label'       => 'To languages',
                        'description' => 'In what languages the site should be translated.'
                    ],
                    'country'          => [
                        'type'   => Option::TYPE_TEXT,
                        'label'  => 'Country',
                        'values' => $countries_list
                    ],
                    'region'           => ['type' => Option::TYPE_TEXT, 'label' => 'Region'],
                ],
                'label'       => 'Language detection pattern',
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

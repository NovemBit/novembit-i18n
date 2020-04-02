<?php

/** @var I18n $this */

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

if ( ! class_exists(NovemBit\i18n\system\helpers\Languages::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Languages.php";
}

if ( ! class_exists(NovemBit\i18n\system\helpers\Countries::class)) {
    include "vendor/novembit/i18n/src/system/helpers/Countries.php";
}

$config =
    [
        'runtime_dir'         => Bootstrap::RUNTIME_DIR,
        'languages'           => require('localization/languages.php'),
        'regions'             => require('localization/regions.php'),
        'countries'           => require('localization/countries.php'),

        'from_language'           => new Option(
            [
                'default'     => 'en',
                'type'        => Option::TYPE_TEXT,
                'method'      => Option::METHOD_SINGLE,
                'values'      => $this->languages->getList(),
                'label'       => 'From language',
                'description' => 'Website main content language.'
            ]
        ),
        'accept_languages'        => new Option(
            [
                'default'     => [
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
                'type'        => Option::TYPE_TEXT,
                'method'      => Option::METHOD_MULTIPLE,
                'markup'      => Option::MARKUP_SELECT,
                'main_params' => ['style' => 'grid-template-columns: repeat(1, 1fr);'],
                'values'      => $this->languages->getList(),
                'label'       => 'Global to translate languages',
                'description' => 'In what languages the site should be translated.'
            ]
        ),
        'localize_host'           => new Option(
            [
                'default'     => true,
                'type'        => Option::TYPE_BOOL,
                'method'      => Option::METHOD_SINGLE,
                'label'       => 'Localize Hosts',
                'description' => 'Use localization countries and regions to determine url domains.'

            ]
        ),
        'path_exclusion_patterns' => [
            '.*\.php',
            '.*wp-admin',
            '.*wp-json',
            '(?<=^search)\/.*$',
            '^aff\/.*$'
        ],

        'global_domains'      => new Option(
            [
                'default'     => [parse_url(site_url(), PHP_URL_HOST)],
                'method'      => Option::METHOD_MULTIPLE,
                'type'        => Option::TYPE_TEXT,
                'label'       => 'Global domain',
                'description' => 'Default value is WordPress site url domain.'
            ]
        ),
        'localization_config' => new Option(
            [
                'default'     => [
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
                'main_params' => ['style' => 'grid-template-columns: repeat(3, 1fr);'],
                'type'        => Option::TYPE_OBJECT,
                'method'      => Option::METHOD_MULTIPLE,
                'template'    => [
                    'language'         => [
                        'type'   => Option::TYPE_TEXT,
                        'values' => $this->languages->getList(),
                        'label'  => 'Language'
                    ],
                    'accept_languages' => [
                        'type'        => Option::TYPE_TEXT,
                        'method'      => Option::METHOD_MULTIPLE,
                        'markup'      => Option::MARKUP_SELECT,
                        'values'      => $this->languages->getList(),
                        'label'       => 'To languages',
                        'description' => 'In what languages the site should be translated.'
                    ]
                ],
                'label'       => 'Language detection pattern',
            ]
        ),

    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;

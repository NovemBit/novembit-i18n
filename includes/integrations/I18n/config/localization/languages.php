<?php

/** @var I18n $this */

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

return [
    'runtime_dir'             => Bootstrap::RUNTIME_DIR,
    'all'                     => $this->languages->getAll(),
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
            'default' => true,
            'type'   => Option::TYPE_BOOL,
            'method' => Option::METHOD_SINGLE,
            'label'  => 'Localize Hosts'
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

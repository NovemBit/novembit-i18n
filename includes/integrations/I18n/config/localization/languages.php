<?php

/** @var I18n $this */

use diazoxide\wp\lib\option\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n;

return [
    'runtime_dir'             => Bootstrap::RUNTIME_DIR,
    'all_languages'           => $this->languages->getAll(),
    'from_language'           => new Option(
        'from_language',
        'en',
        [

            'type'        => Option::TYPE_TEXT,
            'method'      => Option::METHOD_SINGLE,
            'values'      => $this->languages->getList(),
            'label'       => 'From language',
            'description' => 'Website main content language.'
        ]
    ),
    'localize_host'           => new Option(
        'localize_host',
        true,
        [
            'type'   => Option::TYPE_BOOL,
            'method' => Option::METHOD_SINGLE,
            'label'  => 'Localize Hosts'
        ]
    ),
    'accept_languages'        => new Option(
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
            'type'        => Option::TYPE_TEXT,
            'method'      => Option::METHOD_MULTIPLE,
            'markup'      => Option::MARKUP_SELECT,
            'main_params' => ['style' => 'grid-template-columns: repeat(1, 1fr);'],
            'values'      => $this->languages->getList(),
            'label'       => 'To languages',
            'description' => 'In what languages the site should be translated.'
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

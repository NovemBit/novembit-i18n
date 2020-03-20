<?php

defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\v2\Option;
use NovemBit\i18n\component\translation\method\Google;
use NovemBit\i18n\component\translation\method\Rest;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'class'                  => new Option(
            [
                'default' => Rest::class,
                'type'    => Option::TYPE_TEXT,
                'method'  => Option::METHOD_SINGLE,
                'values'  => [
                    Google::class => 'Google',
                    Rest::class   => 'Rest'
                ],
            ]
        ),
        'runtime_dir'            => Bootstrap::RUNTIME_DIR,
        'remote_host'            => new Option(
            [
                'default' => 'i18n.brandlight.org',
                'type'    => Option::TYPE_TEXT
            ]
        ),
        'ssl'                    => new Option(
            [
                'default' => 'true',
                'type'    => Option::TYPE_BOOL
            ]
        ),
        'request_timeout'        => new Option(
            [
                'default' => 5,
                'type'    => Option::TYPE_TEXT,
                'markup'  => Option::MARKUP_NUMBER
            ]
        ),
        'api_limit_expire_delay' => new Option(
            [
                'default' => 3600,
                'type'    => Option::TYPE_TEXT,
                'markup'  => Option::MARKUP_NUMBER
            ]
        ),
        'api_key'                => new Option(
            [
                'default' => 'xxx',
                'type' => Option::TYPE_TEXT,
            ]
        ),
        'save_translations'      => true,

        /*'class' => Google::class,
        'api_key' => 'AIzaSyA3STDoHZLxiaXXgmmlLuQGdX6f9HhXglA',
        'validation' => true,
        'save_translations' => true,*/

        /*
        'class' => Dummy::class,
        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
        'validation' => true,
        'save_translations' => true*/

        'exclusions' => new Option(
            [
                'default' => [
                    "vitamin",
                    'Adidas',
                    'Terry Naturally',
                    'Twinlab',
                    'Shearer Candles',
                    'Stella Sport',
                    'Planetary Herbals',
                    'Reebok',
                    'Fairhaven Health',
                    'Garden of Life',
                    'Dr. Mercola',
                    'Ellyndale',
                    'Doctor\'s Best',
                    'Cosmesis Skin Care (by Life Extension)',
                    'Bounce',
                    'Now Foods',
                    'Jarrow Formulas',
                    'Pip & Nut',
                    'Liberation',
                    'PraNaturals',
                    'Life Extension',
                    'Regime London',
                    'Metabolife',
                    'Source Naturals',
                    'Milkies',
                    'Swanson',
                    'Natural Factors',
                    'Trèsutopia',
                    'Natures Aid',
                    'Brandlight',
                    'Activpet',
                ],
                'type'   => Option::TYPE_TEXT,
                'method' => Option::METHOD_MULTIPLE
            ]
        ),
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}

return $config;

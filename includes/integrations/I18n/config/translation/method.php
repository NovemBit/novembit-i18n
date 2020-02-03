<?php
defined('ABSPATH') || exit;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\component\translation\method\Google;
use NovemBit\i18n\component\translation\method\Rest;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'class' => new Option('translation_method_class', Rest::class,
            [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT,
                'method' => Option::METHOD_SINGLE,
                'values' => [
                    Google::class => 'Google',
                    Rest::class => 'Rest'
                ],
            ]
        ),
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'remote_host' => new Option('translation_method_remote_host', 'i18n.brandlight.org',
            [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT
            ]
        ),
        'ssl' => new Option('translation_method_ssl', true,
            [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_BOOL
            ]
        ),
        'request_timeout'=>new Option('translation_method_request_timeout', 5,
            [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT,
                'markup'=>Option::MARKUP_NUMBER
            ]
        ),
        'api_limit_expire_delay'=>new Option('translation_method_api_limit_expire_delay', 3600,
            [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT,
                'markup'=>Option::MARKUP_NUMBER
            ]
        ),
        'api_key' => new Option('translation_method_api_key', 'xxx', [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT,
            ]
        ),
        'save_translations' => true,

        /*'class' => Google::class,
        'api_key' => 'AIzaSyA3STDoHZLxiaXXgmmlLuQGdX6f9HhXglA',
        'validation' => true,
        'save_translations' => true,*/

        /*
        'class' => Dummy::class,
        'exclusions' => ['barev', 'barev duxov', "hayer", 'Hello'],
        'validation' => true,
        'save_translations' => true*/

        'exclusions' => new Option('translation_method_exclusions',
            [
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
            [
                'parent' => Bootstrap::SLUG,
                'type' => Option::TYPE_TEXT,
                'method' => Option::METHOD_MULTIPLE
            ]
        ),
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
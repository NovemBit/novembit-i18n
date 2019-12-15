<?php

use NovemBit\i18n\component\translation\method\Google;
use NovemBit\i18n\component\translation\method\Rest;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Option;

$config =
    [
        'class' => new Option('translation_method_class', Rest::class, [
            'type' => Option::TYPE_TEXT,
            'method' => Option::METHOD_SINGLE,
            'values' => [
                Google::class => 'Google',
                Rest::class => 'Rest'
            ]
        ]),
        'runtime_dir' => Bootstrap::RUNTIME_DIR,
        'remote_host' => new Option('translation_method_remote_host', 'i18n.brandlight.org',
            ['type' => Option::TYPE_TEXT]),
        'ssl' => new Option('translation_method_ssl', true, ['type' => Option::TYPE_BOOL]),
        'api_key' => new Option('translation_method_api_key', 'xxx', ['type' => Option::TYPE_TEXT]),
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
            ['type' => Option::TYPE_TEXT, 'method' => Option::METHOD_MULTIPLE]),
    ];
if (Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
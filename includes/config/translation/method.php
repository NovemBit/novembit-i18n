<?php

use NovemBit\i18n\component\translation\method\Rest;
use NovemBit\wp\plugins\i18n\Bootstrap;

$config =
    [
        'class' => Rest::class,
        'runtime_dir'=>Bootstrap::RUNTIME_DIR,
        'remote_host' => 'i18n.brandlight.org',
        'ssl' => false,
        'api_key' => 'GmYg90HtUsd187I2lJ20k7s0oIhBBBAv',
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

        'exclusions' => [
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
    ];
if(Bootstrap::getCachePool()) {
    $config['cache_pool'] = Bootstrap::getCachePool();
}
return $config;
<?php


namespace NovemBit\wp\plugins\i18n\integrations;


use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\system\Integration;

class Algolia extends Integration
{

    public static $integrations = [
        Algolia\AlgoliaWoocommerceFork::class
    ];

    public static $rules = [
        [self::class, 'isI18nInstanceCreated']
    ];

    public static $plugins = [
        'search-by-algolia-instant-relevant-results-fork/algolia.php'
    ];

    public static function isI18nInstanceCreated()
    {
        return Module::instance() !== null;
    }

    public function init(): void
    {

    }
}
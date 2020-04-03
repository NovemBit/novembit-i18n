<?php

namespace NovemBit\wp\plugins\i18n\integrations;

use diazoxide\wp\lib\option\Option;
use NovemBit\i18n\system\helpers\Environment;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n\Countries;
use NovemBit\wp\plugins\i18n\integrations\I18n\Languages;
use NovemBit\wp\plugins\i18n\system\Integration;

class Debug extends Integration
{

    public static $integrations = [
    ];

    public static $rules = [
        [self::class, 'isDevelopDomain']
    ];

    public static function isDevelopDomain(): bool
    {

        if (preg_match('/^default\.*/', Environment::server('HTTP_HOST'))) {
            return true;
        }
        return false;
    }

    public function init(): void
    {

        add_filter(
            Countries::class . '::getDefaultCountriesList',
            static function ($list) {
                $allow = ['gb', 'fr', 'ru', 'am', 'de', 'es'];
                foreach ($list as $key => $item) {
                    if (! in_array($item['alpha2'], $allow, true)) {
                        unset($list[$key]);
                    }
                }
                return $list;
            }
        );

        add_filter(
            Languages::class . '::getDefaultLanguagesList',
            static function ($list) {
                $allow = [
                    'hy',
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
                ];
                foreach ($list as $key => $item) {
                    if (! in_array($item['alpha1'], $allow)) {
                        unset($list[$key]);
                    }
                }
                return $list;
            }
        );
    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}

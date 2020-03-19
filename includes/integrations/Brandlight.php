<?php

namespace NovemBit\wp\plugins\i18n\integrations;

use diazoxide\wp\lib\option\Option;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\integrations\I18n\Countries;
use NovemBit\wp\plugins\i18n\integrations\I18n\Languages;
use NovemBit\wp\plugins\i18n\system\Integration;

class Brandlight extends Integration
{

    public static $integrations = [
    ];

    public static $rules = [
        [self::class, 'isBrandlightTheme']
    ];

    public static function isBrandlightTheme()
    {
        $theme = wp_get_theme();
        if ($theme->get_template() == 'brandlight') {
            return true;
        }

        return false;
    }

    public function init(): void
    {
        /*
         * Google shopping `var` exclusion rules
         * */
        add_filter(
            Option::getOptionFilterName('translation_url_path_exclusion_patterns', Bootstrap::SLUG),
            function ($patterns) {
                if (array_search('/\/var\/.*/is', $patterns) === false) {
                    $patterns[] = '/\/var\/.*/is';
                }

                return $patterns;
            }
        );


        /*
         * Google shopping product feed
         * */
        add_filter(
            Option::getOptionFilterName('request_source_type_map', Bootstrap::SLUG),
            function ($patterns) {
                $patterns['/woocommerce_gpf\/google.*/is'] = 'gpf_xml';

                return $patterns;
            }
        );


        add_filter(
            Countries::class . '::getDefaultCountriesList',
            function ($list) {
                $allow = [
                    'be',
                    'el',
                    'lt',
                    'pt',
                    'bg',
                    'es',
                    'lu',
                    'ro',
                    'cz',
                    'fr',
                    'hu',
                    'si',
                    'dk',
                    'hr',
                    'mt',
                    'sk',
                    'de',
                    'it',
                    'nl',
                    'fi',
                    'ee',
                    'cy',
                    'at',
                    'se',
                    'ie',
                    'lv',
                    'pl',
                    'gb'
                ];
                foreach ($list as $key => $item) {
                    if ( ! in_array($item['alpha2'], $allow)) {
                        unset($list[$key]);
                    }
                }

                return $list;
            }
        );

        add_filter(
            Languages::class . '::getDefaultLanguagesList',
            function ($list) {
                $allow = [
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
                    if ( ! in_array($item['alpha1'], $allow)) {
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

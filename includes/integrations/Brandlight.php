<?php


namespace NovemBit\wp\plugins\i18n\integrations;


use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Integration;
use NovemBit\wp\plugins\i18n\system\Option;

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


    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}
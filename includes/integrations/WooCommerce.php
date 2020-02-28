<?php

namespace NovemBit\wp\plugins\i18n\integrations;

use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\system\Integration;

class WooCommerce extends Integration
{

    public static $integrations = [
    ];

    public static $rules = [
        [self::class, 'isI18nInstanceCreated']
    ];

    public static $plugins = [
        'woocommerce/woocommerce.php'
    ];

    public static function isI18nInstanceCreated()
    {
        return Module::instance() !== null;
    }

    public function init(): void
    {

        /**
         * @Todo To avoid that this part not affecting to another parts of WP
         *
         * */
        /*add_filter('woocommerce_get_country_locale',[$this,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
        add_filter('woocommerce_get_country_locale_default',[$this,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
        add_filter('woocommerce_get_country_locale_base', [$this, 'woocommerceFrontendI18nArray'], PHP_INT_MAX);*/
    }

    /**
     * @param $array
     * @return mixed
     */
    public function woocommerceFrontendI18nArray($array)
    {
        return [];

        /*$to_translate = [];
        Arrays::arrayWalkWithRoute($array, function ($key, &$val, $route) use ($to_translate) {
            if (is_string($val)) {
                if ($key == 'label') {
                    $to_translate[] = $val;
                }
            }
        });

        $translates = Module::instance()->request->getTranslation()->text->translate($to_translate);

        Arrays::arrayWalkWithRoute($array, function ($key, &$val, $route) use ($translates) {
            if (is_string($val)) {
                if ($key == 'label') {
                    $val = $translates[$val][Module::instance()->request->getLanguage()] ?? $val;
                }
            }
        });
        return $array;*/
    }

    protected function adminInit(): void
    {
        // TODO: Implement adminInit() method.
    }
}

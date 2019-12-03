<?php

namespace NovemBit\wp\plugins\i18n;

use Cache\Adapter\Memcached\MemcachedCachePool;
use NovemBit\i18n\Module;
use NovemBit\i18n\system\helpers\Arrays;


class Bootstrap
{

    const RUNTIME_DIR = WP_CONTENT_DIR . '/novembit-i18n';

    private static $_cache_pool;

    public static function getCachePool()
    {

        if(!isset(self::$_cache_pool)){

            if (!class_exists('Memcached')) {
                return null;
            }

            $client = new \Memcached();
            $client->addServer('localhost', 11211);
            self::$_cache_pool = new MemcachedCachePool($client);
        }

        return self::$_cache_pool;
    }

    public static function init()
    {

        add_action('init', function () {

            $integration = new Integration();

            $integration->run();

        }, 10);


//        add_filter('woocommerce_get_country_locale',[self::class,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
//        add_filter('woocommerce_get_country_locale_default',[self::class,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
//        add_filter('woocommerce_get_country_locale_base', [self::class, 'woocommerceFrontendI18nArray'], PHP_INT_MAX);


    }

    private static function isWPCli()
    {
        if (defined('WP_CLI') && WP_CLI) {
            return true;
        }
        return false;
    }

    public static function woocommerceFrontendI18nArray($array)
    {
        $to_translate = [];
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
        return $array;
    }


    public static function isWPRest()
    {
        $rest_path = get_rest_url(null, '/', 'relative');

        $rest_path = trim($rest_path, '/');

        $url = trim($_SERVER['REQUEST_URI'] ?? '', '/');

        if (substr($url, 0, strlen($rest_path)) === $rest_path) {
            return true;
        }

        return false;
    }


}
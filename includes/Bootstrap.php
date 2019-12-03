<?php

namespace NovemBit\wp\plugins\i18n;

use Cache\Adapter\Memcached\MemcachedCachePool;
use Exception;

use NovemBit\i18n\Module;
use NovemBit\i18n\system\helpers\Arrays;
use NovemBit\wp\plugins\i18n\integrations\Integration;


class Bootstrap
{

    const RUNTIME_DIR = WP_CONTENT_DIR . '/novembit-i18n';

    public static $integrations = [
        integrations\I18n::class,
        integrations\Algolia::class,
        integrations\Woocommerce::class,
        integrations\TheSEOFramework::class
    ];

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

            self::runIntegrations();

        }, 10);


//        add_filter('woocommerce_get_country_locale',[self::class,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
//        add_filter('woocommerce_get_country_locale_default',[self::class,'woocommerceFrontendI18nArray'],PHP_INT_MAX);
//        add_filter('woocommerce_get_country_locale_base', [self::class, 'woocommerceFrontendI18nArray'], PHP_INT_MAX);


    }

    public static function runIntegrations()
    {
        foreach (self::$integrations as $integration) {
            $instance = new $integration();
            if ($instance instanceof Integration) {
                $instance->run();
            }
        }
    }

    /**
     * @param \WP_Admin_Bar $admin_bar
     */
    public static function adminBarMenu($admin_bar)
    {
        /** @var \WP_Admin_Bar $admin_bar */
        $admin_bar->add_menu(array(
            'id' => 'novembit-i18n',
            'title' => 'NovemBit i18n',
            'meta' => array(
                'title' => 'NovemBit i18n',
            ),
        ));

        $admin_bar->add_menu(array(
            'id' => 'clear-cache',
            'parent' => 'novembit-i18n',
            'title' => 'Clear translations cache',
            'meta' => array(
                'title' => 'Temporary cache (DB records not including).',
                'class' => 'clear_cache',
                'onclick' => "if(confirm('Press Ok to delete cache.')) window.location.href='?novembit-i18n-action=clear-cache'"
            ),
        ));

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
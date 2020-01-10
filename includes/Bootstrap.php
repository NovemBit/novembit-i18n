<?php

namespace NovemBit\wp\plugins\i18n;

use Cache\Adapter\Memcached\MemcachedCachePool;

class Bootstrap
{

    const RUNTIME_DIR = WP_CONTENT_DIR . '/novembit-i18n';

    const SLUG = 'novembit-i18n';

    private static $_cache_pool;

    public static function getOptionName($option)
    {
        return self::SLUG . '_' . $option;
    }

    /**
     * @param string $option
     * @param null $default
     *
     * @return array|mixed|void
     */
    public static function getOption($option, $default = null)
    {
        if (self::isOptionConstant($option)) {
            return constant(self::getOptionName($option));
        }

        return get_option(self::getOptionName($option), $default);
    }


    /**
     * @param $option
     *
     * @return bool
     */
    public static function isOptionConstant($option)
    {
        return defined(self::getOptionName($option));
    }

    /**
     * @param $option
     * @param $value
     *
     * @return bool
     */
    public static function setOption($option, $value)
    {
        $option = self::getOptionName($option);
        if (update_option($option, $value)) {
            return true;
        }

        return false;
    }

    public static function setCachePool($pool)
    {
        self::$_cache_pool = $pool;
    }

    public static function getCachePool()
    {
        return self::$_cache_pool;
    }

    public static function init()
    {

        add_action('init', function () {

            $integration = new Integration();

            $integration->run();

        }, 10);

    }

    private static function isWPCli()
    {
        if (defined('WP_CLI') && WP_CLI) {
            return true;
        }
        return false;
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
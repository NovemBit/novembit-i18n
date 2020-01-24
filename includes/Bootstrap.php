<?php

namespace NovemBit\wp\plugins\i18n;

class Bootstrap
{

    const RUNTIME_DIR = WP_CONTENT_DIR . '/novembit-i18n';

    const SLUG = 'novembit-i18n';

    private static $_cache_pool;

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
        add_action(
            'init',
            function () {
                if (!session_id()) {
                    session_start();
                }

                add_action('admin_notices', [Bootstrap::class, 'printNotices']);

                $integration = new Integration();

                $integration->run();
            },
            10
        );
    }

    private static function isWPCli()
    {
        if (defined('WP_CLI') && WP_CLI) {
            return true;
        }

        return false;
    }


    public static function addNotice($message, $type = 'success', $dismissible = false)
    {
        $_SESSION[self::SLUG . '-notices'][] = ['type' => $type, 'message' => $message, 'dismissible' => $dismissible];
    }


    public static function printNotices()
    {
        if (!isset($_SESSION[self::SLUG . '-notices'])) {
            return;
        }
        foreach ($_SESSION[self::SLUG . '-notices'] as $key => $notice) {
            {
                $type = $notice['type'] ?? 'success';
                $dismissible = $notice['dismissible'] ?? true;
                $message = $notice['message'] ?? '';
                ?>
                <div class="notice notice-<?php echo $type; ?> <?php echo $dismissible ? 'is-dismissible' : ''; ?>">
                    <p><?php echo $message; ?></p>
                </div>
                <?php

                unset($_SESSION[self::SLUG . '-notices'][$key]);
            }
        }
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
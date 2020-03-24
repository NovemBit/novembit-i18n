<?php

namespace NovemBit\wp\plugins\i18n;

use NovemBit\wp\plugins\i18n\shortcodes\Editor;
use NovemBit\wp\plugins\i18n\shortcodes\Switcher;
use Psr\SimpleCache\CacheInterface;

class Bootstrap
{

    public const RUNTIME_DIR = WP_CONTENT_DIR . '/novembit-i18n';

    public const SLUG = 'novembit-i18n';

    /**
     * Main plugin file
     *
     * @var string
     * */
    private $plugin_file;

    /**
     * Cache pool
     *
     * @var CacheInterface
     * */
    private static $cache_pool;

    /**
     * Main instance of class
     *
     * @var self
     * */
    private static $instance;

    /**
     * @param null $plugin_file
     *
     * @return Bootstrap
     */
    public static function instance($plugin_file = null)
    {
        if (! isset(self::$instance)) {
            self::$instance = new self($plugin_file);
        }

        return self::$instance;
    }

    /**
     * Bootstrap constructor.
     *
     * @param $plugin_file
     */
    private function __construct($plugin_file)
    {
        $this->plugin_file = $plugin_file;

        register_activation_hook($this->getPluginFile(), [Install::class, 'install']);

        register_deactivation_hook($this->getPluginFile(), [Install::class, 'uninstall']);

        $this->initHooks();
    }

    /**
     * Set Cache Pool
     *
     * @param CacheInterface $pool PSR cache
     */
    public static function setCachePool(CacheInterface $pool): void
    {
        self::$cache_pool = $pool;
    }

    /**
     * Get Cache Pool
     *
     * @return CacheInterface
     */
    public static function getCachePool(): ?CacheInterface
    {
        return self::$cache_pool;
    }

    /**
     * @deprecated
     * */
    public static function init()
    {
        $old_mu = WPMU_PLUGIN_DIR . '/0_novembit_i18n.php';
        if (file_exists(WPMU_PLUGIN_DIR . '/0_novembit_i18n.php')) {
            unlink($old_mu);
        }
    }

    /**
     * @return void
     */
    private function initHooks(): void
    {
        add_action(
            'init',
            function () {
                if (! session_id()) {
                    session_start();
                }

                add_action('admin_notices', [Bootstrap::class, 'printNotices']);

                $integration = new Integration();

                $integration->run();
            },
            10
        );

        add_action(
            'init',
            function () {
                Switcher::init();
                Editor::init();
            }
        );
    }

    /**
     * @return bool
     */
    private static function isWPCli(): bool
    {
        if (defined('WP_CLI') && WP_CLI) {
            return true;
        }

        return false;
    }


    /**
     * @param $message
     * @param string $type
     * @param bool $dismissible
     */
    public static function addNotice($message, $type = 'success', $dismissible = false): void
    {
        $_SESSION[self::SLUG . '-notices'][] = ['type' => $type, 'message' => $message, 'dismissible' => $dismissible];
    }

    /**
     * @return void
     */
    public static function printNotices(): void
    {
        if (! isset($_SESSION[self::SLUG . '-notices'])) {
            return;
        }
        foreach ($_SESSION[self::SLUG . '-notices'] as $key => $notice) {
            {
                $type        = $notice['type'] ?? 'success';
                $dismissible = $notice['dismissible'] ?? true;
                $message     = $notice['message'] ?? '';
            ?>
                <div class="notice notice-<?php echo $type; ?> <?php echo $dismissible ? 'is-dismissible' : ''; ?>">
                    <p><?php echo $message; ?></p>
                </div>
                <?php

                unset($_SESSION[self::SLUG . '-notices'][$key]);
                }
        }
    }

    /**
     * @return bool
     */
    public static function isWPRest(): bool
    {
        $rest_path = get_rest_url(null, '/', 'relative');

        $rest_path = trim($rest_path, '/');

        $url = trim($_SERVER['REQUEST_URI'] ?? '', '/');

        if (substr($url, 0, strlen($rest_path)) === $rest_path) {
            return true;
        }

        return false;
    }

    /**
     * Restriction mode
     *
     * @return mixed|void
     */
    public function isRestrictedMode(): bool
    {
        return apply_filters(self::SLUG . '-admin-restricted-mode', false);
    }

    /**
     * @return mixed
     */
    public function getPluginFile()
    {
        return $this->plugin_file;
    }


    /**
     * @return mixed
     */
    public function getPluginDirUrl()
    {
        return plugin_dir_url($this->getPluginFile());
    }


    /**
     * @return mixed
     */
    public function getPluginBasename()
    {
        return plugin_basename($this->getPluginFile());
    }
}

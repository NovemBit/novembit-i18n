<?php


namespace NovemBit\wp\plugins\i18n\integrations;


use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\Bootstrap;
use NovemBit\wp\plugins\i18n\system\Option;
use NovemBit\wp\plugins\i18n\system\Integration;
use Psr\SimpleCache\InvalidArgumentException;

class I18n extends Integration
{

    public static $classes = [
        Module::class
    ];

    public function init(): void
    {
        /**
         * Creating instance of i18n
         * */
        $this->createInstance();

        add_action('init', [$this, 'startRequestTranslation'], 11);

        add_action('init', function () {

            add_action('admin_bar_menu', [$this, 'adminBarMenu'], 100);

            if (Module::instance()->request->isReady()) {

                add_filter('wp_redirect', [$this, 'redirectUrlTranslation'], PHP_INT_MAX, 1);

                add_filter('wp_safe_redirect', [$this, 'redirectUrlTranslation'], PHP_INT_MAX, 1);

                add_filter('redirect_canonical', function () {
                    return false;
                }, PHP_INT_MAX, 2);

                add_action('admin_init', function () {
                    remove_action('admin_head', 'wp_admin_canonical_url');
                }, PHP_INT_MAX);
            }

        }, 11);

        /**
         * Implement cache deletion
         * */
        if (isset($_GET['novembit-i18n-action'])
            && $_GET['novembit-i18n-action'] == 'clear-cache'
            && current_user_can('administrator')
        ) {
            try {
                Module::instance()->getCachePool()->clear();
                Module::instance()->request->getCachePool()->clear();
                Module::instance()->translation->getCachePool()->clear();
                Module::instance()->translation->method->getCachePool()->clear();
                Module::instance()->translation->text->getCachePool()->clear();
                Module::instance()->translation->url->getCachePool()->clear();
                Module::instance()->translation->html_fragment->getCachePool()->clear();
                Module::instance()->translation->html->getCachePool()->clear();
                Module::instance()->translation->xml->getCachePool()->clear();
                Module::instance()->translation->json->getCachePool()->clear();
            } catch (\Exception $exception) {
                /**
                 * Prevent warnings
                 * */
            }

            wp_redirect(wp_get_referer() ?? site_url());

            exit;
        }
    }

    /**
     * @param \WP_Admin_Bar $admin_bar
     */
    public function adminBarMenu($admin_bar): void
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

    /**
     * Start request content translation
     *
     * @return void
     * */
    public function startRequestTranslation(): void
    {
        Module::instance()->request->start();
    }

    /**
     * @param $url
     * @return string
     * @throws \Exception
     * @throws InvalidArgumentException
     */
    public function redirectUrlTranslation($url)
    {

        $parsed = parse_url($url);

        if (
            isset($parsed['host']) &&
            !in_array($parsed['host'], [$_SERVER['HTTP_HOST'], parse_url(site_url(), PHP_URL_HOST)])
        ) {
            return $url;
        }

        $language = Module::instance()->request->getLanguage();
        if ($language !== null) {

            $url = Module::instance()->request->getTranslation()->url->translate([$url])[$url][$language] ?? $url;
            $parts = parse_url($url);
            if (isset($parts['host'])) {
                return $url;
            }
            $url = '/' . ltrim($url, '/');
        }
        return $url;
    }

    private $options = [];

    public function createInstance(): void
    {
        $this->options = [
            /**
             * Runtime Dir for module global instance
             * */
            'runtime_dir' => Bootstrap::RUNTIME_DIR,
            /**
             * Components configs
             * */
            'translation' => include(__DIR__ . '/I18n/config/translation.php'),
            'languages' => include(__DIR__ . '/I18n/config/languages.php'),
            'request' => include(__DIR__ . '/I18n/config/request.php'),
            'rest' => include(__DIR__ . '/I18n/config/rest.php'),
            'db' => include(__DIR__ . '/I18n/config/db.php'),
        ];

        $options = $this->options;

        array_walk_recursive($options, function (&$item, $key) {
            if ($item instanceof Option) {
                $item = $item->getValue();
            }
        });

        Module::instance(
            $options
        );

        if (is_admin()) {
            $this->adminInit();
        }
    }

    public function getOptionGroup()
    {
        return str_replace('\\', '-', static::class);
    }

    public function registerSettings()
    {
        array_walk_recursive($this->options, function ($item, $key) {
            if ($item instanceof Option) {
                register_setting(static::getOptionGroup(), Bootstrap::getOptionName($item->getName()));
            }
        });
    }

    protected function adminInit(): void
    {

        add_action('admin_init', [$this, 'registerSettings']);

        add_action('admin_menu', function () {
            add_submenu_page(
                Bootstrap::SLUG,
                'i18n options',
                'i18n configurations',
                'manage_options',
                Bootstrap::SLUG . '-integration-i18n',
                [$this, 'adminContent'],
                1
            );
        });
    }

    private static function arrayWalkWithRoute(
        array &$arr,
        callable $callback,
        array $route = []
    ): void {
        foreach ($arr as $key => &$val) {
            $_route = $route;
            $_route[] = $key;
            if (is_array($val)) {
                self::arrayWalkWithRoute($val, $callback, $_route);
            } else {
                call_user_func_array($callback, [$key, &$val, $_route]);
            }
        }
    }

    private static function printArrayList($array)
    {
        echo '<ul class="' . Bootstrap::SLUG . '-admin-nested-fields">';

        foreach ($array as $k => $v) {
            if (is_array($v)) {
                echo "<li>" . $k . "</li>";
                self::printArrayList($v);
                continue;
            }

            echo "<li>" . $v . "</li>";
        }

        echo "</ul>";
    }

    public function adminContent()
    {

        $_fields = [];
        static::arrayWalkWithRoute($this->options, function ($key, $item, $route) use (&$_fields) {
            if ($item instanceof Option) {
                array_pop($route);
                $label = $item->getParam('label', $item->getName());
                $field = $item->getField();
                $html = sprintf('<div>
                    <div>%s</div>
                    <div>%s</div>
                </div>', $label, $field);

                $temp = &$_fields;
                foreach ($route as $key) {
                    $temp = &$temp[$key];
                }
                $temp[] = $html;
                unset($temp);
            }
        });


        ?>
        <div class="wrap <?php echo Bootstrap::SLUG; ?>-wrap">
            <h1>i18n Configuration</h1>

            <form method="post" action="options.php">
                <?php settings_fields(static::getOptionGroup()); ?>
                <?php do_settings_sections(static::getOptionGroup()); ?>
                <?php self::printArrayList($_fields); ?>
                <?php submit_button(); ?>

            </form>
        </div>
        <?php
    }
}
<?php


namespace NovemBit\wp\plugins\i18n;


use Cache\Adapter\Memcached\MemcachedCachePool;
use NovemBit\wp\plugins\i18n\system\Option;

class Integration extends system\Integration
{

    public static $integrations = [
        \NovemBit\wp\plugins\i18n\integrations\I18n::class,
        \NovemBit\wp\plugins\i18n\integrations\Algolia::class,
        \NovemBit\wp\plugins\i18n\integrations\WooCommerce::class,
        \NovemBit\wp\plugins\i18n\integrations\TheSEOFramework::class
    ];

    public $options = [];

    protected function init(): void
    {

        $this->options = [
            'performance' => [
                'cache_pool' => [
                    'type' => new Option('performance_cache_pool_type', 'file',
                        [
                            'parent' => Bootstrap::SLUG,
                            'type' => Option::TYPE_TEXT,
                            'method' => Option::METHOD_SINGLE,
                            'values' => [
                                'file' => 'File cache',
                                'memcached' => 'Memcached'
                            ],
                        ]
                    ),
                    'pools' => [
                        'memcached' => [
                            'host' => new Option('performance_cache_pool_pools_memcached_host', 'localhost',
                                [
                                    'parent' => Bootstrap::SLUG,
                                    'type' => Option::TYPE_TEXT,
                                    'method' => Option::METHOD_SINGLE
                                ]
                            ),
                            'port' => new Option('performance_cache_pool_pools_memcached_port', '11211',
                                [
                                    'parent' => Bootstrap::SLUG,
                                    'type' => Option::TYPE_TEXT,
                                    'markup' => Option::MARKUP_NUMBER,
                                    'method' => Option::METHOD_SINGLE
                                ]
                            )
                        ],
                    ]
                ]
            ]
        ];

        $this->setBootstrapCachePool();

        add_action('admin_bar_menu', [$this, 'adminBarMenu'], 100);

        if (is_admin()) {
            $this->adminInit();
        }
    }

    private function setBootstrapCachePool(){

        $options = $this->options;

        array_walk_recursive($options, function (&$item, $key) {
            if ($item instanceof Option) {
                $item = $item->getValue();
            }
        });

        if($options['performance']['cache_pool']['type'] == 'memcached'){
            if (!class_exists('Memcached')) {
                return null;
            }

            $client = new \Memcached();

            $host = $options['performance']['cache_pool']['pools']['memcached']['host'] ?? 'localhost';
            $port = $options['performance']['cache_pool']['pools']['memcached']['port'] ?? 11211;

            $client->addServer($host, $port);

            $pool = new MemcachedCachePool($client);

            Bootstrap::setCachePool($pool);

        }
    }

    protected function adminInit(): void
    {

//        wp_enqueue_style(Bootstrap::SLUG . '-bs-grid',
//            plugin_dir_url(NOVEMBIT_I18N_PLUGIN_FILE) . '/vendor/twbs/bootstrap/dist/css/bootstrap-grid.min.css', [], '0.1');

        wp_enqueue_style(Bootstrap::SLUG . '-admin',
            plugin_dir_url(NOVEMBIT_I18N_PLUGIN_FILE) . '/includes/assets/style/admin.css',
            [], '0.2');

        wp_enqueue_script(Bootstrap::SLUG,
            plugin_dir_url(NOVEMBIT_I18N_PLUGIN_FILE) . '/includes/assets/scripts/admin.js', [], '0.2');

        add_action('admin_menu', [$this, 'adminMenu']);


    }

    /**
     * @param \WP_Admin_Bar $admin_bar
     */
    public function adminBarMenu($admin_bar): void
    {
        /** @var \WP_Admin_Bar $admin_bar */
        $admin_bar->add_menu(array(
            'id' => Bootstrap::SLUG,
            'title' => 'NovemBit i18n',
            'meta' => array(
                'title' => 'NovemBit i18n',
            ),
        ));
    }

    public function adminMenu()
    {

        add_menu_page(
            __('NovemBit i18n', 'novembit-18n'),
            __('NovemBit i18n', 'novembit-18n'),
            'manage_options',
            Bootstrap::SLUG,
            [$this, 'adminContent'],
            'dashicons-admin-site-alt',
            75
        );


        add_submenu_page(
            Bootstrap::SLUG,
            'NovemBit i18n - Performance',
            'Performance',
            'manage_options',
            Bootstrap::SLUG . '-performance',
            [$this, 'adminContentPerformance'],
            1
        );
    }

    public function adminContentPerformance()
    {
        ?>
        <div class="wrap">

            <h1>Performance</h1>
            <?php
            Option::printForm(Bootstrap::SLUG, $this->options);
            ?>
        </div>
        <?php
    }

    public function adminContent()
    {
        ?>
        <div class="wrap">
            <h1>NovemBit i18n internationalization plugin.</h1>

            <p>NovemBit-i18n is powerful WordPress framework to provide multilingual web site, and translates site
                content without human intervention. Plugin to automatically translate your site. Just need to make the
                right settings.</p>
        </div>
        <?php
    }
}
<?php

namespace NovemBit\wp\plugins\i18n;

use Cache\Adapter\Memcached\MemcachedCachePool;
use diazoxide\wp\lib\option\v2\Option;
use WP_Admin_Bar;

class Integration extends system\Integration
{

    public static $integrations = [
        \NovemBit\wp\plugins\i18n\integrations\Debug::class,
        \NovemBit\wp\plugins\i18n\integrations\Brandlight::class,
        \NovemBit\wp\plugins\i18n\integrations\I18n::class,
        \NovemBit\wp\plugins\i18n\integrations\Algolia::class,
        \NovemBit\wp\plugins\i18n\integrations\WooCommerce::class,
        \NovemBit\wp\plugins\i18n\integrations\TheSEOFramework::class,

    ];

    public $options = [];

    protected function init(): void
    {
        $this->options = [
            'performance' => [
                'cache_pool' => [
                    'type'  => new Option(
                        [
                            'default' => 'file',
                            'type'    => Option::TYPE_TEXT,
                            'method'  => Option::METHOD_SINGLE,
                            'values'  => [
                                'file'      => 'File cache',
                                'memcached' => 'Memcached'
                            ],
                        ]
                    ),
                    'pools' => [
                        'memcached' => [
                            'host' => new Option(
                                [
                                    'default' => 'localhost',
                                    'type'    => Option::TYPE_TEXT,
                                    'method'  => Option::METHOD_SINGLE
                                ]
                            ),
                            'port' => new Option(
                                [
                                    'default' => 11211,
                                    'type'    => Option::TYPE_TEXT,
                                    'markup'  => Option::MARKUP_NUMBER,
                                    'method'  => Option::METHOD_SINGLE
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

    /**
     * @return void
     */
    private function setBootstrapCachePool(): void
    {
        $options = Option::expandOptions($this->options, Bootstrap::SLUG);

        $type = $options['performance']['cache_pool']['type'] ?? 'file';

        if ($type === 'memcached' && class_exists('Memcached')) {
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
        add_action('admin_menu', [$this, 'adminMenu']);
    }

    /**
     * @param WP_Admin_Bar $admin_bar
     */
    public function adminBarMenu($admin_bar): void
    {
        $admin_bar->add_menu(
            array(
                'id'    => Bootstrap::SLUG,
                'title' => __('I18n', 'novembit-18n'),
                'meta'  => array(
                    'title' => __('NovemBit i18n', 'novembit-18n'),
                ),
            )
        );

        $admin_bar->add_menu(
            array(
                'id'     => 'settings',
                'parent' => Bootstrap::SLUG,
                'href'   => admin_url('admin.php?page=' . Bootstrap::SLUG),
                'title'  => 'Settings',
                'meta'   => array(
                    'title' => 'Settings',
                ),
            )
        );
    }

    /**
     * @return void
     */
    public function adminMenu(): void
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

        if ( ! Bootstrap::instance()->isRestrictedMode()) {
            add_submenu_page(
                Bootstrap::SLUG,
                __('NovemBit i18n - Performance'),
                __('Performance'),
                'manage_options',
                Bootstrap::SLUG . '-performance',
                [$this, 'adminContentPerformance']
            );
        }
    }

    /**
     * @return void
     */
    public function adminContentPerformance(): void
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

    /**
     * @return void
     */
    public function adminContent(): void
    {
        ?>
        <div class="wrap">
            <h1>NovemBit i18n internationalization plugin.</h1>
            <h4>Version: <?php echo Bootstrap::VERSION; ?></h4>

            <p>NovemBit-i18n is powerful WordPress framework to provide multilingual web site, and translates site
                content without human intervention. Plugin to automatically translate your site. Just need to make the
                right settings.</p>

        </div>
        <?php
    }
}

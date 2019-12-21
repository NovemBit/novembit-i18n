<?php


namespace NovemBit\wp\plugins\i18n;


class Integration extends system\Integration
{

    public static $integrations = [
        \NovemBit\wp\plugins\i18n\integrations\I18n::class,
        \NovemBit\wp\plugins\i18n\integrations\Algolia::class,
        \NovemBit\wp\plugins\i18n\integrations\WooCommerce::class,
        \NovemBit\wp\plugins\i18n\integrations\TheSEOFramework::class
    ];

    protected function init(): void
    {
        add_action('admin_bar_menu', [$this, 'adminBarMenu'], 100);

        if (is_admin()) {
            $this->adminInit();
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
            'dashicons-schedule',
            75
        );
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
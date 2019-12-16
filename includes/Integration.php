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
        if(is_admin()){
            $this->adminInit();
        }
    }

    protected function adminInit(): void
    {

        wp_enqueue_style(Bootstrap::SLUG,plugin_dir_url(NOVEMBIT_I18N_PLUGIN_FILE).'/includes/assets/style/admin.css',[],'0.2');

        wp_enqueue_script(Bootstrap::SLUG, plugin_dir_url(NOVEMBIT_I18N_PLUGIN_FILE).'/includes/assets/scripts/admin.js',[],'0.2');

        add_action('admin_menu', [$this, 'adminMenu']);

    }

    public function adminMenu()
    {

        add_menu_page(
            __( 'NovemBit i18n', 'novembit-18n' ),
            __( 'NovemBit i18n', 'novembit-18n' ),
            'manage_options',
            Bootstrap::SLUG,
            [$this,'adminContent'],
            'dashicons-schedule',
            75
        );
    }

    public function adminContent(){
        echo '<div class="wrap">';
        echo '<h1>NovemBit i18n internationalization plugin.</h1>';
        echo '</div>';
    }
}
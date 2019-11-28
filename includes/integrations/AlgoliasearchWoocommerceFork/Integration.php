<?php


namespace NovemBit\wp\plugins\i18n\integrations\AlgoliasearchWoocommerceFork;


use NovemBit\i18n\Module;

class Integration extends \NovemBit\wp\plugins\i18n\integrations\Integration
{

    public static function pluginNames(): array
    {
        return [
            'algoliasearch-woocommerce-fork/algolia-woocommerce.php'
        ];
    }

    public function init(): void
    {

        add_action('init',[$this,'fixPermalinkIssues'],11);

    }

    public function fixPermalinkIssues(){
        if (Module::instance()->request->isReady()) {
            wp_enqueue_script(
                self::class . '-script',
                plugins_url('/includes/integrations/AlgoliasearchWoocommerceFork/assets/fix-permalinks.js',
                    NOVEMBIT_I18N_PLUGIN_FILE),
                [],
                '1.0.1',
                true
            );
        }
    }
}
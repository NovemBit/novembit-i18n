<?php


namespace NovemBit\wp\plugins\i18n\integrations\AlgoliasearchWoocommerceFork;


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

        wp_enqueue_script(
            self::class . '-script',
            plugins_url('/includes/integrations/AlgoliasearchWoocommerceFork/assets/fix-permalinks.js', NOVEMBIT_I18N_PLUGIN_FILE),
            [],
            '1.0.1',
            true
        );

    }

}
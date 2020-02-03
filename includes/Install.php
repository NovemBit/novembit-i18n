<?php

namespace NovemBit\wp\plugins\i18n;


use diazoxide\wp\lib\option\Option;

class Install
{

    private static $filename = '0_novembit_i18n.php';

    private static $migration_version = "1.0.1";

    public static function install()
    {
        self::migration();

        self::install_mu_plugin();
    }

    public static function uninstall()
    {
        self::uninstall_mu_plugin();
    }

    private static function install_mu_plugin()
    {
        $source = dirname(__FILE__) . '/../mu-plugins/i18n.php';
        $target = WPMU_PLUGIN_DIR . '/' . self::$filename;

        if (!file_exists(WPMU_PLUGIN_DIR) || !is_dir(WPMU_PLUGIN_DIR)) {
            mkdir(WPMU_PLUGIN_DIR);
        }

        if (!copy($source, $target)) {
            add_action(
                'admin_notices',
                function () {
                    ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php _e('Can\'t install mu-plugin file!', 'novembit-i18n'); ?></p>
                    </div>
                    <?php
                }
            );
        }
    }

    /**
     * @return bool
     */
    private static function uninstall_mu_plugin()
    {
        $target = WPMU_PLUGIN_DIR . '/' . self::$filename;

        if (unlink($target)) {
            return true;
        }
        return false;
    }

    /**
     *
     */
    private static function migration()
    {
        if (Option::getOption('migration_version', Bootstrap::SLUG, null) != self::$migration_version) {
            $sql = file_get_contents(__DIR__ . '/../vendor/novembit/i18n/migrations/structure.sql');

            global $wpdb;

            try {
                $wpdb->query($sql);

                Option::setOption('migration_version', Bootstrap::SLUG, self::$migration_version);
            } catch (\Exception $exception) {
            }
        }
    }

}
<?php

namespace NovemBit\wp\plugins\i18n;

use diazoxide\wp\lib\option\Option;

class Install
{

    private static $migration_version = "1.0.1";

    public static function install()
    {
        self::migration();
    }

    public static function uninstall()
    {
    }

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
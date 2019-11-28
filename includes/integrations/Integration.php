<?php


namespace NovemBit\wp\plugins\i18n\integrations;


abstract class Integration
{

    abstract public static function pluginNames(): array;

    abstract protected function init(): void;

    final public function run()
    {

        $plugins = static::pluginNames();

        foreach ($plugins as $plugin) {
            if (!function_exists('is_plugin_active')
                || !is_plugin_active($plugin)
            ) {
                return;
            }
        }

        $this->init();

    }
}
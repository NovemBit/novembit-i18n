<?php

namespace NovemBit\wp\plugins\i18n\system;

abstract class Integration
{

    public static $integrations = [];

    public static $plugins = [];

    public static $classes = [];

    public static $functions = [];

    public static $rules = [];

    public static $name;

    protected static $integrationsInstances = [];

    /**
     * @throws \Exception
     */
    final public static function runIntegrations(): void
    {
        foreach (static::$integrations as $integration) {
            $instance = new $integration();
            if ($instance instanceof Integration) {
                static::$integrationsInstances[self::getName()] = &$instance;

                $instance->run();
            } else {
                throw new \Exception(sprintf("Class is not instance of '%s'.", Integration::class));
            }
        }
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    final public static function getIntegration($name)
    {
        return static::$integrationsInstances[$name];
    }

    final public static function getIntegrationsInstances(): array
    {
        return static::$integrationsInstances;
    }

    /**
     * @return string
     */
    final public static function getName(): string
    {
        return static::$name ?? static::class;
    }


    /**
     * @throws \Exception
     */
    final public function run(): void
    {
        foreach (static::$rules as $rule) {
            if (is_callable($rule)) {
                if (! call_user_func($rule)) {
                    return;
                }
            } elseif (! $rule) {
                return;
            }
        }

        foreach (static::$classes as $class) {
            if (! class_exists($class)) {
                return;
            }
        }

        foreach (static::$functions as $function) {
            if (! function_exists($function)) {
                return;
            }
        }

        foreach (static::$plugins as $plugin) {
            if (
                ! function_exists('is_plugin_active')
                 || ! is_plugin_active($plugin)
            ) {
                return;
            }
        }


        $this->init();

        static::runIntegrations();
    }

    /**
     *
     */
    abstract protected function init(): void;
}

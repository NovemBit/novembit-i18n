<?php

namespace NovemBit\wp\plugins\i18n;

use NovemBit\i18n\Module;
use NovemBit\wp\plugins\i18n\shortcodes\Editor;
use NovemBit\wp\plugins\i18n\shortcodes\Switcher;

class i18n
{

    const SLUG = "NOVEMBIT_I18N";

    public $version = '1.0.0';

    private static $_instance = null;

    /**
     * @return i18n|null
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct()
    {
        $this->define_constants();
        $this->init_hooks();
        $this->init_assets_version();
    }

    /**
     * Define constant if not already set.
     *
     * @param string $name Constant name.
     * @param string|bool $value Constant value.
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    private function define_constants()
    {
        $this->define('NOVEMBIT_I18N_ABSPATH', dirname(NOVEMBIT_I18N_PLUGIN_FILE) . '/');
        $this->define('NOVEMBIT_I18N_PLUGIN_BASENAME', plugin_basename(NOVEMBIT_I18N_PLUGIN_FILE));
        $this->define('NOVEMBIT_I18N_VERSION', $this->version);
        $this->define('NOVEMBIT_I18N_PLUGIN_ACTIVE', true);
    }

    /**
     * Hook into actions and filters.
     *
     */
    private function init_hooks()
    {
        register_activation_hook(NOVEMBIT_I18N_PLUGIN_FILE, [Install::class, 'install']);
        register_deactivation_hook(NOVEMBIT_I18N_PLUGIN_FILE, [Install::class, 'uninstall']);

        add_action('plugins_loaded', [$this, 'on_plugins_loaded'], -1);

        add_action('init', [$this, 'init'], PHP_INT_MAX - 10);
    }

    public function on_plugins_loaded()
    {
        do_action('novembit_i18n_loaded');
    }


    public function init()
    {
        /*
         * Init short codes
         * */
        Switcher::init();
        Editor::init();
    }

    /**
     * @param string $option
     * @param null $default
     *
     * @return array|mixed|void
     */
    public static function getOption($option, $default = null)
    {
        if (self::isOptionConstant($option)) {
            return constant(self::SLUG . "_" . $option);
        }

        return get_option(self::SLUG . $option, $default);
    }


    /**
     * @param $option
     *
     * @return bool
     */
    public static function isOptionConstant($option)
    {
        $option = self::SLUG . "_" . $option;

        return defined($option);
    }

    /**
     * @param $option
     * @param $value
     *
     * @return bool
     */
    public static function setOption($option, $value)
    {

        $option = self::SLUG . "_" . $option;

        if (update_option($option, $value)) {
            return true;
        }

        return false;
    }

    /**
     * Setting up assets version
     */
    private function init_assets_version()
    {
        i18n::setOption('assets_version', $this->version);
    }

    public static function getUrlTranslation($url, $lang=null){

        if(Module::instance()->request->isReady()){
            return $url;
        }

        if($lang==null){
            $lang = Module::instance()->request->getLanguage();
        } elseif(!Module::instance()->languages->validateLanguage($lang)){
            return $url;
        }

        return  Module::instance()
                ->translation
                ->setLanguages([$lang])
                ->url
                ->translate([$url])[$url][$lang]
            ?? $url;
    }

}
<?php

defined( 'ABSPATH' ) || exit;

final class NovemBit_i18n {

	const SLUG = "novembit-i18n";

	public $version = '1.0.0';

	private static $_instance = null;

	/**
	 * @return Novembit_i18n|null
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		$this->init_assets_version();
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string $name Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	private function define_constants() {
		$this->define( 'NOVEMBIT_I18N_ABSPATH', dirname( NOVEMBIT_I18N_PLUGIN_FILE ) . '/' );
		$this->define( 'NOVEMBIT_I18N_PLUGIN_BASENAME', plugin_basename( NOVEMBIT_I18N_PLUGIN_FILE ) );
		$this->define( 'NOVEMBIT_I18N_VERSION', $this->version );
		$this->define( 'NOVEMBIT_I18N_PLUGIN_ACTIVE', true );
	}

	/**
	 * Hook into actions and filters.
	 *
	 */
	private function init_hooks() {
		register_activation_hook( NOVEMBIT_I18N_PLUGIN_FILE, array( 'NovemBit_i18n_Install', 'install' ) );
		register_deactivation_hook( NOVEMBIT_I18N_PLUGIN_FILE, array( 'NovemBit_i18n_Install', 'uninstall' ) );

		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), - 1 );
		add_action( 'init', array( $this, 'init' ), PHP_INT_MAX - 10 );
	}

	public function on_plugins_loaded() {
		do_action( 'novembit_i18n_loaded' );
	}


	public function init() {

	}

	public function includes() {
		include_once "class-novembit-i18n-bootstrap.php";
		include_once "class-novembit-i18n-install.php";
	}


	/**
	 * @param string $option
	 * @param null $default
	 *
	 * @return array|mixed|void
	 */
	public static function getOption( $option, $default = null ) {
		if ( self::isOptionConstant( $option ) ) {
			return constant( self::class . "_" . $option );
		}

		return get_option( self::class . "_" . $option, $default );
	}


	/**
	 * @param $option
	 *
	 * @return bool
	 */
	public static function isOptionConstant( $option ) {
		$option = self::class . "_" . $option;

		return defined( $option );
	}

	/**
	 * @param $option
	 * @param $value
	 *
	 * @return bool
	 */
	public static function setOption( $option, $value ) {

		$option = self::class . "_" . $option;

		if ( update_option( $option, $value ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Setting up assets version
	 */
	private function init_assets_version() {
		NovemBit_i18n::setOption( 'assets_version', $this->version );
	}

}
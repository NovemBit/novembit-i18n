<?php
/**
 * Plugin Name: WordPress NovemBit i18n
 * Plugin URI:
 * Description: Dom translation
 * Version: 0.1
 * Author: Novembit
 * Author URI:
 * License: GPLv3
 * Text Domain: novembit-18n
 */

defined( 'ABSPATH' ) || exit;

// Include the main WPI18N class.
if ( ! class_exists( 'Novebit_i18n' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-novembit-i18n.php';
}

// Define WPPF_PLUGIN_FILE.
if ( ! defined( 'NOVEMBIT_I18N_PLUGIN_FILE' ) ) {
	define( 'NOVEMBIT_I18N_PLUGIN_FILE', __FILE__ );
}

/**
 * Returns the main instance of Novembit_i18n.
 *
 * @return Novembit_i18n
 */
function NovemBit_i18n() {
	return NovemBit_i18n::instance();
}

// Global for backwards compatibility.
$GLOBALS['novembit_i18n'] = Novembit_i18n();

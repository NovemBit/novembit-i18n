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

use NovemBit\wp\plugins\i18n\i18n;

defined( 'ABSPATH' ) || exit;

include_once dirname( __FILE__ ) . '/vendor/autoload.php';

// Define WPPF_PLUGIN_FILE.
if ( ! defined( 'NOVEMBIT_I18N_PLUGIN_FILE' ) ) {
	define( 'NOVEMBIT_I18N_PLUGIN_FILE', __FILE__ );
}

/**
 * Returns the main instance of Novembit_i18n.
 *
 * @return i18n
 */
function NovemBit_i18n() {
	return i18n::instance();
}

// Global for backwards compatibility.
$GLOBALS['novembit_i18n'] = Novembit_i18n();

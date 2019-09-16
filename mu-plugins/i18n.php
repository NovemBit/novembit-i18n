<?php
defined( 'ABSPATH' ) || exit;

$active_plugins = get_option( 'active_plugins' );

if ( !in_array( "wp-i18n/novembit-i18n.php", $active_plugins ) ) {
	return;
}

include_once WP_PLUGIN_DIR . '/wp-i18n/includes/class-novembit-i18n-bootstrap.php';

NovemBit_i18n_bootstrap::init();
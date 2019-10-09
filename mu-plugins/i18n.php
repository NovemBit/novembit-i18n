<?php

use NovemBit\wp\plugins\i18n\Bootstrap;

defined('ABSPATH') || exit;

$active_plugins = get_option('active_plugins');

if (!in_array("novembit-i18n/novembit-i18n.php", $active_plugins)) {
    return;
}

require WP_PLUGIN_DIR . '/novembit-i18n/vendor/autoload.php';

Bootstrap::init();
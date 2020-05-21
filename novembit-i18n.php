<?php

/**
 * Plugin Name: WordPress NovemBit i18n
 * Plugin URI:
 * Description: Dom translation
 * Version: 2.4.1
 * Author: Novembit
 * Author URI:
 * License: GPLv3
 * Text Domain: novembit-i18n
 */

use NovemBit\wp\plugins\i18n\Bootstrap;

defined('ABSPATH') || exit;

include_once __DIR__ . '/vendor/autoload.php';

Bootstrap::instance(__FILE__);
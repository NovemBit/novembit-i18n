<?php

/**
 * Plugin Name: WordPress NovemBit i18n
 * Plugin URI:
 * Description: Dom translation
 * Version: 0.1
 * Author: Novembit
 * Author URI:
 * License: GPLv3
 * Text Domain: novembit-i18n
 */

use NovemBit\wp\plugins\i18n\Bootstrap;

defined('ABSPATH') || exit;

include_once dirname(__FILE__) . '/vendor/autoload.php';

Bootstrap::instance(__FILE__);

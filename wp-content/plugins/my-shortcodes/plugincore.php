<?php
/*
Plugin Name: My Shortcodes
Plugin URI: http://myshortcodes.cramer.co.za
Description: Build advanced custom shortcodes to use within your site or distribute to other sites.
Author: David Cramer
Version: 1.9
Author URI: http://cramer.co.za
*/

//initilize plugin
define('ELEMENTS_PATH', plugin_dir_path(__FILE__));
define('ELEMENTS_URL', plugin_dir_url(__FILE__));

require_once ELEMENTS_PATH.'libs/functions.php';
require_once ELEMENTS_PATH.'libs/actions.php';

?>
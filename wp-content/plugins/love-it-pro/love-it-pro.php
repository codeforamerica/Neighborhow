<?php
/*
Plugin Name: Love It Pro
Plugin URI: http://pippinsplugins.com/love-it-pro
Description: Adds a "Love It" link to posts, pages, and custom post types
Version: 1.1.4
Author: Pippin Williamson
Contributors: mordauk
Author URI: http://pippinsplugins.com
*/

/***************************
* constants
***************************/

if(!defined('LI_BASE_DIR')) {
	define('LI_BASE_DIR', dirname(__FILE__));
}
if(!defined('LI_BASE_URL')) {
	define('LI_BASE_URL', plugin_dir_url(__FILE__));
}
if(!defined('LI_BASE_FILE')) {
	define('LI_BASE_FILE', __FILE__);
}

if( !defined( 'LIP_PLUGIN_VERSION' ) ) define( 'LIP_PLUGIN_VERSION', '1.1.4' );

$lip_options = get_option('lip_settings');

/***************************
* language files
***************************/
load_plugin_textdomain( 'love_it', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

/***************************
* includes
***************************/
include(LI_BASE_DIR . '/includes/display-functions.php');
include(LI_BASE_DIR . '/includes/love-functions.php');
include(LI_BASE_DIR . '/includes/scripts.php');
include(LI_BASE_DIR . '/includes/widgets.php');
if(is_admin()) {
	include(LI_BASE_DIR . '/includes/settings.php');
	
	if(!class_exists('Custom_Plugin_Updater')) {
		include_once(LI_BASE_DIR . '/includes/class-custom-plugin-updater.php' );
	}
	// setup the plugin updater
	$lip_updater = new Custom_Plugin_Updater( 'http://pippinsplugins.com/updater/api/', LI_BASE_FILE, array( 'version' => LIP_PLUGIN_VERSION ));
}
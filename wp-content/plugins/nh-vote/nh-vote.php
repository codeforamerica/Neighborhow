<?php
/*
Plugin Name: NH Vote
Plugin URI: http://neighborhow.org
Description: Adds a vote btn to posts
Version: 1.0
Author: Neighborow
Author URI: http://neighborhow.org
*/

/***************************
* constants
***************************/

if(!defined('NH_BASE_DIR')) {
	define('NH_BASE_DIR', dirname(__FILE__));
}
if(!defined('NH_BASE_URL')) {
	define('NH_BASE_URL', plugin_dir_url(__FILE__));
}
if(!defined('NH_BASE_FILE')) {
	define('NH_BASE_FILE', __FILE__);
}

//$vote_options = get_option('vote_settings');

/***************************
* includes
***************************/
include(NH_BASE_DIR . '/includes/display-functions.php');
include(NH_BASE_DIR . '/includes/vote-functions.php');
include(NH_BASE_DIR . '/includes/scripts.php');
//if(is_admin()) {
//	include(NH_BASE_DIR . '/includes/settings.php');
//}
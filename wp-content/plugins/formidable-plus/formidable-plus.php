<?php
/**
 * @package Formidable
 */
/*
Plugin Name: Formidable Plus
Description: Adds a table field-type
Version: 1.1.7.1
Plugin URI: http://topquark.com/extend/plugins/formidable-plus
Author URI: http://topquark.com
Author: topquarky
*/

/*  Copyright 2010  topquark  (email : support@topquark.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/
    
define('FRMPLUS_PLUGIN_TITLE','Formidable Plus');
define('FRMPLUS_PLUGIN_NAME','formidable-plus');
define('FRMPLUS_PATH',WP_PLUGIN_DIR.'/'.FRMPLUS_PLUGIN_NAME);
define('FRMPLUS_CONTROLLERS_PATH',FRMPLUS_PATH.'/classes/controllers');
define('FRMPLUS_HELPERS_PATH',FRMPLUS_PATH.'/classes/helpers');
define('FRMPLUS_MODELS_PATH',FRMPLUS_PATH.'/classes/models');
define('FRMPLUS_VIEWS_PATH',FRMPLUS_PATH.'/classes/views');
define('FRMPLUS_TEMPLATES_PATH',FRMPLUS_PATH.'/classes/templates');

define('FRMPLUS_URL',WP_PLUGIN_URL.'/'.FRMPLUS_PLUGIN_NAME);
define('FRMPLUS_MODELS_URL',FRMPLUS_URL.'/classes/models');
define('FRMPLUS_VIEWS_URL',FRMPLUS_URL.'/classes/views');
define('FRMPLUS_IMAGES_URL',FRMPLUS_URL.'/images');
define('FRMPLUS_ICONS_URL',FRMPLUS_IMAGES_URL.'/error_icons');

// Instansiate Helpers
require_once(FRMPLUS_HELPERS_PATH . "/FrmPlusEntriesHelper.php");
require_once(FRMPLUS_HELPERS_PATH . "/FrmPlusEntryMetaHelper.php");
require_once(FRMPLUS_HELPERS_PATH . "/FrmPlusFieldsHelper.php");

global $frmplus_entries_helper;
global $frmplus_entry_meta_helper;
global $frmplus_fields_helper;

$frmplus_entries_helper     = new FrmPlusEntriesHelper();
$frmplus_entry_meta_helper  = new FrmPlusEntryMetaHelper();
$frmplus_fields_helper      = new FrmPlusFieldsHelper();


// Instansiate Controllers
require_once(FRMPLUS_CONTROLLERS_PATH . "/FrmPlusAppController.php");
require_once(FRMPLUS_CONTROLLERS_PATH . "/FrmPlusFieldsController.php");
require_once(FRMPLUS_CONTROLLERS_PATH . "/FrmPlusFormsController.php");
require_once(FRMPLUS_CONTROLLERS_PATH . "/FrmPlusEntriesController.php");

global $frmplus_app_controller;
global $frmplus_fields_controller;
global $frmplus_forms_controller;
global $frmplus_entries_controller;

$frmplus_app_controller         = new FrmPlusAppController();
$frmplus_forms_controller       = new FrmPlusFormsController();
$frmplus_fields_controller      = new FrmPlusFieldsController();
$frmplus_entries_controller      = new FrmPlusEntriesController();

?><?php //BEGIN::SELF_HOSTED_PLUGIN_MOD
					
	/**********************************************
	* The following was added by Self Hosted Plugin
	* to enable automatic updates
	* See http://wordpress.org/extend/plugins/self-hosted-plugins
	**********************************************/
	require "__plugin-updates/plugin-update-checker.class.php";
	$__UpdateChecker = new PluginUpdateChecker('http://topquark.com/extend/plugins/formidable-plus/update', __FILE__,'formidable-plus');			
	

	include_once("__plugin-updates/topquark.settings.php");
	add_action('init',create_function('$a','do_action("register_topquark_plugin","Formidable Plus");'));
//END::SELF_HOSTED_PLUGIN_MOD ?>
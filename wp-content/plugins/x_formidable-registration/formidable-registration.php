<?php
/*
Plugin Name: Formidable Registration
Plugin URI: http://formidablepro.com/knowledgebase/formidable-registration/
Description: Register users through a Formidable form
Author: Strategy11
Author URI: http://strategy11.com
Version: 1.01
*/

define('FRM_REG_PLUGIN_TITLE', 'Formidable Registration');
define('FRM_REG_PLUGIN_NAME', 'formidable-registration');
define('FRM_REG_PATH', WP_PLUGIN_DIR.'/'.FRM_REG_PLUGIN_NAME);

include_once(FRM_REG_PATH .'/FrmRegAppController.php');
global $frm_reg_app_controller;
$frm_reg_app_controller = new FrmRegAppController();

include_once(FRM_REG_PATH .'/FrmRegAppHelper.php');
global $frm_reg_app_helper;
$frm_reg_app_helper = new FrmRegAppHelper();

// Register Widgets
if(class_exists('WP_Widget')){
    require_once(FRM_REG_PATH . '/widgets/FrmRegLogin.php');
    add_action('widgets_init', create_function('', 'return register_widget("FrmRegLogin");'));
}


add_action('admin_init', 'frm_reg_include_updater', 1);
function frm_reg_include_updater(){
    if(!class_exists('FrmUpdate')) return;
    
    include_once(FRM_REG_PATH.'/FrmRegUpdate.php');
    global $frm_reg_update;
    $frm_reg_update = new FrmRegUpdate();
}

?>
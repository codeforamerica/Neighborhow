<?php 
/**
 * @package Formidable
 */
 
define('FRMPRO_PATH',FRM_PATH.'/pro');
define('FRMPRO_CONTROLLERS_PATH',FRMPRO_PATH.'/classes/controllers');
define('FRMPRO_HELPERS_PATH',FRMPRO_PATH.'/classes/helpers');
define('FRMPRO_MODELS_PATH',FRMPRO_PATH.'/classes/models');
define('FRMPRO_VIEWS_PATH',FRMPRO_PATH.'/classes/views');
define('FRMPRO_TEMPLATES_PATH',FRMPRO_PATH.'/classes/templates');

define('FRMPRO_URL',FRM_URL.'/pro');
define('FRMPRO_MODELS_URL',FRMPRO_URL.'/classes/models');
define('FRMPRO_VIEWS_URL',FRMPRO_URL.'/classes/views');
define('FRMPRO_IMAGES_URL',FRMPRO_URL.'/images');
define('FRMPRO_ICONS_URL',FRMPRO_IMAGES_URL.'/error_icons');

define('FRM_WIDGETS_PATH',FRMPRO_PATH.'/classes/widgets');

require_once(FRMPRO_MODELS_PATH.'/FrmProSettings.php');

global $frmpro_settings;

$frmpro_settings = get_transient('frmpro_options');
if(!is_object($frmpro_settings)){
    if($frmpro_settings){ //workaround for W3 total cache conflict
        $frmpro_settings = unserialize(serialize($frmpro_settings));
    }else{
        $frmpro_settings = get_option('frmpro_options');

        // If unserializing didn't work
        if(!is_object($frmpro_settings)){
            if($frmpro_settings) //workaround for W3 total cache conflict
                $frmpro_settings = unserialize(serialize($frmpro_settings));
            else
                $frmpro_settings = new FrmProSettings();
            update_option('frmpro_options', $frmpro_settings);
            set_transient('frmpro_options', $frmpro_settings);
        }
    }
}
$frmpro_settings = get_option('frmpro_options');

// If unserializing didn't work
if(!is_object($frmpro_settings)){
    if($frmpro_settings) //workaround for W3 total cache conflict
        $frmpro_settings = unserialize(serialize($frmpro_settings));
    else
        $frmpro_settings = new FrmProSettings();
    update_option('frmpro_options', $frmpro_settings);
}

$frmpro_settings->set_default_options();

global $frm_readonly;
$frm_readonly = false;

global $frm_show_fields, $frm_rte_loaded, $frm_datepicker_loaded;
global $frm_timepicker_loaded, $frm_hidden_fields, $frm_calc_fields, $frm_input_masks;
$frm_show_fields = $frm_rte_loaded = $frm_datepicker_loaded = $frm_timepicker_loaded = array();
$frm_hidden_fields = $frm_calc_fields = $frm_input_masks = array();

global $frm_settings;
if(!is_admin() and $frm_settings->jquery_css)
    $frm_datepicker_loaded = true;
    
require_once(FRMPRO_MODELS_PATH.'/FrmProDb.php');
require_once(FRMPRO_MODELS_PATH.'/FrmProDisplay.php');
require_once(FRMPRO_MODELS_PATH.'/FrmProEntry.php');
require_once(FRMPRO_MODELS_PATH.'/FrmProEntryMeta.php');
require_once(FRMPRO_MODELS_PATH.'/FrmProField.php');
require_once(FRMPRO_MODELS_PATH.'/FrmProForm.php');
require_once(FRMPRO_MODELS_PATH.'/FrmProNotification.php');

global $frmprodb;
global $frmpro_display;
global $frmpro_entry;
global $frmpro_entry_meta;
global $frmpro_field;
global $frmpro_form;
global $frmpro_notification;

$frmprodb           = new FrmProDb();
$frmpro_display     = new FrmProDisplay();
$frmpro_entry       = new FrmProEntry();
$frmpro_entry_meta  = new FrmProEntryMeta();
$frmpro_field       = new FrmProField();
$frmpro_form        = new FrmProForm();
$frmpro_notification = new FrmProNotification();

// Instansiate Controllers
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProAppController.php");
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProDisplaysController.php");
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProEntriesController.php");
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProFieldsController.php");
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProFormsController.php");
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProSettingsController.php");
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProStatisticsController.php");

global $frmpro_app_controller;
global $frmpro_displays_controller;
global $frmpro_entries_controller;
global $frmpro_fields_controller;
global $frmpro_forms_controller;
global $frmpro_settings_controller;
global $frmpro_statistics_controller;

$frmpro_app_controller         = new FrmProAppController();
$frmpro_displays_controller    = new FrmProDisplaysController();
$frmpro_entries_controller     = new FrmProEntriesController();
$frmpro_fields_controller      = new FrmProFieldsController();
$frmpro_forms_controller       = new FrmProFormsController();
$frmpro_settings_controller    = new FrmProSettingsController();
$frmpro_statistics_controller  = new FrmProStatisticsController();

if (IS_WPMU){
//Models
require_once(FRMPRO_MODELS_PATH.'/FrmProCopy.php');
global $frmpro_copy;
$frmpro_copy = new FrmProCopy();
    
//Add options to copy forms and displays
require_once(FRMPRO_CONTROLLERS_PATH . "/FrmProCopiesController.php");
global $frmpro_copies_controller;
$frmpro_copies_controller = new FrmProCopiesController();
}

// Instansiate Helpers
require_once(FRMPRO_HELPERS_PATH. "/FrmProAppHelper.php");
require_once(FRMPRO_HELPERS_PATH. "/FrmProDisplaysHelper.php");
require_once(FRMPRO_HELPERS_PATH. "/FrmProEntriesHelper.php");
require_once(FRMPRO_HELPERS_PATH. "/FrmProEntryMetaHelper.php");
require_once(FRMPRO_HELPERS_PATH. "/FrmProFieldsHelper.php");
require_once(FRMPRO_HELPERS_PATH. "/FrmProFormsHelper.php");

global $frmpro_app_helper;
global $frmpro_displays_helper;
global $frmpro_entries_helper;
global $frmpro_entry_meta_helper;
global $frmpro_fields_helper;
global $frmpro_forms_helper;

$frmpro_app_helper      = new FrmProAppHelper();
$frmpro_displays_helper = new FrmProDisplaysHelper();
$frmpro_entries_helper  = new FrmProEntriesHelper();
$frmpro_entry_meta_helper = new FrmProEntryMetaHelper();
$frmpro_fields_helper   = new FrmProFieldsHelper();
$frmpro_forms_helper    = new FrmProFormsHelper();

global $frm_next_page, $frm_prev_page;
$frm_next_page = $frm_prev_page = array();

global $frm_media_id;
$frm_media_id = array();

// Register Widgets
if(class_exists('WP_Widget')){
    // Include Widgets
    require_once(FRM_WIDGETS_PATH . "/FrmListEntries.php");
    //require_once(FRM_WIDGETS_PATH . "/FrmPollResults.php");
    
    add_action('widgets_init', create_function('', 'return register_widget("FrmListEntries");'));
    //add_action('widgets_init', create_function('', 'return register_widget("FrmPollResults");'));
}

?>
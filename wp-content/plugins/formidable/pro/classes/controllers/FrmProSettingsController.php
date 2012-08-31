<?php

class FrmProSettingsController{
    function FrmProSettingsController(){
        add_action('frm_settings_form', array(&$this, 'more_settings'));
        add_action('admin_init',  array(&$this, 'admin_init'));
        add_action('frm_update_settings',  array(&$this, 'update'));
        add_action('frm_store_settings', array(&$this, 'store'));
    }
    
    function more_settings($frm_settings){
        global $frmpro_settings, $wp_scripts;
        $error_icons = glob(FRMPRO_PATH."/images/error_icons/*.png");
        
        $jquery_themes = FrmProAppHelper::jquery_themes();
        
        require(FRMPRO_VIEWS_PATH.'/settings/form.php');
    }
    
    function admin_init(){
        global $frm_settings;
        if(isset($_GET) and isset($_GET['page']) and $_GET['page'] == 'formidable-settings')
            wp_enqueue_script('jquery-ui-datepicker');
        add_action('admin_head-'. sanitize_title($frm_settings->menu) .'_page_formidable-settings', array(&$this, 'head'));
    }
    
    function head(){
        $js_file  = FRM_URL . '/js/jquery/jquery-ui-themepicker.js';
      ?>
        <link type="text/css" rel="stylesheet" href="http<?php is_ssl() ? 's' : ''; ?>://ajax.googleapis.com/ajax/libs/jqueryui/1.7.3/themes/base/ui.all.css" />
        <link href="<?php echo FRM_SCRIPT_URL ?>&amp;controller=settings" type="text/css" rel="Stylesheet" class="frm-custom-theme"/>
        <?php
        require(FRM_VIEWS_PATH . '/shared/head.php');
    }

    function update($params){
        global $frmpro_settings;
        $frmpro_settings->update($params);
    }

    function store(){
        global $frmpro_settings;
        $frmpro_settings->store();
    }
}

?>
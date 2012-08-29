<?php
/**
 * @package Formidable
 */
 
class FrmProAppController{
    function FrmProAppController(){
        add_action('init', array(&$this, 'create_taxonomies'), 0 );
        //add_action('admin_menu', array( $this, 'menu' ), 25);
        add_action('frm_column_header',array(&$this, 'insert_header_checkbox'));
        add_action('frm_first_col',array(&$this, 'insert_item_checkbox'));
        add_action('frm_before_table', array(&$this, 'add_bulk_actions'));
        add_action('frm_before_item_nav', array(&$this, 'insert_search_form'), 10, 4);
        add_filter('widget_text', array(&$this, 'widget_text_filter'), 8 );
        add_action('frm_standalone_route', array(&$this, 'standalone_route'), 10, 2);
        add_action('frm_after_install', array(&$this, 'install'));
        add_action('frm_after_uninstall', array(&$this, 'uninstall'));
    }
    
    
    function create_taxonomies() {
        register_taxonomy( 'frm_tag', 'formidable', array( 'hierarchical' => false,
    													//'update_count_callback' => '_update_post_term_count',
    													'label' => __('Formidable Tags', 'formidable'),
    													'singular_label' => __('Formidable Tag', 'formidable'),
    													//'query_var' => true,
    													//'rewrite' => true,
    													'public' => true,
    													'show_ui' => true
    												) ) ;
    }
    
    function menu(){
        //add_submenu_page('formidable, 'Formidable | Entry Tags', '<code>Pro</code> Entry Tags', 'administrator', 'edit-tags.php&taxonomy=frm_tag', array($this, 'route'));
        add_submenu_page('formidable', 'Formidable | Import/Export', '<code>Pro</code> Import/Export', 'frm_edit_forms', 'formidable-import', array($this, 'route'));
    }
    
    function route(){
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $action = FrmAppHelper::get_param($action);
        if($action=='import_xml')
            return $this->import_xml();
        else
            return $this->form();
    }
    
    function import_xml(){        
        if( isset($_FILES) and isset($_FILES['frm_import_file']) and !empty($_FILES['frm_import_file']['name']) and (int)$_FILES['frm_import_file']['size'] > 0){
            //$media_id = FrmProAppHelper::upload_file('frm_import_file');
            //if(is_numeric($media_id)){
                ob_start();
                include $_FILES['frm_import_file']['tmp_name'];
                $xml_content = ob_get_contents();
                ob_end_clean();
                FrmProAppHelper::import_xml($xml_content);
            //}else{
            //    foreach ($media_id->errors as $error)
            //        echo $error[0];
            //}
        }
    }
    
    function form(){
        $forms = FrmForm::getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name');
        include_once(FRMPRO_VIEWS_PATH .'/shared/import_form.php');
    }
    
    
    //Bulk Actions
    function insert_header_checkbox(){ 
        FrmProAppHelper::header_checkbox();
    }
    

    function insert_item_checkbox($id){ 
        FrmProAppHelper::item_checkbox($id);
    }
    

    function add_bulk_actions($footer){
        FrmProAppHelper::bulk_actions($footer);
    }
    
    function export_xml($type=false, $ids=false){
        if(isset($_GET['page']) and preg_match('/formidable*/', $_GET['page'])){
            if(!current_user_can('frm_edit_forms')){
                global $frm_settings;
                wp_die($frm_settings->admin_permission);
            }
            
            if(!$type){
                $bulk = FrmAppHelper::get_param('action');
                if($bulk == -1)
                    $bulk = FrmAppHelper::get_param('action2');

                if(!empty($bulk) and strpos($bulk, 'bulk_') === 0){
                    $bulk = str_replace('bulk_', '', $bulk);
                }else{
                    $bulk = '-1';
                    if(isset($_POST['bulkaction']) and $_POST['bulkaction'] != '-1')
                        $bulk = $_POST['bulkaction'];
                    else if(isset($_POST['bulkaction2']) and $_POST['bulkaction2'] != '-1')
                        $bulk = $_POST['bulkaction2'];
                }
                
                if($bulk == 'export'){
                    $ids = $_REQUEST['item-action'];

                    if($_GET['page'] == 'formidable-entries')
                        $type = 'items';
                    else if($_GET['page'] == 'formidable-entry-templates')
                        $type = 'displays';
                    else
                        $type = 'forms';
                }else{ 
                    $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
                    $action = FrmAppHelper::get_param($action);
                    
                    if($action == 'export_frm_xml'){
                        $type = $frm_export_types;

                        if(isset($_POST['frm_export_forms']))
                            $ids = $_POST['frm_export_forms'];
                    }
                }
            }
            if(!$type) return;
            
            if(is_array($ids))
                $ids = implode(',', $ids);

            echo FrmProAppHelper::export_xml($type, compact('ids'));
            die();
        }
    }
    
    function export_xml_direct($controller='forms', $ids=false){
        if(!current_user_can('frm_edit_forms')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        $is_template = FrmAppHelper::get_param('is_template', false);
        echo FrmProAppHelper::export_xml($controller, compact('ids', 'is_template'));
        die();
    }
    
    function insert_search_form($sort_str, $sdir_str, $search_str, $fid=false){ 
        FrmProAppHelper::search_form($sort_str, $sdir_str, $search_str, $fid);
    }
    
    function widget_text_filter( $content ){
        global $frm_app_controller;
    	$regex = '/\[\s*(display-frm-data|frm-stats|frm-graph|frm-entry-links|formresults)\s+.*\]/';
    	return preg_replace_callback( $regex, array($frm_app_controller, 'widget_text_filter_callback'), $content );
    }
    
    function standalone_route($controller, $action){
        if ($controller == 'fields'){
            if(!defined('DOING_AJAX'))
                define('DOING_AJAX', true);
                
            global $frmpro_fields_controller;
            if ($action == 'ajax_get_data')
                $frmpro_fields_controller->ajax_get_data(FrmAppHelper::get_param('entry_id'), FrmAppHelper::get_param('field_id'), FrmAppHelper::get_param('current_field'));
            else if ($action == 'ajax_data_options')
                $frmpro_fields_controller->ajax_data_options(FrmAppHelper::get_param('hide_field'), FrmAppHelper::get_param('entry_id'), FrmAppHelper::get_param('selected_field_id'), FrmAppHelper::get_param('field_id'));
            else if ($action == 'ajax_time_options')
                $frmpro_fields_controller->ajax_time_options();
        }else if ($controller == 'forms'){
            global $frmpro_forms_controller;
            if ($action == 'export')
                $frmpro_forms_controller->export_template(FrmAppHelper::get_param('id'));
            else if($action == 'import')
                $frmpro_forms_controller->import_templates();
        }else if ($controller == 'entries'){
            global $frmpro_entries_controller;
            if ($action == 'csv'){
                $s = isset($_REQUEST['s']) ? 's' : 'search';
                $frmpro_entries_controller->csv(FrmAppHelper::get_param('form'), FrmAppHelper::get_param($s), FrmAppHelper::get_param('fid'));
                unset($s);
            }else{
                if(!defined('DOING_AJAX'))
                    define('DOING_AJAX', true);
                    
                if($action == 'ajax_set_cookie')
                    $frmpro_entries_controller->set_cookie(FrmAppHelper::get_param('entry_id'), FrmAppHelper::get_param('form_id'));
                else if($action == 'edit_entry_ajax')
                    $frmpro_entries_controller->edit_entry_ajax(FrmAppHelper::get_param('id'), FrmAppHelper::get_param('entry_id', false), FrmAppHelper::get_param('post_id', false));
                else if($action == 'update_field_ajax')
                    $frmpro_entries_controller->update_field_ajax(FrmAppHelper::get_param('entry_id'), FrmAppHelper::get_param('field_id'), FrmAppHelper::get_param('value'));
                else if($action == 'send_email')
                    $frmpro_entries_controller->send_email(FrmAppHelper::get_param('entry_id'), FrmAppHelper::get_param('form_id'), FrmAppHelper::get_param('type'));
                else if($action == 'create')
                    $frmpro_entries_controller->ajax_create();
                else if($action == 'update')
                    $frmpro_entries_controller->ajax_update();
                else if($action == 'destroy')
                    $frmpro_entries_controller->ajax_destroy();
            }
        }else if($controller == 'settings'){
            global $frmpro_settings;
            if(!is_admin())
                $use_saved = true;
            
            include(FRMPRO_PATH .'/css/custom_theme.css.php');
        }
        
        if($action == 'xml')
            $this->export_xml_direct(FrmAppHelper::get_param('controller'), FrmAppHelper::get_param('ids'));
    }
    
    function install(){
        global $frmprodb;
        $frmprodb->upgrade();
    }
    
    function uninstall(){
        global $frmprodb;
        $frmprodb->uninstall();
    }
    
}

?>
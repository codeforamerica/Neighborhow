<?php
/**
 * @package Formidable
 */
 
class FrmFormsController{
    function FrmFormsController(){
        add_action('admin_menu', array( &$this, 'menu' ));
        add_action('admin_menu', array( &$this, 'lower_menu' ), 90);
        add_action('admin_head-toplevel_page_formidable', array(&$this, 'head'));
        add_action('wp_ajax_frm_form_name_in_place_edit', array(&$this, 'edit_name') );
        add_action('wp_ajax_frm_form_desc_in_place_edit', array(&$this, 'edit_description') );
        add_action('wp_ajax_frm_delete_form_wo_fields',array(&$this, 'destroy_wo_fields'));
        add_filter('frm_submit_button', array(&$this, 'submit_button_label'));
        add_filter('media_buttons_context', array(&$this, 'insert_form_button'));
        //add_action('media_buttons', array(&$this, 'show_form_button'), 20);
        add_action('admin_footer',  array(&$this, 'insert_form_popup'));
        
        add_filter('set-screen-option', array(&$this, 'save_per_page'), 10, 3);
    }
    
    function menu(){
        global $frm_settings;
        add_submenu_page('formidable', $frm_settings->menu .' | '. __('Forms', 'formidable'), __('Forms', 'formidable'), 'frm_view_forms', 'formidable', array(&$this, 'route'));
        add_submenu_page('formidable', $frm_settings->menu .' | '. __('Templates', 'formidable'), __('Templates', 'formidable'), 'frm_view_forms', 'formidable-templates', array(&$this, 'template_list'));
        
        add_action('admin_head-'. sanitize_title($frm_settings->menu) .'_page_formidable-new', array(&$this, 'head'));
        add_action('admin_head-'. sanitize_title($frm_settings->menu) .'_page_formidable-templates', array(&$this, 'head'));
        
        if(class_exists('WP_List_Table')){
            add_filter('manage_toplevel_page_formidable_columns', array( &$this, 'get_columns' ), 0 );
	        add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-templates_columns', array( &$this, 'get_columns' ), 0 );
	        add_filter('manage_toplevel_page_formidable_sortable_columns', array(&$this, 'get_sortable_columns'));
	        add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-templates_sortable_columns', array(&$this, 'get_sortable_columns'));
	        add_filter('get_user_option_managetoplevel_page_formidablecolumnshidden', array(&$this, 'hidden_columns'));
	        add_filter('get_user_option_manage'. sanitize_title($frm_settings->menu) .'_page_formidable-templatescolumnshidden', array(&$this, 'hidden_columns'));
        }
    }
    
    function lower_menu(){
        add_submenu_page('formidable', 'Formidable | '. __('Add New Form', 'formidable'), '<span style="display:none;">'. __('Add New Form', 'formidable') .'</span>', 'frm_edit_forms', 'formidable-new', array(&$this, 'new_form'));
    }
    
    function head(){
        global $frm_settings;

        $js_file  = array(FRM_URL . '/js/jquery/jquery-ui-themepicker.js', FRM_URL.'/js/jquery/jquery.editinplace.packed.js');
        require(FRM_VIEWS_PATH . '/shared/head.php');
    }
    
    function list_form(){
        $params = $this->get_params();
        $errors = apply_filters('frm_admin_list_form_action', array());
        return $this->display_forms_list($params, '', false, false, $errors);
    }
    
    function template_list(){
        $_POST['template'] = 1;
        $errors = apply_filters('frm_admin_list_form_action', array());
        return $this->display_forms_list();
    }
    
    function new_form(){
        global $frm_form, $frmpro_is_installed, $frm_ajax_url;
        
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $action = FrmAppHelper::get_param($action);
        if ($action == 'create'){
            return $this->create();
        }else if ($action == 'new'){
            $frm_field_selection = FrmFieldsHelper::field_selection();  
            $values = FrmFormsHelper::setup_new_vars();
            $id = $frm_form->create( $values );
            $values['id'] = $id;
            require(FRM_VIEWS_PATH.'/frm-forms/new.php');
        }else{
            $all_templates = $frm_form->getAll(array('is_template' => 1), 'name');
            require(FRM_VIEWS_PATH.'/frm-forms/new-selection.php');
        }
    }
    
    function create(){
        global $frm_entry, $frm_form, $frm_field, $frmpro_is_installed;
        $errors = $frm_form->validate($_POST);
        $id = (int)FrmAppHelper::get_param('id');
        
        if( count($errors) > 0 ){
            $hide_preview = true;
            $frm_field_selection = FrmFieldsHelper::field_selection();
            $record = $frm_form->getOne( $id );
            $fields = $frm_field->getAll(array('fi.form_id' => $id), 'field_order');
            $values = FrmAppHelper::setup_edit_vars($record, 'forms', $fields, true);
            require(FRM_VIEWS_PATH.'/frm-forms/new.php');
        }else{
            $record = $frm_form->update( $id, $_POST, true );
            die('<script type="text/javascript">window.location="'. admin_url('admin.php?page=formidable&frm_action=settings&id='. $id) .'"</script>');
            //$message = __('Form was Successfully Created', 'formidable');
            //return $this->settings($record, $message);
        }
    }
    
    function edit(){
        $id = FrmAppHelper::get_param('id');
        return $this->get_edit_vars($id);
    }
    
    function settings($id=false, $message=''){
        if(!$id or !is_numeric($id))
            $id = FrmAppHelper::get_param('id');
        return $this->get_settings_vars($id, '', $message);
    }
    
    function update_settings(){
        global $frm_form;
        
        $id = FrmAppHelper::get_param('id');
        $errors = $frm_form->validate($_POST);
        if( count($errors) > 0 ){
            return $this->get_settings_vars($id, $errors);
        }else{
            $record = $frm_form->update( $_POST['id'], $_POST );
            $message = __('Settings Successfully Updated', 'formidable');
            return $this->get_settings_vars($id, '', $message);
        }
    }
    
    function translate($action){
        global $frmpro_is_installed, $frm_form;
        $id = FrmAppHelper::get_param('id', false);
        $form = $frm_form->getOne($id);
        $values = (array)$form;
        include(FRM_VIEWS_PATH . '/frm-forms/translate.php');
    }
    
    function edit_name(){
        global $frm_form;
        $values = array('name' => trim($_POST['update_value']));
        $form = $frm_form->update($_POST['form_id'], $values);
        echo stripslashes($_POST['update_value']);  
        die();
    }

    function edit_description(){
        global $frm_form;
        $form = $frm_form->update($_POST['form_id'], array('description' => $_POST['update_value']));
        $description = stripslashes($_POST['update_value']);
        if(apply_filters('frm_use_wpautop', true))
            $description = wpautop($description);
        echo $description;
        die();
    }
    
    function update(){
        global $frm_form;
        $errors = $frm_form->validate($_POST);
        $id = FrmAppHelper::get_param('id');
        if( count($errors) > 0 ){
            return $this->get_edit_vars($id, $errors);
        }else{
            $record = $frm_form->update( $_POST['id'], $_POST );
            $message = __('Form was Successfully Updated', 'formidable');
            return $this->get_edit_vars($id, '', $message);
        }
    }
    
    function duplicate(){
        global $frm_form;
        
        $params = $this->get_params();
        $record = $frm_form->duplicate( $params['id'], $params['template'] );
        $message = ($params['template']) ? __('Form template was Successfully Created', 'formidable') : __('Form was Successfully Copied', 'formidable');
        if ($record)
            return $this->get_edit_vars($record, '', $message, true);
        else
            return $this->display_forms_list($params, __('There was a problem creating new template.', 'formidable'));
    }
    
    function page_preview(){
        global $frm_form;
        $params = $this->get_params();
        if (!$params['form']) return;
        $form = $frm_form->getOne($params['form']);
        if(!$form) return;
        return FrmEntriesController::show_form($form->id, '', true, true);
    }

    function preview(){
        do_action('frm_wp');
        
        global $frm_form, $frm_settings, $frmpro_is_installed;
        if ( !defined( 'ABSPATH' ) && !defined( 'XMLRPC_REQUEST' )) {
            global $wp;
            $root = dirname(dirname(dirname(dirname(__FILE__))));
            include_once( $root.'/wp-config.php' );
            $wp->init();
            $wp->register_globals();
        }
        
        if($frmpro_is_installed)
            FrmProEntriesController::register_scripts();
            
        header("Content-Type: text/html; charset=utf-8");

        $plugin     = FrmAppHelper::get_param('plugin');
        $controller = FrmAppHelper::get_param('controller');
        $key = (isset($_GET['form']) ? $_GET['form'] : (isset($_POST['form']) ? $_POST['form'] : ''));
        $form = $frm_form->getAll(array('form_key' => $key), '', 1);
        if (!$form) $form = $frm_form->getAll(array(), '', 1);
        
        require(FRM_VIEWS_PATH.'/frm-entries/direct.php');   
    }
    
    function destroy(){
        if(!current_user_can('frm_delete_forms')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
            
        global $frm_form;
        $params = $this->get_params();
        $message = '';
        if ($frm_form->destroy( $params['id'] ))
            $message = __('Form was Successfully Deleted', 'formidable');
        $this->display_forms_list($params, $message, '', 1);
    }
    
    function destroy_wo_fields(){
        global $frm_field, $frm_form, $frmdb;
        $id = $_POST['form_id'];
        if ($frmdb->get_count($frmdb->fields, array('form_id' => $id)) <= 0)
            $frm_form->destroy($id);
        die();
    }
    
    function submit_button_label($submit){
        if (!$submit or empty($submit)){ 
            global $frm_settings;
            $submit = $frm_settings->submit_value;
        }
        return $submit;
    }
    
    function insert_form_button($content){
        if(current_user_can('frm_view_forms'))
            $content .= '<a href="#TB_inline?width=450&height=550&inlineId=frm_insert_form" class="thickbox" title="' . __("Add Formidable Form", 'formidable') . '"><img src="'.FRM_IMAGES_URL.'/form_16.png" alt="' . __("Add Formidable Form", 'formidable') . '" /></a>';
        return $content;
    }
    
    function show_form_button($id){
        if($id != 'content')
            return;
        echo '<a href="#TB_inline?width=450&height=550&inlineId=frm_insert_form" class="thickbox" title="' . __("Add Formidable Form", 'formidable') . '"><img src="'. esc_url(FRM_IMAGES_URL.'/form_16.png'). '" alt="' . __("Add Formidable Form", 'formidable') . '" /></a>';
    }
    
    function insert_form_popup(){
        $page = basename($_SERVER['PHP_SELF']);
        if(in_array($page, array('post.php', 'page.php', 'page-new.php', 'post-new.php')) or (isset($_GET) and isset($_GET['page']) and $_GET['page'] == 'formidable-entry-templates')){
            if(class_exists('FrmProDisplay')){
                global $frmpro_display;
                $displays = $frmpro_display->getAll('', ' ORDER BY name');
            }
            require(FRM_VIEWS_PATH.'/frm-forms/insert_form_popup.php');   
        }
    }

    function display_forms_list($params=false, $message='', $page_params_ov = false, $current_page_ov = false, $errors = array()){
        global $wpdb, $frmdb, $frm_app_helper, $frm_form, $frm_entry, $frm_page_size, $frmpro_is_installed;
        
        if(!$params)
            $params = $this->get_params();
            
        if($message=='')
            $message = FrmAppHelper::frm_get_main_message();
            
        $page_params = '&action=0&&frm_action=0&page=formidable';
        
        if ($params['template']){
            $default_templates = $frm_form->getAll(array('default_template' => 1));
            $all_templates = $frm_form->getAll(array('is_template' => 1), 'name');
        }
        
        if(class_exists('WP_List_Table')){
            require_once( FRM_PATH .'/classes/helpers/FrmListHelper.php' );
            
            $args = array('table_name' => $frmdb->forms, 'params' => $params);
            $args['page_name'] = $params['template'] ? '-template' : '';
            $wp_list_table = new FrmListHelper($args);
            unset($args);

            $pagenum = $wp_list_table->get_pagenum();

            $wp_list_table->prepare_items();

            $total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
            if ( $pagenum > $total_pages && $total_pages > 0 ) {
            	wp_redirect( add_query_arg( 'paged', $total_pages ) );
            	exit;
            }
            
            if ( ! empty( $_REQUEST['s'] ) )
                $page_params .= '&s='. urlencode($_REQUEST['s']);
        }else{
  
            $where_clause = " (status is NULL OR status = '' OR status = 'published') AND default_template=0 AND is_template = ".$params['template'];

            $form_vars = $this->get_form_sort_vars($params, $where_clause);

            $current_page = ($current_page_ov) ? $current_page_ov : $params['paged'];
            $page_params .= ($page_params_ov) ? $page_params_ov : $form_vars['page_params'];

            $sort_str = $form_vars['sort_str'];
            $sdir_str = $form_vars['sdir_str'];
            $search_str = $form_vars['search_str'];

            $record_count = $frm_app_helper->getRecordCount($form_vars['where_clause'], $frmdb->forms);
            $page_count = $frm_app_helper->getPageCount($frm_page_size, $record_count, $frmdb->forms);
            $forms = $frm_app_helper->getPage($current_page, $frm_page_size, $form_vars['where_clause'], $form_vars['order_by'], $frmdb->forms);
            $page_last_record = $frm_app_helper->getLastRecordNum($record_count,$current_page,$frm_page_size);
            $page_first_record = $frm_app_helper->getFirstRecordNum($record_count,$current_page,$frm_page_size);
        }
        
        require(FRM_VIEWS_PATH.'/frm-forms/list.php');
    }
    
    function get_form_sort_vars($params,$where_clause = ''){
        $order_by = '';
        $page_params = '';

        // These will have to work with both get and post
        $sort_str = $params['sort'];
        $sdir_str = $params['sdir'];
        $search_str = $params['search'];

        // Insert search string
        if(!empty($search_str)){
            $search_params = explode(" ", $search_str);

            foreach($search_params as $search_param){
                if(!empty($where_clause))
                    $where_clause .= " AND";

                $where_clause .= " (name like '%$search_param%' OR description like '%$search_param%' OR created_at like '%$search_param%')";
            }

            $page_params .="&search=$search_str";
        }

        // make sure page params stay correct
        if(!empty($sort_str))
            $page_params .="&sort=$sort_str";

        if(!empty($sdir_str))
            $page_params .= "&sdir=$sdir_str";

        // Add order by clause
        switch($sort_str){
            case "id":
            case "name":
            case "description":
            case "form_key":
                $order_by .= " ORDER BY $sort_str";
                break;
            default:
                $order_by .= " ORDER BY name";
        }

        // Toggle ascending / descending
        if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'asc'){
            $order_by .= ' ASC';
            $sdir_str = 'asc';
        }else{
            $order_by .= ' DESC';
            $sdir_str = 'desc';
        }

        return compact('order_by', 'sort_str', 'sdir_str', 'search_str', 'where_clause', 'page_params');
    }
    
    function get_columns($columns){
	    $columns['cb'] = '<input type="checkbox" />';
        $columns['id'] = 'ID';
        $columns['name'] = __('Name', 'formidable');
        $columns['description'] = __('Description', 'formidable');
        $columns['form_key'] = __('Key', 'formidable');
        
        if($_GET['page'] == 'formidable-templates'){
            add_screen_option( 'per_page', array('label' => __('Templates', 'formidable'), 'default' => 10, 'option' => 'formidable_page_formidable_templates_per_page') );
        }else{
            $columns['entries'] = __('Entries', 'formidable');
            $columns['link'] = __('Direct Link', 'formidable');
            $columns['shortcode'] = __('ShortCodes', 'formidable');
            add_screen_option( 'per_page', array('label' => __('Forms', 'formidable'), 'default' => 20, 'option' => 'formidable_page_formidable_per_page') );
        }
        
        $columns['created_at'] = __('Date', 'formidable');
        
        return $columns;
	}
	
	function get_sortable_columns() {
		return array(
		    'id'        => 'id',
			'name'      => 'name',
			'description'   => 'description',
			'form_key'   => 'form_key',
			'created_at' => 'created_at'
		);
	}
	
	function hidden_columns($result){
        $return = false;
        foreach((array)$result as $r){
            if(!empty($r)){
                $return = true;
                break;
            }
        }
        
        if($return)
            return $result;

        $result[] = 'created_at';
        if($_GET['page'] == 'formidable-templates'){
            $result[] = 'id';
            $result[] = 'form_key';
        } 
               
        return $result;
    }
	
	function save_per_page($save, $option, $value){
        if($option == 'formidable_page_formidable_per_page' or $option == 'formidable_page_formidable_templates_per_page')
            $save = (int)$value;
        return $save;
    }

    function get_edit_vars($id, $errors = '', $message='', $create_link=false){
        global $frm_entry, $frm_form, $frm_field, $frmpro_is_installed, $frm_ajax_url;
        $record = $frm_form->getOne( $id );
        $frm_field_selection = FrmFieldsHelper::field_selection();
        $fields = $frm_field->getAll(array('fi.form_id' => $record->id), 'field_order');
        $values = FrmAppHelper::setup_edit_vars($record, 'forms', $fields, true);
        
        $edit_message = __('Form was Successfully Updated', 'formidable');
        if ($values['is_template'] and $message == $edit_message)
            $message = __('Template was Successfully Updated', 'formidable');
        
        if (isset($values['default_template']) && $values['default_template'])
            wp_die(__('That template cannot be edited', 'formidable'));
        else if($create_link)
            require(FRM_VIEWS_PATH.'/frm-forms/new.php');
        else
            require(FRM_VIEWS_PATH.'/frm-forms/edit.php');
    }
    
    function get_settings_vars($id, $errors = '', $message=''){
        global $frm_entry, $frm_form, $frm_field, $frmpro_is_installed, $frm_ajax_url;
        $record = $frm_form->getOne( $id );
        $fields = $frm_field->getAll(array('fi.form_id' => $id), 'field_order');
        $values = FrmAppHelper::setup_edit_vars($record, 'forms', $fields, true);
        $sections = apply_filters('frm_add_form_settings_section', array(), $values);
        $pro_feature = ($frmpro_is_installed) ? '' : ' class="pro_feature"';
        if (isset($values['default_template']) && $values['default_template'])
            wp_die(__('That template cannot be edited', 'formidable'));
        else
            require(FRM_VIEWS_PATH.'/frm-forms/settings.php');
    }
    
    function get_params(){
        $values = array();
        foreach (array('template' => 0, 'id' => '', 'paged' => 1, 'form' => '', 'search' => '', 'sort' => '', 'sdir' => '') as $var => $default)
            $values[$var] = FrmAppHelper::get_param($var, $default);

        return $values;
    }
    
    function add_default_templates($path, $default=true, $template=true){
        global $frm_form, $frm_field;
        $templates = glob($path."/*.php");
        
        for($i = count($templates) - 1; $i >= 0; $i--){
            $filename = str_replace('.php', '', str_replace($path.'/', '', $templates[$i]));
            $template_query = array('form_key' => $filename);
            if($template) $template_query['is_template'] = 1;
            if($default) $template_query['default_template'] = 1;
            $form = $frm_form->getAll($template_query, '', 1);
            
            $values = FrmFormsHelper::setup_new_vars();
            $values['form_key'] = $filename;
            $values['is_template'] = $template;
            $values['status'] = 'published';
            if($default) $values['default_template'] = 1;
            
            include_once($templates[$i]);
        }
    }

    function route(){
        $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
        $action = FrmAppHelper::get_param($action);
        
        if($action == 'new')
            return $this->new_form();
        else if($action == 'create')
            return $this->create();
        else if($action == 'edit')
            return $this->edit();
        else if($action == 'update')
            return $this->update();
        else if($action == 'duplicate')
            return $this->duplicate();
        else if($action == 'destroy')
            return $this->destroy();
        else if($action == 'list-form')
            return $this->list_form(); 
        else if($action == 'settings')
            return $this->settings();
        else if($action == 'update_settings')
            return $this->update_settings();
        else if($action == 'translate' or $action == 'update_translate')
            return $this->translate($action);
        else{
            $action = FrmAppHelper::get_param('action');
            if($action == -1)
                $action = FrmAppHelper::get_param('action2');
                
            if(strpos($action, 'bulk_') === 0){
                if(isset($_GET) and isset($_GET['action']))
                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action'], '', $_SERVER['REQUEST_URI']);
                if(isset($_GET) and isset($_GET['action2']))
                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);
                    
                return $this->list_form();
            }else{
                return $this->display_forms_list();
            }
        }
    }

}
?>
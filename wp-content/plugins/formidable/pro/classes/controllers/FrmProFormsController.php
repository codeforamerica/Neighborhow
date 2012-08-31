<?php

class FrmProFormsController{
    function FrmProFormsController(){
        add_filter('frm_admin_list_form_action', array(&$this, 'process_bulk_form_actions'));
        add_action('frm_additional_form_options', array(&$this, 'add_form_options'));
        add_action('frm_additional_form_notification_options', array(&$this, 'notifications'));
        add_action('wp_ajax_frm_add_postmeta_row', array(&$this, '_postmeta_row'));
        add_action('wp_ajax_frm_add_posttax_row', array(&$this, '_posttax_row'));
        add_action('frm_extra_form_instructions', array(&$this, 'instructions'));
        add_action('frm_translation_page', array(&$this, 'translate'), 10, 2);
        add_filter('get_frm_stylesheet', array(&$this, 'custom_stylesheet'), 10, 2);
        add_action('frm_template_action_links', array(&$this, 'template_action_links'));
        add_filter('frmpro_field_links', array(&$this, 'add_field_link'), 10, 3);
        add_filter('frm_drag_field_class', array(&$this, 'drag_field_class'));
        add_action('formidable_shortcode_atts', array(&$this, 'formidable_shortcode_atts'));
        add_filter('frm_content', array(&$this, 'filter_content'), 10, 3);
        add_filter('frm_submit_button', array(&$this, 'submit_button_label'), 5, 2);
        add_filter('frm_error_icon', array(&$this, 'error_icon'));
    }
    
    function process_bulk_form_actions($errors){
        if(!isset($_POST)) return;
        global $frm_form;
        
        $bulkaction = FrmAppHelper::get_param('action');
        if($bulkaction == -1)
            $bulkaction = FrmAppHelper::get_param('action2');

        if(!empty($bulkaction) and strpos($bulkaction, 'bulk_') === 0){
            if(isset($_GET) and isset($_GET['action']))
                $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action'], '', $_SERVER['REQUEST_URI']);
            if(isset($_GET) and isset($_GET['action2']))
                $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);
            
            $bulkaction = str_replace('bulk_', '', $bulkaction);
        }else{
            $bulkaction = '-1';
            if(isset($_POST['bulkaction']) and $_POST['bulkaction'] != '-1')
                $bulkaction = $_POST['bulkaction'];
            else if(isset($_POST['bulkaction2']) and $_POST['bulkaction2'] != '-1')
                $bulkaction = $_POST['bulkaction2'];
        }

        $ids = FrmAppHelper::get_param('item-action', '');
        if (empty($ids)){
            $errors[] = __('No forms were specified', 'formidable');
        }else{                
            if($bulkaction == 'export'){
                $controller = 'forms';
                if(is_array($ids))
                    $ids = implode(',', $ids);
                
                if(isset($_GET['page']) and $_GET['page'] == 'formidable-templates')
                    $is_template = true;
                include_once(FRMPRO_VIEWS_PATH.'/shared/xml.php');
            }else{
                if(!current_user_can('frm_delete_forms')){
                    global $frm_settings;
                    $errors[] = $frm_settings->admin_permission;
                }else{
                    if(!is_array($ids))
                        $ids = explode(',', $ids);
                        
                    if(is_array($ids)){
                        if($bulkaction == 'delete'){
                            foreach($ids as $form_id)
                                $frm_form->destroy($form_id);
                        }
                    }
                }
            }
        }
        return $errors;
    }
    
    function add_form_options($values){ 
        global $frm_ajax_url;
        $editable_roles = get_editable_roles();
        $post_types = FrmProAppHelper::get_custom_post_types();
        $show_post_type = false;
        if(isset($values['fields']) and $values['fields']){
            foreach($values['fields'] as $field){
                if(!$show_post_type and $field['post_field'] != '')
                    $show_post_type = true;
            }
        }
        
        require(FRMPRO_VIEWS_PATH.'/frmpro-forms/add_form_options.php');
    }
    
    function notifications($values){
        global $wpdb, $frmdb;
        require(FRMPRO_VIEWS_PATH.'/frmpro-forms/notifications.php');
    }
    
    function post_options($values){
        global $frm_ajax_url;
        
        $post_types = FrmProAppHelper::get_custom_post_types();
        if(!$post_types) return;
        
        $post_type = FrmProForm::post_type($values);
        if(function_exists('get_object_taxonomies'))
            $taxonomies = get_object_taxonomies($post_type);
        
        $echo = true;
        $show_post_type = false;
        if(isset($values['fields']) and $values['fields']){
            foreach($values['fields'] as $field){
                if(!$show_post_type and $field['post_field'] != '')
                    $show_post_type = true;
            }
        }
        
        if($show_post_type)
            $values['create_post'] = true;
            
        $form_id = (int)$_GET['id'];
        $display = FrmProDisplay::getAll("form_id={$form_id} and show_count in ('single', 'dynamic', 'calendar')", '', ' LIMIT 1' );
            
        require(FRMPRO_VIEWS_PATH.'/frmpro-forms/post_options.php');
    }
    
    function _postmeta_row(){
        $custom_data = array('meta_name' => $_POST['meta_name'], 'field_id' => '');
        $values = array();
        
        if(isset($_POST['form_id']))
            $values['fields'] = FrmField::getAll("fi.form_id='$_POST[form_id]' and fi.type not in ('divider', 'html', 'break', 'captcha')", ' ORDER BY field_order');
        $echo = false;
        include(FRMPRO_VIEWS_PATH.'/frmpro-forms/_custom_field_row.php');
        die();
    }
    
    function _posttax_row(){
        $field_vars = array('meta_name' => '', 'field_id' => '', 'show_exclude' => 0, 'exclude_cat' => 0);
        $post_type = $_POST['post_type'];
        $tax_key = $_POST['meta_name'];
        
        if($post_type and function_exists('get_object_taxonomies'))
            $taxonomies = get_object_taxonomies($post_type);
        
        $values = array();
        
        if(isset($_POST['form_id'])){
            $values['fields'] = FrmField::getAll("fi.form_id='$_POST[form_id]' and fi.type in ('checkbox', 'radio', 'select', 'tag', 'data')", ' ORDER BY field_order');
            $values['id'] = $_POST['form_id'];
        }
        $echo = false;
        include(FRMPRO_VIEWS_PATH.'/frmpro-forms/_post_taxonomy_row.php');
        die();
    }
    
    function instructions(){
        $tags = array(
            'date' => __('Current Date', 'formidable'), 
            'time' => __('Current Time', 'formidable'), 
            'email' => __('User Email', 'formidable'), 
            'login' => __('User Login', 'formidable'), 
            'display_name' => __('User Display Name', 'formidable'), 
            'first_name' => __('User First Name', 'formidable'), 
            'last_name' => __('User Last Name', 'formidable'), 
            'user_id' => __('User ID', 'formidable'), 
            'user_meta key=whatever' => __('User Meta', 'formidable'), 
            'post_id' => __('Post ID', 'formidable'), 
            'post_title' => __('Post Title', 'formidable'),
            'post_author_email' => __('Author Email', 'formidable'),
            'post_meta key=whatever' => __('Post Meta', 'formidable'),
            'ip' => __('Client IP Address', 'formidable'), 
            'auto_id start=1' => __('Auto Increment', 'formidable'), 
            'get param=whatever' => __('GET/POST variable', 'formidable')
        );
        require_once(FRMPRO_VIEWS_PATH.'/frmpro-forms/instructions.php');
    }
    
    function translate($form, $action){
        global $frm_field, $wpdb, $sitepress;
        
        if(!function_exists('icl_t')){
            _e('You do not have WPML installed', 'formidable');
            return;
        }
        
        if($action == 'update_translate' and isset($_POST) and isset($_POST['frm_wpml'])){
            foreach($_POST['frm_wpml'] as $tkey => $t){
                $st = array('value' => $t['value']);
                $st['status'] = (isset($t['status'])) ? $t['status'] : ICL_STRING_TRANSLATION_NOT_TRANSLATED;
                
                if(is_numeric($tkey)){
                    $wpdb->update("{$wpdb->prefix}icl_string_translations", $st, array('id' => $tkey));
                }else if(!empty($t['value'])){
                    $info = explode('_', $tkey);
                    if(!is_numeric($info[0]))
                        continue;
                        
                    $st['string_id'] = $info[0];
                    $st['language']  = $info[1];
                    $st['translator_id'] = get_current_user_id();
                    $st['translation_date'] = current_time('mysql');
 
                    $wpdb->insert("{$wpdb->prefix}icl_string_translations", $st);
                }
                unset($t);
                unset($tkey);
            }
        }
        
        $id = $form->id;
        $langs = $sitepress->get_active_languages();
        ksort($langs);
        $lang_count = (count($langs)-1);
        
        if(class_exists('FormidableWPML')){
            $formidable_wpml = new FormidableWPML();
            $formidable_wpml->get_translatable_items(array(), 'formidable', '');
        }
        
        $strings = $wpdb->get_results("SELECT id, name, value, language FROM {$wpdb->prefix}icl_strings
            WHERE context='formidable' AND name LIKE '{$id}_%' ORDER BY name DESC", OBJECT_K
        );

        if($strings){
            $translations = $wpdb->get_results("SELECT id, string_id, value, status, language 
                FROM {$wpdb->prefix}icl_string_translations WHERE string_id in (". implode(',', array_keys($strings)).") 
                ORDER BY language ASC"
            );
            $base_lang = reset($strings)->language;
            $col_order = array($base_lang);
        }else{
            $base_lang = reset($langs);
        }
        
        $fields = $frm_field->getAll(array('fi.form_id' => $id), 'field_order');
        $values = FrmAppHelper::setup_edit_vars($form, 'forms', $fields, true);
        
        include(FRMPRO_VIEWS_PATH . '/frmpro-forms/translate.php');
    }
    
    function custom_stylesheet($previous_css, $location='header'){
        global $frmpro_settings, $frm_datepicker_loaded, $frm_css_loaded;
        $uploads = wp_upload_dir();
        $css_file = array();
        
        if(!$frm_css_loaded){
            //include css in head
            if(is_readable($uploads['basedir'] .'/formidable/css/formidablepro.css')){
                if(is_ssl() and !preg_match('/^https:\/\/.*\..*$/', $uploads['baseurl']))
                    $uploads['baseurl'] = str_replace('http://', 'https://', $uploads['baseurl']);
                $css_file[] = $uploads['baseurl'] .'/formidable/css/formidablepro.css';
            }else
                $css_file[] = FRM_SCRIPT_URL . '&amp;controller=settings';
        }

        if($frm_datepicker_loaded)
            $css_file[] = FrmProAppHelper::jquery_css_url($frmpro_settings->theme_css);

        return $css_file;
    }
    
    function template_action_links($form){
        echo '| <span><a href="'.FRM_SCRIPT_URL.'&controller=forms&frm_action=export&id='. $form->id .'" title="'. __('Export Template', 'formidable') . ' '. $form->name .'">'. __('Export Template', 'formidable') .'</a></span>';
    }
    
    function export_template($form_id){
        if(current_user_can('frm_edit_forms')){
            global $frmdb, $frm_form, $frm_field, $current_user, $frm_settings, $frmpro_settings;
            $form = $frm_form->getOne($form_id);
            $form->options = maybe_unserialize($form->options);
            $fields = $frm_field->getAll(array('fi.form_id' => $form_id));
            
            require(FRMPRO_VIEWS_PATH.'/frmpro-forms/export_template.php');
        }else{
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
    }
    
    function import_templates(){
        if(current_user_can('frm_edit_forms')){
            global $frmpro_settings;
            FrmFormsController::add_default_templates($frmpro_settings->template_path, false);
        }else{
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
    }
    
    function add_field_link($field_type, $id, $field_key){
        return "<a href=\"javascript:add_frm_field_link($id,'$field_key');\">$field_type</a>";
    }
    
    function drag_field_class($class){
        return ' class="field_type_list"';
    }
    
    function formidable_shortcode_atts($atts){
        global $frm_readonly, $frm_editing_entry, $frm_show_fields, $frmdb;
        $frm_readonly = $atts['readonly'];
        $frm_editing_entry = false;
        
        if(!is_array($atts['fields']))
            $frm_show_fields = explode(',', $atts['fields']);
        else
            $frm_show_fields = array();
            
        if($atts['entry_id'] == 'last'){
            global $user_ID, $frm_entry_meta;
            if($user_ID){
                $where_meta = array('form_id' => $atts['id'], 'user_id' => $user_ID);
                $frm_editing_entry = $frmdb->get_var($frmdb->entries, $where_meta, 'id', 'created_at DESC');
            }
        }else if($atts['entry_id']){
            $frm_editing_entry = $atts['entry_id'];
        }
    }
    
    function filter_content($content, $form, $entry=false){
        if($entry and is_numeric($entry)){
            global $frm_entry;
            $entry = $frm_entry->getOne($entry);
        }else{
            $entry_id = (isset($_POST) and isset($_POST['id'])) ? $_POST['id'] : false;
            if($entry_id){
                global $frm_entry;
                $entry = $frm_entry->getOne($entry_id);
            }
        }
        if(!$entry) return $content;
        if(is_object($form))
            $form = $form->id;
        $shortcodes = FrmProAppHelper::get_shortcodes($content, $form);
        $content = FrmProFieldsHelper::replace_shortcodes($content, $entry, $shortcodes);
        return $content;
    }
    
    function submit_button_label($submit, $form){
        global $frm_next_page;
        if(isset($frm_next_page[$form->id])) 
            $submit = $frm_next_page[$form->id];
        return $submit;
    }
    
    function error_icon(){
        global $frmpro_settings;
        $icon = FRMPRO_ICONS_URL .'/'. $frmpro_settings->error_icon;
        return $icon;
    }
}

?>
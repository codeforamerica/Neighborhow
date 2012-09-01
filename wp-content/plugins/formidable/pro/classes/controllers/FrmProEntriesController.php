<?php

class FrmProEntriesController{
    
    function FrmProEntriesController(){
        add_action('admin_menu', array( &$this, 'menu' ), 20);
        add_filter('frm_nav_array', array( &$this, 'frm_nav'), 1);
        add_action('admin_init', array(&$this, 'admin_js'), 1);
        add_action('init', array(&$this, 'register_scripts'));
        add_action('wp_enqueue_scripts', array(&$this, 'add_js'));
        add_action('wp_footer', array(&$this, 'footer_js'), 1);
        add_action('admin_footer', array(&$this, 'footer_js'));
        add_filter('update_user_metadata', array(&$this, 'check_hidden_cols'), 10, 5);
        add_action('updated_user_meta', array(&$this, 'update_hidden_cols'), 10, 4);
        add_filter('set-screen-option', array(&$this, 'save_per_page'), 10, 3);
        add_action('frm_before_table', array( &$this, 'before_table'), 10, 2);
        add_action('wp_ajax_frm_import_csv', array( &$this, 'import_csv_entries') );
        add_action('frm_process_entry', array(&$this, 'process_update_entry'), 10, 3);
        add_action('frm_display_form_action', array(&$this, 'edit_update_form'), 10, 5);
        add_action('frm_submit_button_action', array($this, 'ajax_submit_button'), 10, 2);
        add_filter('frm_success_filter', array(&$this, 'get_confirmation_method'), 10, 2);
        add_action('frm_success_action', array(&$this, 'confirmation'), 10, 4);
        add_action('deleted_post', array(&$this, 'delete_entry'));
        add_action('add_meta_boxes', array( &$this, 'create_entry_from_post_box'), 10, 2);
        add_action('wp_ajax_frm_create_post_entry', array( &$this, 'create_post_entry'));
        
        add_filter('frmpro_fields_replace_shortcodes', array(&$this, 'filter_shortcode_value'), 10, 4);
        add_filter('frm_display_value_custom', array(&$this, 'filter_display_value'), 10, 2);
        
        //Shortcodes
        add_shortcode('formresults', array(&$this, 'get_form_results'));
        add_shortcode('frm-search', array(&$this, 'get_search'));
        add_shortcode('frm-entry-links', array(&$this, 'entry_link_shortcode'));
        add_shortcode('frm-entry-edit-link', array(&$this, 'entry_edit_link'));
        add_shortcode('frm-entry-update-field', array(&$this, 'entry_update_field'));
        add_shortcode('frm-entry-delete-link', array(&$this, 'entry_delete_link'));
    }
    
    function menu(){
        global $frm_settings;
        if(current_user_can('administrator') and !current_user_can('frm_view_entries')){
            global $wp_roles;
            $frm_roles = FrmAppHelper::frm_capabilities();
            foreach($frm_roles as $frm_role => $frm_role_description){
                if(!in_array($frm_role, array('frm_view_forms', 'frm_edit_forms', 'frm_delete_forms', 'frm_change_settings')))
                    $wp_roles->add_cap( 'administrator', $frm_role );
            }
        }
        add_submenu_page('formidable', $frm_settings->menu .' | '. __('Form Entries', 'formidable'), __('Form Entries', 'formidable'), 'frm_view_entries', 'formidable-entries', array(&$this, 'route'));
        
        if(class_exists('WP_List_Table') and (!isset($_GET['frm_action']) or !in_array($_GET['frm_action'], array('edit', 'show')))){
            add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-entries_columns', array(&$this, 'manage_columns'));
            add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-entries_sortable_columns', array(&$this, 'sortable_columns'));
            add_filter('get_user_option_manage'. sanitize_title($frm_settings->menu) .'_page_formidable-entriescolumnshidden', array(&$this, 'hidden_columns'));
        }
        //add_filter( 'bulk_actions-' . sanitize_title($frm_settings->menu) .'_page_formidable-entries', array(&$this, 'bulk_action_options'));
        add_action('admin_head-'. sanitize_title($frm_settings->menu) .'_page_formidable-entries', array(&$this, 'head'));
    }
    
    function frm_nav($nav){
        if(current_user_can('frm_view_entries'))
            $nav['formidable-entries'] = __('Entries', 'formidable');
        //if(current_user_can('frm_create_entries'))
        //    $nav['formidable-entries&frm_action=new'] = __('Add New Entry', 'formidable');
        return $nav;
    }
    
    function head(){
        global $frmpro_settings;
        $css_file = array(FrmProAppHelper::jquery_css_url($frmpro_settings->theme_css));
        
        require(FRM_VIEWS_PATH . '/shared/head.php');
    }
    
    function admin_js(){
        if (isset($_GET) and isset($_GET['page']) and ($_GET['page'] == 'formidable-entries' or $_GET['page'] == 'formidable-entry-templates' or $_GET['page'] == 'formidable-import')){
            
            if(!function_exists('wp_editor')){
                    add_action( 'admin_print_footer_scripts', 'wp_tiny_mce', 25 );
                add_filter('tiny_mce_before_init', array(&$this, 'remove_fullscreen'));
                if ( user_can_richedit() ){
            	    wp_enqueue_script('editor');
            	    wp_enqueue_script('media-upload');
            	}
            	wp_enqueue_script('common');
            	wp_enqueue_script('post');
        	}
        	if($_GET['page'] == 'formidable-entries')
        	    wp_enqueue_script('jquery-ui-datepicker');
        }
    }
    
    function remove_fullscreen($init){
        if(isset($init['plugins'])){
            $init['plugins'] = str_replace('wpfullscreen,', '', $init['plugins']);
            $init['plugins'] = str_replace('fullscreen,', '', $init['plugins']);
        }
        return $init;
    }
    
    function register_scripts(){
        global $wp_scripts;
        wp_register_script('jquery-frm-rating', FRMPRO_URL . '/js/jquery.rating.min.js', array('jquery'), '3.13', true);
        wp_register_script('jquery-star-metadata', FRMPRO_URL . '/js/jquery.MetaData.js', array('jquery'), '', true);
        wp_register_script('jquery-maskedinput', FRMPRO_URL . '/js/jquery.maskedinput.min.js', array('jquery'), '1.3', true);
        wp_register_script('nicedit', FRMPRO_URL . '/js/nicedit.js', array(), '', true);
        wp_register_script('jquery-frmtimepicker', FRMPRO_URL . '/js/jquery.timePicker.min.js', array('jquery'), '0.3', true);

        //jquery-ui-datepicker registered in WP 3.3
        if(!isset($wp_scripts->registered) or !isset( $wp_scripts->registered['jquery-ui-datepicker'])){
            $date_ver = FrmProAppHelper::datepicker_version();
            wp_register_script('jquery-ui-datepicker', FRMPRO_URL . '/js/jquery.ui.datepicker'. $date_ver .'.js', array('jquery', 'jquery-ui-core'), empty($date_ver) ? '1.8.16' : trim($date_ver, '.'), true);
        }
    }
    
    function add_js(){        
        if(is_admin())
            return;
         
        wp_enqueue_script('jquery-ui-core');
        
        global $frm_settings;
        if($frm_settings->accordion_js){
            wp_enqueue_script('jquery-ui-widget');
            wp_enqueue_script('jquery-ui-accordion', FRMPRO_URL.'/js/jquery.ui.accordion.js', array('jquery', 'jquery-ui-core'), '1.8.16', true);
        }
    }
    
    function footer_js(){
        global $frm_rte_loaded, $frm_datepicker_loaded, $frm_timepicker_loaded, $frm_star_loaded;
        global $frm_hidden_fields, $frm_forms_loaded, $frm_calc_fields, $frm_rules, $frm_input_masks;
        
        if(empty($frm_forms_loaded))
            return;
            
        $form_ids = '';
        foreach($frm_forms_loaded as $form){
            if(!is_object($form))
                continue;
                
            if($form_ids != '')
                $form_ids .= ',';
            $form_ids .= '#form_'. $form->form_key;
        }
        
        $scripts = array('formidable');
        
        if(!empty($frm_rte_loaded))
            $scripts[] = 'nicedit';

        if(!empty($frm_datepicker_loaded))
            $scripts[] = 'jquery-ui-datepicker';
            
        if(!empty($frm_timepicker_loaded))
            $scripts[] = 'jquery-frmtimepicker';

        if($frm_star_loaded){ 
            $scripts[] = 'jquery-frm-rating';

            if(is_array($frm_star_loaded) and in_array('split', $frm_star_loaded))
                $scripts[] = 'jquery-star-metadata'; //needed for spliting stars
        }
        
        $frm_input_masks = apply_filters('frm_input_masks', $frm_input_masks, $frm_forms_loaded);
        if(!empty($frm_input_masks)) 
            $scripts[] = 'jquery-maskedinput';
        
        if(!empty($scripts)){
            global $wp_scripts;
            $wp_scripts->do_items( $scripts );
        }
        
        unset($scripts);
        
        include_once(FRMPRO_VIEWS_PATH.'/frmpro-entries/footer_js.php');
    }
    
    function before_table($footer, $form_id=false){
        FrmProEntriesHelper::before_table($footer, $form_id);
    }
    
    /* Back End CRUD */
    function show($id = false){
        if(!current_user_can('frm_view_entries'))
            wp_die('You are not allowed to view entries');
            
        global $frm_entry, $frm_field, $frm_entry_meta, $user_ID;
        if(!$id)
            $id = FrmAppHelper::get_param('id');
        if(!$id)
            $id = FrmAppHelper::get_param('item_id');
        
        $entry = $frm_entry->getOne($id, true);
        $data = maybe_unserialize($entry->description);
        if(!is_array($data) or !isset($data['referrer']))
            $data = array('referrer' => $data);
        

        $fields = $frm_field->getAll("fi.type not in ('captcha','html') and fi.form_id=". (int)$entry->form_id, 'fi.field_order');
        $date_format = get_option('date_format');
        $time_format = get_option('time_format');
        $show_comments = true;
        
        if(isset($_POST) and isset($_POST['frm_comment']) and !empty($_POST['frm_comment'])){
            FrmEntryMeta::add_entry_meta($_POST['item_id'], 0, '', serialize(array('comment' => $_POST['frm_comment'], 'user_id' => $user_ID)));
            //send email notifications
        }
        
        if($show_comments){
            $comments = $frm_entry_meta->getAll("item_id=$id and field_id=0", ' ORDER BY it.created_at ASC');
            $to_emails = apply_filters('frm_to_email', array(), $entry, $entry->form_id);
        }
            
        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/show.php');
    }
    
    function list_entries(){
        $params = $this->get_params();
        return $this->display_list($params);
    }
    
    function new_entry(){
        global $frm_form;
        if($form_id = FrmAppHelper::get_param('form')){
            $form = $frm_form->getOne($form_id);
            $this->get_new_vars('', $form); 
        }else
             require(FRMPRO_VIEWS_PATH.'/frmpro-entries/new-selection.php'); 
    }
    
    function create(){
        global $frm_form, $frm_entry;
        
        $params = $this->get_params();
        if($params['form'])
            $form = $frm_form->getOne($params['form']);
            
        $errors = $frm_entry->validate($_POST);

        if( count($errors) > 0 ){
            $this->get_new_vars($errors, $form);
        }else{
            if (isset($_POST['frm_page_order_'.$form->id])){
                $this->get_new_vars('', $form); 
            }else{
                $_SERVER['REQUEST_URI'] = str_replace('&frm_action=new', '', $_SERVER['REQUEST_URI']);
                $record = $frm_entry->create( $_POST );

                if ($record)
                    $message = __('Entry was Successfully Created', 'formidable');
                $this->display_list($params, $message, '', 1);
            }
        }
    }
    
    function edit(){
        $id = FrmAppHelper::get_param('id');
        return $this->get_edit_vars($id);
    }
    
    
    function update(){
        global $frm_entry;
        $message = '';
        $errors = $frm_entry->validate($_POST);
        $id = FrmAppHelper::get_param('id');

        if( empty($errors) ){
            if (isset($_POST['form_id']) and isset($_POST['frm_page_order_'. $_POST['form_id']])){
                return $this->get_edit_vars($id);
            }else{
                $record = $frm_entry->update( $id, $_POST );
                //if ($record)
                $message = __('Entry was Successfully Updated', 'formidable') . "<br/> <a href='?page=formidable-entries&form=". $_POST['form_id'] ."'>&larr; ". __('Back to Entries', 'formidable') ."</a>";
            }
        }
        
        return $this->get_edit_vars($id,$errors,$message);
    }
    
    function import(){
        global $frm_field;
        
        if(!current_user_can('frm_create_entries'))
            wp_die($frm_settings->admin_permission);
            
        $step = FrmAppHelper::get_param('step', 'One');
        $csv_del = FrmAppHelper::get_param('csv_del', ',');
        $form_id = FrmAppHelper::get_param('form_id');
        
        if($step != 'One'){
            if($step == 'Two'){
                //validate 
                if(empty($_POST['form_id']) or (empty($_POST['csv']) and (!isset($_FILES) or !isset($_FILES['csv']) or empty($_FILES['csv']['name']) or (int)$_FILES['csv']['size'] <= 0))){
                    $errors = array(__('All Fields are required', 'formidable'));
                    $step = 'One';
                }else{
                    
                    //upload
                    $media_id = ($_POST['csv'] and is_numeric($_POST['csv'])) ? $_POST['csv'] : FrmProAppHelper::upload_file('csv');
                    if($media_id and !is_wp_error($media_id)){
                        $current_path = get_attached_file($media_id);
                        $row = 1;
                        
                        $headers = $example = '';

                        
                        if (($f = fopen($current_path, "r")) !== FALSE) {
                            $row = 0;
                            while (($data = fgetcsv($f, 100000, $csv_del)) !== FALSE) {
                                $row++;
                                
                                if($row == 1)
                                    $headers = $data;
                                else if($row == 2)
                                    $example = $data;
                                else
                                    continue;
                            }
                            fclose($f);
                        }
                        
                        
                        $fields = $frm_field->getAll("fi.type not in ('break','divider','captcha','html') and fi.form_id=". (int)$form_id, 'fi.field_order');
                        
                    }else if(is_wp_error($media_id)){
                        echo $media_id->get_error_message();
                        $step = 'One';
                    }
                }
            }else if($step == 'import'){
                global $frm_ajax_url;
                //IMPORT NOW
                $media_id = FrmAppHelper::get_param('csv');
                $current_path = get_attached_file($media_id);
                $row = FrmAppHelper::get_param('row');
                
                $opts = get_option('frm_import_options');
                
                $left = ($opts and isset($opts[$media_id])) ? ((int)$row - (int)$opts[$media_id]['imported'] - 1) : ($row-1);
                    
                $mapping = FrmAppHelper::get_param('data_array');
                $url_vars = "&csv_del=". urlencode($csv_del) ."&form_id={$form_id}&csv={$media_id}&row={$row}";
                foreach($mapping as $mkey => $map)
                    $url_vars .= "&data_array[$mkey]=$map";
            }
        }
        
        $next_step = ($step == 'One') ? __('Step Two', 'formidable') : __('Import', 'formidable');
        
        if($step == 'One')
            $csvs = get_posts( array('post_type' => 'attachment', 'post_mime_type' => 'text/csv') );
        
        
        include(FRMPRO_VIEWS_PATH.'/frmpro-entries/import.php');
    }
    
    function import_csv_entries(){
        if(!current_user_can('frm_create_entries'))
            wp_die($frm_settings->admin_permission);
            
        extract($_POST);
        
        $opts = get_option('frm_import_options');
        if(!$opts)
            $opts = array();
          
        $current_path = get_attached_file($csv);
        $start_row = (isset($opts[$csv])) ? $opts[$csv]['imported'] : 1;
        $imported = FrmProAppHelper::import_csv($current_path, $form_id, $data_array, 0, $start_row+1, $csv_del);

        $opts[$csv] = compact('row', 'imported');
        echo $remaining = ((int)$row - (int)$imported);
        
        if(!$remaining)
            unset($opts[$csv]);
            
        update_option('frm_import_options', $opts);
        
        die();
    }
    
    function duplicate(){
        global $frm_entry, $frm_form;
        
        $params = $this->get_params();
        if($params['form'])
            $form = $frm_form->getOne($params['form']);
        
        $message = $errors = '';
        $record = $frm_entry->duplicate( $params['id'] );
        if ($record)
            $message = __('Entry was Successfully Duplicated', 'formidable');
        else
            $errors = __('There was a problem duplicating that entry', 'formidable');
        
        if(!empty($errors))
            return $this->display_list($params, $errors);
        else
            return $this->get_edit_vars($record, '', $message);
    }
    
    function destroy(){
        if(!current_user_can('frm_delete_entries')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        
        global $frm_entry, $frm_form;
        $params = $this->get_params();
        if($params['form'])
            $form = $frm_form->getOne($params['form']);
        
        $message = '';    
        if ($frm_entry->destroy( $params['id'] ))
            $message = __('Entry was Successfully Destroyed', 'formidable');
        $this->display_list($params, $message, '', 1);
    }
    
    function destroy_all(){
        if(!current_user_can('frm_delete_entries')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }
        
        global $frm_entry, $frm_form, $frmdb;
        $params = $this->get_params();
        $message = '';    
        $errors = array();
        if($params['form']){
            $form = $frm_form->getOne($params['form']);
            $entry_ids = $frmdb->get_col($frmdb->entries, array('form_id' => $form->id));
            
            foreach($entry_ids as $entry_id){
                if ($frm_entry->destroy( $entry_id ))
                    $message = __('Entries were Successfully Destroyed', 'formidable');
            }
        }else{
            $errors = __('No entries were specified', 'formidable');
        }
        $this->display_list($params, $message, '', 0, $errors);
    }
    
    function bulk_actions($action='list-form'){
        global $frm_entry, $frm_settings;
        $params = $this->get_params();
        $errors = array();
        $bulkaction = '-1';
        
        if($action == 'list-form'){
            if($_REQUEST['bulkaction'] != '-1')
                $bulkaction = $_REQUEST['bulkaction'];
            else if($_POST['bulkaction2'] != '-1')
                $bulkaction = $_REQUEST['bulkaction2'];
        }else{
            $bulkaction = str_replace('bulk_', '', $action);
        }

        $items = FrmAppHelper::get_param('item-action', '');
        if (empty($items)){
            $errors[] = __('No entries were specified', 'formidable');
        }else{
            if(!is_array($items))
                $items = explode(',', $items);
                
            if($bulkaction == 'delete'){
                if(!current_user_can('frm_delete_entries')){
                    $errors[] = $frm_settings->admin_permission;
                }else{
                    if(is_array($items)){
                        foreach($items as $item_id)
                            $frm_entry->destroy($item_id);
                    }
                }
            }else if($bulkaction == 'export'){
                $controller = 'items';
                $ids = $items;
                $ids = implode(',', $ids);
                include_once(FRMPRO_VIEWS_PATH.'/shared/xml.php');
            }else if($bulkaction == 'csv'){
                if(!current_user_can('frm_view_entries'))
                    wp_die($frm_settings->admin_permission);

                global $frm_form;
                
                $form_id = $params['form'];
                if($form_id){
                    $form = $frm_form->getOne($form_id);
                }else{
                    $form = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');
                    if($form)
                        $form_id = $form->id;
                    else
                        $errors[] = __('No form was found', 'formidable');
                }
                
                if($form_id and is_array($items)){
                    echo '<script type="text/javascript">window.onload=function(){location.href="'. FRM_SCRIPT_URL .'&controller=entries&form='. $form_id .'&frm_action=csv&item_id='. implode(',', $items) .'";}</script>';
                }
            }
        }
        $this->display_list($params, '', false, false, $errors);
    }
    
    /* Front End CRUD */

    function process_update_entry($params, $errors, $form){
        global $frm_entry, $frm_saved_entries, $frm_created_entry;
        
        $form->options = stripslashes_deep(maybe_unserialize($form->options));
        
        if($params['action'] == 'update' and in_array((int)$params['id'], (array)$frm_saved_entries))
            return;
        
        if($params['action'] == 'create' and isset($frm_created_entry[$form->id]) and isset($frm_created_entry[$form->id]['entry_id']) and is_numeric($frm_created_entry[$form->id]['entry_id'])){
            $entry_id = $params['id'] = $frm_created_entry[$form->id]['entry_id'];
            
            FrmProEntriesController::set_cookie($entry_id, $form->id);
                
            $conf_method = apply_filters('frm_success_filter', 'message', $form, $form->options);
            if ($conf_method == 'redirect'){
                //do_action('frm_success_action', $conf_method, $form, $form->options, $params['id']);
                $success_url = apply_filters('frm_content', $form->options['success_url'], $form, $entry_id);
                $success_url = apply_filters('frm_redirect_url', $success_url, $form, $params);
                wp_redirect( $success_url );
                exit;
            }
        }else if ($params['action'] == 'update'){
            if(in_array((int)$params['id'], (array)$frm_saved_entries)){
                if(isset($_POST['item_meta']))
                    unset($_POST['item_meta']);

                add_filter('frm_continue_to_new', create_function('', "return $continue;"), 15);
                return;
            }
            
            if (empty($errors)){
                if (isset($form->options['editable_role']) and !FrmAppHelper::user_has_permission($form->options['editable_role'])){
                    global $frm_settings;
                    wp_die($frm_settings->login_msg);
                }
                
                if (!isset($_POST['frm_page_order_'. $form->id])){
                    $frm_entry->update( $params['id'], $_POST );
                    
                    //check confirmation method 
                    $conf_method = apply_filters('frm_success_filter', 'message', $form);
                    
                    if ($conf_method == 'redirect'){
                        //do_action('frm_success_action', $conf_method, $form, $form->options, $params['id']);
                        $success_url = apply_filters('frm_content', $form->options['success_url'], $form, $params['id']);
                        $success_url = apply_filters('frm_redirect_url', $success_url, $form, $params);
                        wp_redirect( $success_url );
                        exit;
                    }
                }
            }
            
        }else if ($params['action'] == 'destroy'){
            //if the user who created the entry is deleting it
            $this->ajax_destroy($form->id, false, false);
        }
    }
        
    function edit_update_form($params, $fields, $form, $title, $description){
        global $frmdb, $wpdb, $frm_entry, $frm_entry_meta, $user_ID, $frm_editing_entry, $frmpro_settings, $frm_saved_entries;
        
        $message = '';
        $continue = true;
        $form->options = stripslashes_deep(maybe_unserialize($form->options));

        if ($params['action'] == 'edit'){
            $entry_key = FrmAppHelper::get_param('entry');
            $entry_key = esc_sql($entry_key);
            
            if($entry_key){    
                $in_form = $wpdb->get_var("SELECT id FROM $frmdb->entries WHERE form_id=".(int)$form->id ." AND (id='{$entry_key}' OR item_key='{$entry_key}')");
                if(!$in_form)
                    $entry_key = false;
                unset($in_form);
            }
            
            $entry = FrmProEntry::user_can_edit($entry_key, $form);

            if($entry and !is_array($entry)){
                $where = "fr.id='$form->id'";
                if ($entry_key)
                    $where .= ' AND (it.id="'. $entry_key .'" OR it.item_key="'. $entry_key .'")';

                $entry = $frm_entry->getAll( $where, '', 1, true);   
            }
            
            if ($entry and !empty($entry)){
                $entry = reset($entry);
                $frm_editing_entry = $entry->id;
                $this->show_responses($entry, $fields, $form, $title, $description);
                $continue = false;
            }
        }else if ($params['action'] == 'update' and ($params['posted_form_id'] == $form->id)){
            global $frm_created_entry;
            
            $errors = $frm_created_entry[$form->id]['errors'];

            if (empty($errors)){
                if (!isset($_POST['frm_page_order_'. $form->id])){
                    //check confirmation method 
                    $conf_method = apply_filters('frm_success_filter', 'message', $form);
                    
                    if ($conf_method == 'message'){
                        global $frmpro_settings;
                        $message = '<div class="frm_message" id="message">'. do_shortcode(isset($form->options['edit_msg']) ? $form->options['edit_msg'] : $frmpro_settings->edit_msg).'</div>';
                    }else{
                        do_action('frm_success_action', $conf_method, $form, $form->options, $params['id']);
                        add_filter('frm_continue_to_new', create_function('', "return false;"), 15);
                        return;
                    }
                }
            }else{
                $fields = FrmFieldsHelper::get_form_fields($form->id, true);
            }
            
            $this->show_responses($params['id'], $fields, $form, $title, $description, $message, $errors);
            $continue = false;
            
        }else if ($params['action'] == 'destroy'){
            //if the user who created the entry is deleting it
            $message = $this->ajax_destroy($form->id, false);
        }else if($frm_editing_entry){
            if(is_numeric($frm_editing_entry)){
                $entry_id = $frm_editing_entry; //get entry from shortcode
            }else{
                $entry_ids = $wpdb->get_col("SELECT id FROM $frmdb->entries WHERE user_id='$user_ID' and form_id='$form->id'");
                
                if (isset($entry_ids) and !empty($entry_ids)){
                    $where_options = $frm_editing_entry;
                    if(!empty($where_options))
                        $where_options .= ' and ';
                    $where_options .= "it.item_id in (".implode(',', $entry_ids).")";
                    
                    $get_meta = $frm_entry_meta->getAll($where_options, ' ORDER BY it.created_at DESC', ' LIMIT 1');
                    $entry_id = ($get_meta) ? $get_meta->item_id : false;
                }
            }

            if(isset($entry_id) and $entry_id){
                if($form->editable and isset($form->options['open_editable']) and $form->options['open_editable'] and isset($form->options['open_editable_role']) and FrmAppHelper::user_has_permission($form->options['open_editable_role']))
                    $meta = true;
                else
                    $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'id' => $entry_id, 'form_id' => $form->id ));

                if($meta){
                    $frm_editing_entry = $entry_id;
                    $this->show_responses($entry_id, $fields, $form, $title, $description);
                    $continue = false;
                }
            }
        }else{
            //check to see if use is allowed to create another entry
            $can_submit = true;
            if (isset($form->options['single_entry']) and $form->options['single_entry']){
                if ($form->options['single_entry_type'] == 'cookie' and isset($_COOKIE['frm_form'. $form->id . '_' . COOKIEHASH])){
                    $can_submit = false;
                }else if ($form->options['single_entry_type'] == 'ip'){
                    $prev_entry = $frm_entry->getAll(array('it.form_id' => $form->id, 'it.ip' => $_SERVER['REMOTE_ADDR']), '', 1);
                    if ($prev_entry)
                        $can_submit = false;
                }else if ($form->options['single_entry_type'] == 'user' and !$form->editable and $user_ID){
                    $meta = $frmdb->get_var($frmdb->entries, array('user_id' => $user_ID, 'form_id' => $form->id ));
                    if ($meta)
                        $can_submit = false;
                }

                if (!$can_submit){
                    echo stripslashes($frmpro_settings->already_submitted);//TODO: DO SOMETHING IF USER CANNOT RESUBMIT FORM
                    $continue = false;
                }
            }
        }

        add_filter('frm_continue_to_new', create_function('', "return $continue;"), 15);
    }

    function show_responses($id, $fields, $form, $title=false,$description=false, $message='', $errors=''){
        global $frm_form, $frm_field, $frm_entry, $frmpro_entry, $frm_entry_meta, $user_ID, $frmpro_settings, $frm_next_page, $frm_prev_page, $frm_load_css;

        if(is_object($id)){
            $item = $id;
            $id = $item->id;
        }else
            $item = $frm_entry->getOne($id, true);

        $values = FrmAppHelper::setup_edit_vars($item, 'entries', $fields);

        if($values['custom_style']) $frm_load_css = true;
        $show_form = true;
        $submit = (isset($frm_next_page[$form->id])) ? $frm_next_page[$form->id] : (isset($values['edit_value']) ? $values['edit_value'] : $frmpro_settings->update_value);
        
        if(!isset($frm_prev_page[$form->id]) and isset($_POST['item_meta']) and empty($errors) and $form->id == FrmAppHelper::get_param('form_id')){
            $form->options = stripslashes_deep(maybe_unserialize($form->options));
            $show_form = (isset($form->options['show_form'])) ? $form->options['show_form'] : true;
            $conf_method = apply_filters('frm_success_filter', 'message', $form);
            if ($conf_method != 'message')
                do_action('frm_success_action', $conf_method, $form, $form->options, $id);
        }else if(isset($frm_prev_page[$form->id]) or !empty($errors)){
            $jump_to_form = true;
        }

        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/edit-front.php');
        add_filter('frm_continue_to_new', array($frmpro_entry, 'frmpro_editing'), 10, 3);
    }
    
    function ajax_submit_button($form, $action='create'){
        global $frm_novalidate;
        
        if($frm_novalidate)
            echo ' formnovalidate="formnovalidate"';
        //if form ajax submit
        //echo 'onsubmit="return false;" onclick="frm_submit_form(\''.FRM_SCRIPT_URL.'\',jQuery(\'#form_'. $form->form_key .'\').serialize(), \'form_'. $form->form_key .'\')"';

    }
    
    function get_confirmation_method($method, $form){
        $method = (isset($form->options['success_action']) and !empty($form->options['success_action'])) ? $form->options['success_action'] : $method;
        return $method;
    }
    
    function confirmation($method, $form, $form_options, $entry_id){
        //fire the alternate confirmation options ('page' or 'redirect')
        if($method == 'page' and is_numeric($form_options['success_page_id'])){
            global $post;
            if($form_options['success_page_id'] != $post->ID){
                $page = get_post($form_options['success_page_id']);
                $old_post = $post;
                $post = $page;
                $content = apply_filters('frm_content', $page->post_content, $form, $entry_id);
                echo apply_filters('the_content', $content);
                $post = $old_post;
            }
        }else if($method == 'redirect'){
            $success_url = apply_filters('frm_content', $form_options['success_url'], $form, $entry_id);
            $success_msg = isset($form_options['success_msg']) ? stripslashes($form_options['success_msg']) : __('Please wait while you are redirected.', 'formidable'); 
            $redirect_msg = '<div class="frm-redirect-msg frm_message">'. $success_msg .'<br/>'.
                sprintf(__('%1$sClick here%2$s if you are not automatically redirected.', 'formidable'), '<a href="'. esc_url($success_url) .'">', '</a>') .
                '</div>';
                
            echo apply_filters('frm_redirect_msg', $redirect_msg, array(
                'entry_id' => $entry_id, 'form_id' => $form->id, 'form' => $form
            ));
            
            echo "<script type='text/javascript'> jQuery(document).ready(function($){ setTimeout(window.location='". $success_url ."', 5000); });</script>";
        }
    }
    
    function delete_entry($post_id){
        global $frmdb;
        $entry = $frmdb->get_one_record($frmdb->entries, array('post_id' => $post_id), 'id');
        if($entry){
            global $frm_entry;
            $frm_entry->destroy($entry->id);
        }
    }
    
    function create_entry_from_post_box($post_type, $post){
        if(!isset($post->ID) or $post_type == 'attachment' or $post_type == 'link')
            return;
            
        global $frmdb, $wpdb, $frm_post_forms;
        
        //don't show the meta box if there is already an entry for this post
        $post_entry = $wpdb->get_var("SELECT id FROM $frmdb->entries WHERE post_id=". $post->ID);
        if($post_entry) 
            return;
        
        //don't show meta box if no forms are set up to create this post type
        $forms = $wpdb->get_results("SELECT id, name FROM $frmdb->forms where options LIKE '%s:9:\"post_type\";s:". strlen($post_type) .":\"". $post_type ."\";%' AND options LIKE '%s:11:\"create_post\";s:1:\"1\";%'");
        if(!$forms)
            return;
            
        $frm_post_forms = $forms;
        
        add_meta_box('frm_create_entry', __('Create Entry in Form', 'formidable'), array(&$this, 'render_meta_box_content' ), null, 'side');
    }
    
    function render_meta_box_content($post){
        global $frm_post_forms, $frm_ajax_url;
        $count = count($frm_post_forms);
        $i = 1;
        
        echo '<p>';
        foreach($frm_post_forms as $form){
            if($i != 1)
                echo ' | ';
            
            $i++;
            echo '<a href="javascript:frm_create_post_entry('. $form->id .','. $post->ID .')">'. stripslashes(FrmAppHelper::truncate($form->name, 15)) .'</a>';
            unset($form);
        }
        unset($i);
        echo '</p>';
        
        
        echo "<script type='text/javascript'>function frm_create_post_entry(id,post_id){
jQuery('#frm_create_entry p').replaceWith('<img src=\"". FRM_IMAGES_URL ."/wpspin_light.gif\" alt=\"". __('Loading...', 'formidable') ."\" />');
jQuery.ajax({type:'POST',url:'{$frm_ajax_url}',data:'action=frm_create_post_entry&id='+id+'&post_id='+post_id,
success:function(msg){jQuery('#frm_create_entry').fadeOut('slow');}
});
};</script>";
    }
    
    function create_post_entry($id=false, $post_id=false){
        if(!$id)
            $id = $_POST['id'];
            
        if(!$post_id)
            $post_id = $_POST['post_id'];
        
        if(!is_numeric($id) or !is_numeric($post_id))
            return;
        
        $post = get_post($post_id);
        
        global $frmdb, $wpdb, $frm_field;
        $values = array(
            'description' => __('Copied from Post', 'formidable'),
            'form_id' => $id,
            'created_at' => $post->post_date_gmt,
            'name' => $post->post_title,
            'item_key' => FrmAppHelper::get_unique_key($post->post_name, $frmdb->entries, 'item_key'),
            'user_id' => $post->post_author,
            'post_id' => $post->ID
        );
        
        $results = $wpdb->insert( $frmdb->entries, $values );
        unset($values);
        
        if($results){
            $entry_id = $wpdb->insert_id;
            $user_id_field = $frm_field->getAll(array('fi.type' => 'user_id', 'fi.form_id' => $id), '', 1);
            if($user_id_field){
                $new_values = array(
                    'meta_value' => $post->post_author,
                    'item_id' => $entry_id,
                    'field_id' => $user_id_field->id,
                    'created_at' => current_time('mysql', 1)
                );
                
                $wpdb->insert( $frmdb->entry_metas, $new_values );
            }
        }
        die();
    }

    /* Export to CSV */
    function csv($form_id, $search = '', $fid = ''){
        if(!current_user_can('frm_view_entries')){
            global $frm_settings;
            wp_die($frm_settings->admin_permission);
        }

        if( !ini_get('safe_mode') ){
            set_time_limit(0); //Remove time limit to execute this function
            ini_set('memory_limit', '256M');
        }

        global $current_user, $frm_form, $frm_field, $frm_entry, $frm_entry_meta, $wpdb, $frmpro_settings;
        
        $form = $frm_form->getOne($form_id);
        $form_name = sanitize_title_with_dashes($form->name);
        $form_cols = $frm_field->getAll("fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=".$form->id, 'field_order ASC');
        $item_id = FrmAppHelper::get_param('item_id', false);
        $where_clause = "it.form_id=". (int)$form_id;
        
        if($item_id){

            $where_clause .= " and it.id in (";
            $item_ids = explode(',', $item_id);
            foreach((array)$item_ids as $k => $it){
                if($k)
                    $where_clause .= ",";
                $where_clause .= $it;
                unset($k);
                unset($it);
            }

            $where_clause .= ")";
        }else if(!empty($search)){
            $where_clause = $this->get_search_str($where_clause, $search, $form_id, $fid);
        }
          
        $where_clause = apply_filters('frm_csv_where', $where_clause, compact('form_id'));

        $entries = $frm_entry->getAll($where_clause, '', '', true, false);

        $filename = date("ymdHis",time()) . '_' . $form_name . '_formidable_entries.csv';
        $wp_date_format = apply_filters('frm_csv_date_format', 'Y-m-d H:i:s');
        $charset = get_option('blog_charset');
        
        $to_encoding = $frmpro_settings->csv_format;
        
        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/csv.php');
    }

    /* Display in Back End */
    
    function manage_columns($columns){
        global $frm_field, $frm_cols;
        $form_id = FrmProAppHelper::get_current_form_id();
        
        $columns['cb'] = '<input type="checkbox" />';
        $columns[$form_id .'_id'] = 'ID';
        $columns[$form_id .'_item_key'] = __('Entry Key', 'formidable');
        
        $form_cols = $frm_field->getAll("fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=". $form_id, 'field_order ASC');
        foreach($form_cols as $form_col)
            $columns[$form_id .'_'. $form_col->field_key] = stripslashes($form_col->name);

        $columns[$form_id .'_post_id'] = __('Post', 'formidable');
        $columns[$form_id .'_created_at'] = __('Entry creation date', 'formidable');
        $columns[$form_id .'_updated_at'] = __('Entry update date', 'formidable');
        $columns[$form_id .'_ip'] = 'IP';
        
        //TODO: allow custom order of columns
        
        $frm_cols = $columns;
        add_screen_option( 'per_page', array('label' => __('Entries', 'formidable'), 'default' => 20, 'option' => 'formidable_page_formidable_entries_per_page') );
        
        return $columns;
    }
    
    function check_hidden_cols($check, $object_id, $meta_key, $meta_value, $prev_value){
        global $frm_settings;
        if($meta_key != 'manage'.  sanitize_title($frm_settings->menu) .'_page_formidable-entriescolumnshidden' or $meta_value == $prev_value)
            return $check;
        
        if ( empty($prev_value) )
    		$prev_value = get_metadata('user', $object_id, $meta_key, true);
    		
        global $frm_prev_hidden_cols;
        $frm_prev_hidden_cols = ($frm_prev_hidden_cols) ? false : $prev_value; //add a check so we don't create a loop

        return $check;
    }
    
    //add hidden columns back from other forms
    function update_hidden_cols($meta_id, $object_id, $meta_key, $meta_value ){
        global $frm_settings;

        if($meta_key != 'manage'.  sanitize_title($frm_settings->menu) .'_page_formidable-entriescolumnshidden')
            return;
            
        global $frm_prev_hidden_cols;
        if(!$frm_prev_hidden_cols)
            return; //don't continue if there's no previous value
        
        foreach($meta_value as $mk => $mv){
            //remove blank values
            if(empty($mv))
                unset($meta_value[$mk]);
        }
        
        $cur_form_prefix = reset($meta_value);
        $cur_form_prefix = explode('_', $cur_form_prefix);
        $cur_form_prefix = $cur_form_prefix[0];
        $save = false;

        foreach($frm_prev_hidden_cols as $prev_hidden){
            if(empty($prev_hidden) or in_array($prev_hidden, $meta_value)) //don't add blank cols or process included cols
                continue;
            
            $form_prefix = explode('_', $prev_hidden);
            $form_prefix = $form_prefix[0];
            if($form_prefix == $cur_form_prefix) //don't add back columns that are meant to be hidden
                continue;
            
            $meta_value[] = $prev_hidden;
            $save = true;
            unset($form_prefix);
        }
        
        if($save){
            $user = wp_get_current_user();
            update_user_option($user->ID, 'manage'.  sanitize_title($frm_settings->menu) .'_page_formidable-entriescolumnshidden', $meta_value, true);
        }
    }
    
    function save_per_page($save, $option, $value){
        if($option == 'formidable_page_formidable_entries_per_page')
            $save = (int)$value;
        return $save;
    }
    
    function sortable_columns(){
        $form_id = FrmProAppHelper::get_current_form_id();
        return array(
            $form_id .'_id'         => 'id',
            $form_id .'_created_at' => 'created_at',
            $form_id .'_updated_at' => 'updated_at',
            $form_id .'_ip'         => 'ip',
            $form_id .'_item_key'   => 'item_key'
        );
    }
    
    function hidden_columns($result){
        global $frm_cols;

        $form_id = FrmProAppHelper::get_current_form_id();
        
        $return = false;
        foreach((array)$result as $r){
            if(!empty($r)){
                $form_prefix = explode('_', $r);
                $form_prefix = $form_prefix[0];
                
                if((int)$form_prefix == (int)$form_id){
                    $return = true;
                    break;
                }
                
                unset($form_prefix);
            }
        }
        
        if($return)
            return $result;
 
        $i = count($frm_cols);
        $max_columns = 8;
        if($i > $max_columns){
            global $frm_current_form;
            if($frm_current_form)
                $frm_current_form->options = maybe_unserialize($frm_current_form->options);
            if($frm_current_form and isset($frm_current_form->options['hidden_cols']) and !empty($frm_current_form->options['hidden_cols'])){
                $result = $frm_current_form->options['hidden_cols'];
            }else{
                $cols = $frm_cols;
                $cols = array_reverse($cols, true);

                $result[] = $form_id .'_id';
                $i--;
                
                $result[] = $form_id .'_item_key';
                $i--;
                
                foreach($cols as $col_key => $col){
                    if($i > $max_columns)
                        $result[] = $col_key; //remove some columns by default
                    $i--;
                }
            }
        }
        
        return $result;
    }
    
    function display_list($params=false, $message='', $page_params_ov = false, $current_page_ov = false, $errors = array()){
        global $wpdb, $frmdb, $frm_app_helper, $frm_form, $frm_entry, $frm_entry_meta, $frm_page_size, $frm_field, $frm_current_form;
        
        if(!$params)
            $params = $this->get_params();
   
        $errors = array();
        
        $form_select = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name');

        if($params['form'])
            $form = $frm_form->getOne($params['form']);
        else
            $form = (isset($form_select[0])) ? $form_select[0] : 0;
        
        if($form){
            $params['form'] = $form->id;
            $frm_current_form = $form;
	        $where_clause = " it.form_id=$form->id";
        }else{
            $where_clause = '';
		}
        
        $page_params = "&action=0&frm_action=0&form=";
        $page_params .= ($form) ? $form->id : 0;
        
        if ( ! empty( $_REQUEST['s'] ) )
            $page_params .= '&s='. urlencode($_REQUEST['s']);
        
        if ( ! empty( $_REQUEST['search'] ) )
            $page_params .= '&search='. urlencode($_REQUEST['search']);

    	if ( ! empty( $_REQUEST['fid'] ) )
    	    $page_params .= '&fid='. $_REQUEST['fid'];
        
        if(class_exists('WP_List_Table')){
            require_once(FRMPRO_PATH .'/classes/helpers/FrmProListHelper.php');

            $wp_list_table = new FrmProListHelper(array('singular' => 'entry', 'plural' => 'entries', 'table_name' => $frmdb->entries, 'page_name' => 'entries', 'params' => $params));

            $pagenum = $wp_list_table->get_pagenum();

            $wp_list_table->prepare_items();

            $total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
            if ( $pagenum > $total_pages && $total_pages > 0 ) {
            	wp_redirect( add_query_arg( 'paged', $total_pages ) );
            	exit;
            }
        }else{
            $item_vars = $this->get_sort_vars($params, $where_clause);
    		$page_params .= ($page_params_ov) ? $page_params_ov : $item_vars['page_params'];
    		
            if($form){
    			$form_cols = $frm_field->getAll("fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=". (int)$form->id, 'field_order ASC', ' LIMIT 7');
    	        $record_where = ($item_vars['where_clause'] == " it.form_id=$form->id") ? $form->id : $item_vars['where_clause'];
    	    }else{
    	        $form_cols = array();
    	        $record_where = $item_vars['where_clause'];
    	    }
    	    
            $current_page = ($current_page_ov) ? $current_page_ov: $params['paged'];
            
            $sort_str = $item_vars['sort_str'];
            $sdir_str = $item_vars['sdir_str'];
            $search_str = $item_vars['search_str'];
            $fid = $item_vars['fid'];
              
            $record_count = $frm_entry->getRecordCount($record_where);
            $page_count = $frm_entry->getPageCount($frm_page_size, $record_count);
            $items = $frm_entry->getPage($current_page, $frm_page_size, $item_vars['where_clause'], $item_vars['order_by']);
            $page_last_record = $frm_app_helper->getLastRecordNum($record_count, $current_page, $frm_page_size);
            $page_first_record = $frm_app_helper->getFirstRecordNum($record_count, $current_page, $frm_page_size);
        }

        require_once(FRMPRO_VIEWS_PATH.'/frmpro-entries/list.php');
    }
    
    function get_sort_vars($params=false, $where_clause = ''){
        global $frm_entry_meta, $frm_current_form;
        
        if(!$params)
            $params = $this->get_params($frm_current_form);
 
        $order_by = '';
        $page_params = '';

        // These will have to work with both get and post
        $sort_str = $params['sort'];
        $sdir_str = $params['sdir'];
        $search_str = $params['search'];
        $fid = $params['fid'];

        // make sure page params stay correct
        if(!empty($sort_str))
            $page_params .="&sort=$sort_str";

        if(!empty($sdir_str))
            $page_params .= "&sdir=$sdir_str";

        if(!empty($search_str)){
            $where_clause = $this->get_search_str($where_clause, $search_str, $params['form'], $fid);
            $page_params .= "&search=$search_str";
            if(is_numeric($fid))
                $page_params .= "&fid=$fid";
        }

        // Add order by clause
        if(is_numeric($sort_str))
            $order_by .= " ORDER BY ID"; //update this to order by item meta
        else if ($sort_str == "item_key")
            $order_by .= " ORDER BY item_key";
        else
            $order_by .= " ORDER BY ID";


        // Toggle ascending / descending
        if((empty($sort_str) and empty($sdir_str)) or $sdir_str == 'desc'){
            $order_by .= ' DESC';
            $sdir_str = 'desc';
        }else{
            $order_by .= ' ASC';
            $sdir_str = 'asc';
        }
        
        return compact('order_by', 'sort_str', 'sdir_str', 'fid', 'search_str', 'where_clause', 'page_params');
    }
    
    function get_search_str($where_clause='', $search_str, $form_id=false, $fid=false){
        global $frm_entry_meta;
        
        $where_item = '';
        $join = ' (';
        if(!is_array($search_str))
            $search_str = explode(" ", $search_str);
        
        foreach($search_str as $search_param){
            $search_param = esc_sql( like_escape( $search_param ) );
			
            if(!is_numeric($fid)){
                $where_item .= (empty($where_item)) ? ' (' : ' OR';
                    
                if(in_array($fid, array('created_at', 'user_id', 'updated_at'))){
                    if($fid == 'user_id' and !is_numeric($search_param))
                        $search_param = FrmProAppHelper::get_user_id_param($search_param);
                    
                    $where_item .= " it.{$fid} like '%$search_param%'";
                }else{
                    $where_item .= " it.name like '%$search_param%' OR it.item_key like '%$search_param%' OR it.description like '%$search_param%' OR it.created_at like '%$search_param%'";
                }
            }
            
            if(empty($fid) or is_numeric($fid)){
                $where_entries = "(meta_value LIKE '%$search_param%'";
                if($data_fields = FrmProForm::has_field('data', $form_id, false)){
                    $df_form_ids = array();

                    //search the joined entry too
                    foreach((array)$data_fields as $df){
                        $df->field_options = maybe_unserialize($df->field_options);
                        if (is_numeric($df->field_options['form_select']))
                            $df_form_ids[] = $df->field_options['form_select'];

                        unset($df);
                    }
                    
                    unset($data_fields);

                    global $wpdb, $frmdb;
                    $data_form_ids = $wpdb->get_col("SELECT form_id FROM $frmdb->fields WHERE id in (". implode(',', $df_form_ids).")");
                    unset($df_form_ids);

                    if($data_form_ids){
                        $data_entry_ids = $frm_entry_meta->getEntryIds("fi.form_id in (". implode(',', $data_form_ids).") and meta_value LIKE '%". $search_param ."%'");
                        if(!empty($data_entry_ids))
                            $where_entries .= " OR meta_value in (".implode(',', $data_entry_ids).")";
                    }

                    unset($data_form_ids);
                }
                
                $where_entries .= ")";

                if(is_numeric($fid))
                    $where_entries .= " AND fi.id=$fid";

                $meta_ids = $frm_entry_meta->getEntryIds($where_entries);
                if (!empty($meta_ids)){
                    if(!empty($where_clause)){
                        $where_clause .= " AND" . $join;
                        if(!empty($join)) $join = '';
                    }
                    $where_clause .= " it.id in (".implode(',', $meta_ids).")";
                }else{
                    if(!empty($where_clause)){
                        $where_clause .= " AND" . $join;
                        if(!empty($join)) $join = '';
                    }
                    $where_clause .= " it.id=0";
                }
            }
        }
        
        if(!empty($where_item)){
            $where_item .= ')';
            if(!empty($where_clause))
                $where_clause .= empty($fid) ? ' OR' : ' AND';
            $where_clause .= $where_item;
            if(empty($join))
                $where_clause .= ')';
        }else{
            if(empty($join))
                $where_clause .= ')';
        }

        return $where_clause;
    }

    function get_new_vars($errors = '', $form = '',$message = ''){
        global $frm_form, $frm_field, $frm_entry, $frm_settings, $frm_next_page;
        $title = true;
        $description = true;
        $fields = FrmFieldsHelper::get_form_fields($form->id, !empty($errors));
        $values = FrmEntriesHelper::setup_new_vars($fields, $form);
        $submit = (isset($frm_next_page[$form->id])) ? $frm_next_page[$form->id] : (isset($values['submit_value']) ? $values['submit_value'] : $frm_settings->submit_value);  
        require_once(FRMPRO_VIEWS_PATH.'/frmpro-entries/new.php');
    }

    function get_edit_vars($id, $errors = '', $message= ''){
        if(!current_user_can('frm_edit_entries'))
            return $this->show($id);

        global $frm_form, $frm_entry, $frm_field, $frm_next_page, $frmpro_settings, $frm_editing_entry;
        $title = $description = true;
        $record = $frm_entry->getOne( $id, true );
        $frm_editing_entry = $id;
        
        $form = $frm_form->getOne($record->form_id);
        $fields = FrmFieldsHelper::get_form_fields($form->id, !empty($errors));
        $values = FrmAppHelper::setup_edit_vars($record, 'entries', $fields);

        $submit = (isset($frm_next_page[$form->id])) ? $frm_next_page[$form->id] : (isset($values['edit_value']) ? $values['edit_value'] : $frmpro_settings->update_value); 
        require(FRMPRO_VIEWS_PATH.'/frmpro-entries/edit.php');
    }
    
    function get_params($form=null){
        global $frm_form;

        if(!$form)
            $form = $frm_form->getAll("is_template=0 AND (status is NULL OR status = '' OR status = 'published')", ' ORDER BY name', ' LIMIT 1');
        
        $values = array();
        foreach (array('id' => '', 'form_name' => '', 'paged' => 1, 'form' => (($form) ? $form->id : 0), 'field_id' => '', 'search' => '', 'sort' => '', 'sdir' => '', 'fid' => '') as $var => $default)
            $values[$var] = FrmAppHelper::get_param($var, $default);

        return $values;
    }
    
    function &filter_shortcode_value($value, $tag, $atts, $field){
        if(isset($atts['show']) and $atts['show'] == 'value')
            return $value;
            
        $value = $this->filter_display_value($value, $field);
        return $value;
    }
    
    function &filter_display_value($value, $field){
        global $frm_entries_controller;
        $value = $frm_entries_controller->filter_display_value($value, $field);
        return $value;
    }

    function route(){
        $action = FrmAppHelper::get_param('frm_action');
            
        if($action == 'show')
            return $this->show();
        else if($action == 'new')
            return $this->new_entry();
        else if($action == 'create')
            return $this->create();
        else if($action == 'edit')
            return $this->edit();
        else if($action == 'update')
            return $this->update();
        else if($action == 'import')
            return $this->import();
        else if($action == 'duplicate')
            return $this->duplicate();
        else if($action == 'destroy')
            return $this->destroy();
        else if($action == 'destroy_all')
            return $this->destroy_all();
        else if($action == 'list-form')
            return $this->bulk_actions($action);
        else{
            $action = FrmAppHelper::get_param('action');
            if($action == -1)
                $action = FrmAppHelper::get_param('action2');
            
            if(strpos($action, 'bulk_') === 0){
                if(isset($_GET) and isset($_GET['action']))
                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action'], '', $_SERVER['REQUEST_URI']);
                if(isset($_GET) and isset($_GET['action2']))
                    $_SERVER['REQUEST_URI'] = str_replace('&action='.$_GET['action2'], '', $_SERVER['REQUEST_URI']);
                    
                return $this->bulk_actions($action);
            }else{
                return $this->display_list();
            }
        }
    }
    
    function get_form_results($atts){
        extract(shortcode_atts(array('id' => false, 'cols' => 99, 'style' => true, 'no_entries' => __('No Entries Found', 'formidable'), 'fields' => false, 'clickable' => false, 'user_id' => false, 'google' => false, 'pagesize' => 20, 'sort' => true, 'edit_link' => false, 'page_id' => false), $atts));
        if (!$id) return;
        
        global $frm_form, $frm_field, $frm_entry, $frm_entry_meta, $frmpro_settings;
        $form = $frm_form->getOne($id);
        if (!$form) return;
        $where = "fi.type not in ('divider', 'captcha', 'break', 'html') and fi.form_id=". (int)$form->id;
        if($fields){
            $fields = explode(',', $fields);
            $f_list = array();
            foreach($fields as $k => $f){
                $f = trim($f);
                $fields[$k] = $f;
                $f_list[] = esc_sql(like_escape($f)); 
                unset($k);
                unset($f);
            }
            if(count($fields) == 1 and in_array('id', $fields))
                $where .= ''; //don't search fields if only field id
            else
                $where .= " and (fi.id in ('". implode("','", $f_list)  ."') or fi.field_key in ('". implode("','", $f_list) ."'))";
            
        }
        $fields = (array)$fields;
        
        $form_cols = $frm_field->getAll($where, 'field_order ASC', $cols);
        unset($where);
        
        $where = array('it.form_id' => $form->id);
        if($user_id)
            $where['user_id'] = FrmProAppHelper::get_user_id_param($user_id);
        
        $entries = $frm_entry->getAll($where, '', '', true, false);
        
        if($edit_link){
            $anchor = '';
            if(!$page_id){
                global $post;
                $page_id = $post->ID;
                $anchor = '#form_'. $form->form_key;
            }
            if($edit_link === '1')
                $edit_link = __('Edit', 'formidable');
                
            $permalink = get_permalink($page_id);
        }
        
        if($style){
            global $frm_load_css;
            $frm_load_css = true;
        }
        
        $filename = 'table';
        if($google){
            global $frm_google_chart;
            $filename = 'google_table';
            $options = array();
            
            if($pagesize)
                $options = array('page' => 'enable', 'pageSize' => (int)$pagesize);
                
            $options['allowHtml'] = true;
            $options['sort'] = ($sort) ? 'enable' : 'disable';
            
            if($style)
                $options['cssClassNames'] = array('oddTableRow' => 'frm_even');
        }
        
        ob_start();
        include(FRMPRO_VIEWS_PATH .'/frmpro-entries/'. $filename .'.php');
        $contents = ob_get_contents();
        ob_end_clean();
        
        if(!$google and $clickable)
            $contents = make_clickable($contents);
        return $contents;
    }
    
    function get_search($atts){
        extract(shortcode_atts(array('id' => false, 'post_id' => '', 'label' => __('Search', 'formidable')), $atts));
        //if (!$id) return;
        if($post_id == ''){
            global $post;
            if($post)
                $post_id = $post->ID;
        }
        
        if($post_id != '')
            $action_link = get_permalink($post_id);
        else
            $action_link = '';
        
        ob_start();
        include(FRMPRO_VIEWS_PATH .'/frmpro-entries/search.php');
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    
    function entry_link_shortcode($atts){
        global $user_ID, $frm_entry, $frm_entry_meta, $post;
        extract(shortcode_atts(array('id' => false, 'field_key' => 'created_at', 'type' => 'list', 'logged_in' => true, 'edit' => true, 'class' => '', 'link_type' => 'page', 'blank_label' => '', 'param_name' => 'entry', 'param_value' => 'key', 'page_id' => false, 'show_delete' => false), $atts));
        
        if (!$id or ($logged_in && !$user_ID)) return;
        $id = (int)$id;
        if($show_delete === 1) $show_delete = __('Delete', 'formidable');
        $s = FrmAppHelper::get_param('frm_search', false);

        if($s)
            $entry_ids = FrmProEntriesHelper::get_search_ids($s, $id);
        else
            $entry_ids = $frm_entry_meta->getEntryIds("fi.form_id='$id'");
        
        if ($entry_ids){
            $id_list = implode(',', $entry_ids);
            $order = ($type == 'collapse') ? ' ORDER BY it.created_at DESC' : '';
            
            $where = "it.id in ($id_list)";
            if ($logged_in)
                $where .= " and it.form_id='". $id ."' and it.user_id='". (int)$user_ID ."'";
            
            $entries = $frm_entry->getAll($where, $order, '', true);
        }

        if (!empty($entries)){
            if ($type == 'list'){
                $content = "<ul class='frm_entry_ul $class'>\n";
            }else if($type == 'collapse'){
                $content = '<div class="frm_collapse">';
                $year = $month = '';
                $prev_year = $prev_month = false;
            }else{
                $content = "<select id='frm_select_form_$id' name='frm_select_form_$id' class='$class' onchange='location=this.options[this.selectedIndex].value;'>\n <option value='".get_permalink($post->ID)."'>$blank_label</option>\n";
            }
            
            global $frm_field;
            if($field_key != 'created_at')
                $field = $frm_field->getOne($field_key);
            
            foreach ($entries as $entry){
                $action = (isset($_GET) and isset($_GET['frm_action'])) ? 'frm_action' : 'action';
                if(isset($_GET) and isset($_GET[$action]) and $_GET[$action] == 'destroy'){
                    if(isset($_GET['entry']) and ($_GET['entry'] == $entry->item_key or $_GET['entry'] == $entry->id))
                        continue;
                }
                
                if($entry->post_id){
                    global $wpdb;
                    $post_status = $wpdb->get_var("SELECT post_status FROM $wpdb->posts WHERE ID=".$entry->post_id);
                    if($post_status != 'publish')
                        continue;
                }
                $value = '';
                $meta = false;
                if ($field_key && $field_key != 'created_at'){
                    if($entry->post_id and (($field and $field->field_options['post_field']) or $field->type == 'tag'))
                        $value = FrmProEntryMetaHelper::get_post_value($entry->post_id, $field->field_options['post_field'], $field->field_options['custom_field'], array('type' => $field->type, 'form_id' => $field->form_id, 'field' => $field));
                    else
                        $meta = isset($entry->metas[$field_key]) ? $entry->metas[$field_key] : '';
                }else
                    $meta = reset($entry->metas);
                
                $value = ($field_key == 'created_at' or !isset($meta) or !$meta) ? $value : (is_object($meta) ? $meta->meta_value : $meta);
                
                if(empty($value))
                    $value = date_i18n(get_option('date_format'), strtotime($entry->created_at));
                else
                    $value = FrmProEntryMetaHelper::display_value($value, $field, array('type' => $field->type, 'show_filename' => false));
                
                if($param_value == 'key')
                    $args = array($param_name => $entry->item_key);
                else
                    $args = array($param_name => $entry->id);
                    
                if ($edit)
                    $args['frm_action'] = 'edit';
                
                if ($link_type == 'scroll'){
                    $link = '#'.$entry->item_key;
                }else if ($link_type == 'admin'){
                    $link = add_query_arg($args, $_SERVER['REQUEST_URI']);
                }else{
                    if($page_id)
                        $permalink = get_permalink($page_id);
                    else
                        $permalink = get_permalink($post->ID);
                    $link = add_query_arg($args, $permalink);
                }
                
                unset($args);
                
                $current = (isset($_GET['entry']) && $_GET['entry'] == $entry->item_key) ? true : false;
                if ($type == 'list'){
                    $content .= "<li><a href='$link'>".stripslashes($value)."</a>";
                    if($show_delete and isset($permalink) and FrmProEntriesHelper::allow_delete($entry))
                        $content .= " <a href='". add_query_arg(array('frm_action' => 'destroy', 'entry' => $entry->id), $permalink) ."' class='frm_delete_list'>$show_delete</a>\n";
                    $content .= "</li>\n";
                }else if($type == 'collapse'){
                    $new_year = strftime('%G', strtotime($entry->created_at));
                    $new_month = strftime('%B', strtotime($entry->created_at));
                    if ($new_year != $year){
                        if($prev_year){
                            if($prev_month) $content .= '</ul></div>';
                            $content .= '</div>';
                            $prev_month = false;
                        }
                        $style = ($prev_year) ? " style='display:none'" : '';
                        $triangle = ($prev_year) ? "e" : "s";
                        $content .= "\n<div class='frm_year_heading frm_year_heading_$id'>
                            <span class='ui-icon ui-icon-triangle-1-$triangle'></span>\n
                            <a>$new_year</a></div>\n
                            <div class='frm_toggle_container' $style>\n";
                        $prev_year = true;
                    }
                    if ($new_month != $month){
                        if($prev_month)
                            $content .= '</ul></div>';
                        $style = ($prev_month) ? " style='display:none'" : '';
                        $triangle = ($prev_month) ? "e" : "s";
                        $content .= "<div class='frm_month_heading frm_month_heading_$id'>
                            <span class='ui-icon ui-icon-triangle-1-$triangle'></span>\n
                            <a>$new_month</a>\n</div>\n
                            <div class='frm_toggle_container frm_month_listing' $style><ul>\n";
                        $prev_month = true;
                    }
                    $content .= "<li><a href='$link'>".stripslashes($value)."</a></li>";
                    $year = $new_year;
                    $month = $new_month;
                }else{
                    $selected = $current ? ' selected="selected"' : '';
                    $content .= "<option value='$link'$selected>" .stripslashes($value) . "</option>\n";
                }
            }

            if ($type == 'list')
                $content .= "</ul>\n";
            else if($type == 'collapse'){
                if($prev_year) $content .= '</div>';
                if($prev_month) $content .= '</ul></div>';
                $content .= '</div>';
                $content .= "<script type='text/javascript'>jQuery(document).ready(function($){ $('.frm_month_heading_". $id . ", .frm_year_heading_". $id ."').toggle(function(){ $(this).children('.ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s'); $(this).children('.ui-icon-triangle-1-s').removeClass('ui-icon-triangle-1-e'); $(this).next('.frm_toggle_container').fadeIn('slow');},function(){ $(this).children('.ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e'); $(this).children('.ui-icon-triangle-1-e').removeClass('ui-icon-triangle-1-s'); $(this).next('.frm_toggle_container').hide();});})</script>\n";
            }else{
                $content .= "</select>\n";
                if($show_delete and isset($_GET) and isset($_GET['entry']) and $_GET['entry'])
                    $content .= " <a href='".add_query_arg(array('frm_action' => 'destroy', 'entry' => $_GET['entry']), $permalink) ."' class='frm_delete_list'>$show_delete</a>\n";
            }
            
        }else
            $content = '';
        
        return $content;
    }
    
    function entry_edit_link($atts){
        global $frm_editing_entry, $post, $frm_forms_loaded;
        extract(shortcode_atts(array(
            'id' => $frm_editing_entry, 'label' => __('Edit', 'formidable'), 'cancel' => __('Cancel', 'formidable'), 
            'class' => '', 'page_id' => (($post) ? $post->ID : 0), 'html_id' => false,
            'prefix' => '', 'form_id' => false
        ), $atts));

        $link = '';
        $entry_id = ($id and is_numeric($id)) ? $id : FrmAppHelper::get_param('entry', false);
            
        if($entry_id and !empty($entry_id)){
            if(!$form_id){
                global $frmdb;
                $form_id = (int)$frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'form_id');
            }
            
            //if user is not allowed to edit, then don't show the link
            if(!FrmProEntry::user_can_edit($entry_id, $form_id))
                return $link;
                
            if(empty($prefix)){
               $link = add_query_arg(array('frm_action' => 'edit', 'entry' => $entry_id), get_permalink($page_id));
               
               if($label)
                   $link = '<a href="'. $link .'" class="'. $class.'">'. $label .'</a>';
                   
               return $link;
            }
            
            $action = (isset($_POST) && isset($_POST['frm_action'])) ? 'frm_action' : 'action';
            if (isset($_POST) and isset($_POST[$action]) and ($_POST[$action] =='update') and isset($_POST['form_id']) and ($_POST['form_id'] == $form_id) and isset($_POST['id']) and ($_POST['id'] == $entry_id)){
                global $frm_created_entry;
                $errors = $frm_created_entry[$form_id]['errors'];
                
                if($errors)
                    return FrmAppController::get_form_shortcode(array('id' => $form_id, 'entry_id' => $entry_id));
                
                $link .= "<script type='text/javascript'>window.onload= function(){var frm_pos=jQuery('#". $prefix . $entry_id ."').offset();window.scrollTo(frm_pos.left,frm_pos.top);}</script>";
            }

                
            if(!$html_id)
                $html_id = "frm_edit_{$entry_id}";
              
            $frm_forms_loaded[] = true;  
            $link .= "<a href='javascript:frmEditEntry($entry_id,\"". FRM_SCRIPT_URL."\",\"$prefix\",$page_id,$form_id,\"$cancel\",\"$class\")' class='frm_edit_link $class' id='$html_id'>$label</a>\n";
        }

        return $link;
    }
    
    function entry_update_field($atts){
        global $frm_editing_entry, $post, $frmdb;
        
        extract(shortcode_atts(array(
            'id' => $frm_editing_entry, 'field_id' => false, 'form_id' => false, 
            'label' => 'Update', 'class' => '', 'value' => '', 'message' => ''
        ), $atts));
        
        $link = '';
        $entry_id = (int)($id and is_numeric($id)) ? $id : FrmAppHelper::get_param('entry', false);
        
        if(!$entry_id or empty($entry_id))
            return $link;
            
        if(!$form_id)
            $form_id = (int)$frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'form_id');
        
        if(!FrmProEntry::user_can_edit($entry_id, $form_id))
            return $link;
        
        if(!is_numeric($field_id))
            $field_id = $frmdb->get_var($frmdb->fields, array('field_key' => $field_id));
        
        if(!$field_id)
            return 'no field'. $link;
        
        $link = "<a href='javascript:frmUpdateField($entry_id,$field_id,\"$value\",\"$message\",\"". FRM_SCRIPT_URL."\")' id='frm_update_field_{$entry_id}_{$field_id}' class='frm_update_field_link $class'>$label</a>";
        
        return $link;
    }
    
    function entry_delete_link($atts){
        global $frm_editing_entry, $post, $frm_forms_loaded;
        extract(shortcode_atts(array(
            'id' => $frm_editing_entry, 'label' => 'Delete', 
            'confirm' => __('Are you sure you want to delete that entry?', 'formidable'), 
            'class' => '', 'page_id' => (($post) ? $post->ID : 0), 'html_id' => false, 'prefix' => ''
        ), $atts));
        
        $frm_forms_loaded[] = true;
            
        $link = '';
        $entry_id = ($id and is_numeric($id)) ? $id : (is_admin() ? FrmAppHelper::get_param('id', false) : FrmAppHelper::get_param('entry', false));

        if($entry_id and !empty($entry_id)){
            if(empty($prefix)){
                $action = FrmAppHelper::get_param('frm_action');
                if($action == 'destroy'){
                    $entry_key = FrmAppHelper::get_param('entry');
                    if(is_numeric($entry_key) and $entry_key == $entry_id){
                        $link = FrmProEntriesController::ajax_destroy(false, false, false);
                        if(!empty($link)){
                            $new_link = '<div class="frm_message">'. $link .'</div>';
                            if($link == __('Your entry was successfully deleted', 'formidable'))    
                                return $new_link;
                            else
                                $link = $new_link;
                                
                            unset($new_link);
                        }
                    }
                }
                    
                $link .= "<a href='". add_query_arg(array('frm_action' => 'destroy', 'entry' => $entry_id), get_permalink($page_id)) ."' class='$class' onclick='return confirm(\"". $confirm ."\")'>$label</a>\n";
            }else{
                if(!$html_id)
                    $html_id = "frm_delete_{$entry_id}";
              
                $link = "<a href='javascript:frmDeleteEntry($entry_id,\"". FRM_SCRIPT_URL."\",\"$prefix\")' class='frm_delete_link $class' id='$html_id' onclick='return confirm(\"". $confirm ."\")'>$label</a>\n";
            }
        }
            
        return $link;
    }
    
    /* AJAX */
    function set_cookie($entry_id, $form_id){
        global $frm_form;
        $form = $frm_form->getOne($form_id);
        $form->options = maybe_unserialize($form->options);
        $expiration = (isset($form->options['cookie_expiration'])) ? ((float)$form->options['cookie_expiration'] *60*60) : 30000000; 
        $expiration = apply_filters('frm_cookie_expiration', $expiration, $form_id, $entry_id);
        setcookie('frm_form'.$form_id.'_' . COOKIEHASH, current_time('mysql', 1), time() + $expiration, COOKIEPATH, COOKIE_DOMAIN);
    }
    
    function ajax_create(){
        global $frm_entry;
        
        $errors = $frm_entry->validate($_POST, array('file','rte','captcha'));
        if(empty($errors)){
            echo false;
        }else{
            $errors = str_replace('"', '&quot;', stripslashes_deep($errors));
            $obj = array();
            foreach($errors as $field => $error){
                $field_id = str_replace('field', '', $field);
                $obj[$field_id] = $error;
            }
            echo json_encode($obj);
        }
        
        die();
    }
    
    function ajax_update(){
        return $this->ajax_create();
    }
    
    function ajax_destroy($form_id=false, $ajax=true, $echo=true){
        global $user_ID, $frmdb, $frm_entry, $frm_deleted_entries;
        
        $entry_key = FrmAppHelper::get_param('entry');
        if(!$form_id)
            $form_id = FrmAppHelper::get_param('form_id');
        
        if(!$entry_key)
            return;
            
        if(is_array($frm_deleted_entries) and in_array($entry_key, $frm_deleted_entries))
            return;
            
        $where = array();
        if(!current_user_can('frm_delete_entries'))
            $where['user_id'] = $user_ID;
            
        if(is_numeric($entry_key))
            $where['id'] = $entry_key;
        else
            $where['item_key'] = $entry_key;
        
        $entry = $frmdb->get_one_record( $frmdb->entries, $where, 'id, form_id' );
        
        if($form_id and $entry->form_id != (int)$form_id)
            return;
        
        $entry_id = $entry->id;
        
        apply_filters('frm_allow_delete', $entry_id, $entry_key, $form_id);

        if(!$entry_id){
            $message = __('There was an error deleting that entry', 'formidable');
            if($echo)
                echo '<div class="frm_message">'. $message .'</div>';
        }else{
            $frm_entry->destroy( $entry_id );
            if(!$frm_deleted_entries)
                $frm_deleted_entries = array();
            $frm_deleted_entries[] = $entry_id;
            
            if($ajax){
                if($echo)
                    echo $message = 'success';
            }else{
                $message = __('Your entry was successfully deleted', 'formidable');
                
                if($echo)
                    echo '<div class="frm_message">'. $message .'</div>';
            }
        }
        
        return $message;
    }
    
    function edit_entry_ajax($id, $entry_id=false, $post_id=false){
        global $frm_ajax_edit;
        $frm_ajax_edit = ($entry_id) ? $entry_id : true;

        if($post_id and is_numeric($post_id)){
            global $post;
            if(!$post)
                $post = get_post($post_id);
        }

        
        global $wp_scripts;
        $wp_scripts->do_items( array('formidable') );
        echo "<script type='text/javascript'>
//<![CDATA[
jQuery(document).ready(function($){
$('#frm_form_". $id ."_container .frm-show-form').submit(function(e){e.preventDefault();window.frmGetFormErrors(this,'". FRM_SCRIPT_URL ."');});
});
//]]>
</script>";
        echo FrmAppController::get_form_shortcode(compact('id', 'entry_id'));

        $frm_ajax_edit = false;
        //if(!isset($_POST) or (!isset($_POST['action']) and !isset($_POST['frm_action])))
        //    echo FrmProEntriesController::footer_js();
        
        die();
    }
    
    function update_field_ajax($entry_id, $field_id, $value){
        global $frmdb, $wpdb, $frm_field;
        
        $entry_id = (int)$entry_id;
        
        if(!$entry_id)
            return false;
           
        $where = '';
        if(is_numeric($field_id))
            $where .= "fi.id=$field_id";
        else
            $where .= "field_key='$field_id'";
            
        $field = $frm_field->getAll($where, '', ' LIMIT 1');
    
        if(!$field or !FrmProEntry::user_can_edit($entry_id, $field->form_id))
            return false;
        
        $post_id = false;
        
        $field->field_options = maybe_unserialize($field->field_options);
        if(isset($field->field_options['post_field']) and !empty($field->field_options['post_field']))
            $post_id = $frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'post_id');
            
        if(!$post_id){
            $updated = $wpdb->update( $frmdb->entry_metas, 
                array('meta_value' => $value), 
                array('item_id' => $entry_id, 'field_id' => $field_id) 
            );

            if(!$updated){
                $wpdb->query($wpdb->prepare("DELETE FROM $frmdb->entry_metas WHERE item_id = %d and field_id = %d", $entry_id, $field_id));
                $updated = FrmEntryMeta::add_entry_meta($entry_id, $field_id, '', $value);
            }
            wp_cache_delete( $entry_id, 'frm_entry');
        }else{
            switch($field->field_options['post_field']){
                case 'post_custom':
                    $updated = update_post_meta($post_id, $field->field_options['post_custom'], maybe_serialize($value));
                break;
                case 'post_category':
                    $taxonomy = (isset($field->field_options['taxonomy']) and !empty($field->field_options['taxonomy'])) ? $field->field_options['taxonomy'] : 'category';
                    $updated = wp_set_post_terms( $post_id, $value, $taxonomy );
                break;
                default:
                    $post = get_post($post_id, ARRAY_A);
                    $post[$field->field_options['post_field']] = maybe_serialize($value);
                    $updated = wp_insert_post( $post );
            }
        }
        do_action('frm_after_update_field', compact('entry_id', 'field_id', 'value'));
        return $updated;
    }
    
    function send_email($entry_id, $form_id, $type){
        if(current_user_can('frm_view_forms') or current_user_can('frm_edit_forms')){
            if($type=='autoresponder')
                $sent_to = FrmProNotification::autoresponder($entry_id, $form_id);
            else
                $sent_to = FrmProNotification::entry_created($entry_id, $form_id);
            
            if(is_array($sent_to))
                echo implode(',', $sent_to);
            else
                echo $sent_to;
        }else{
            _e('No one! You do not have permission', 'formidable');
        }
    }
    
}

?>

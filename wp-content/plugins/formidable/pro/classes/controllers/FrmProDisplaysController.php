<?php
/**
 * @package Formidable
 */
 
class FrmProDisplaysController{

    function FrmProDisplaysController(){
        add_action('admin_menu', array( &$this, 'menu' ), 21);
        add_filter('frm_nav_array', array( &$this, 'frm_nav'), 2);
        add_filter('the_content', array(&$this, 'get_content'), 8);
        add_action('wp_ajax_frm_get_field_tags', array(&$this, 'get_tags') );
        add_action('wp_ajax_frm_get_entry_select', array(&$this, 'get_entry_select') );
        add_action('wp_ajax_frm_add_where_row', array(&$this, 'get_where_row'));
        add_action('wp_ajax_frm_add_where_options', array(&$this, 'get_where_options'));
        add_filter('frm_before_display_content', array(&$this, 'calendar_header'), 10, 3);
        add_filter('frm_display_entries_content', array(&$this, 'build_calendar'), 10, 5);
        add_filter('frm_after_display_content', array(&$this, 'calendar_footer'), 10, 3);
        add_filter('set-screen-option', array(&$this, 'save_per_page'), 10, 3);
        
        //Shortcodes
        add_shortcode('display-frm-data', array(&$this, 'get_shortcode'), 1);
    }
    
    function menu(){
        global $frm_settings;
        
        add_submenu_page('formidable', 'Formidable | '. __('Custom Displays', 'formidable'), __('Custom Displays', 'formidable'), 'frm_edit_displays', 'formidable-entry-templates', array(&$this, 'route'));
        
        if(class_exists('WP_List_Table') and (!isset($_GET['frm_action']) or $_GET['frm_action'] != 'edit')){
            add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-entry-templates_columns', array(&$this, 'manage_columns'));
            add_filter('manage_'. sanitize_title($frm_settings->menu) .'_page_formidable-entry-templates_sortable_columns', array(&$this, 'sortable_columns'));
            add_filter('get_user_option_manage'. sanitize_title($frm_settings->menu) .'_page_formidable-entry-templatescolumnshidden', array(&$this, 'hidden_columns'));
        }
    }
    
    function frm_nav($nav){
        if(current_user_can('frm_edit_displays')){
            $nav['formidable-entry-templates'] = __('Custom Displays', 'formidable');
            //$nav['formidable-entry-templates&frm_action=new'] = __('New Custom Display', 'formidable');
        }
        return $nav;
    }
    
    function get_content($content){
        global $post, $frmpro_display;
        if(!$post) return $content;

        $display = $entry_id = false;
        if(is_single() or is_page()){
            global $frmdb, $frmprodb;
            //get entry with post_id
            $entry = $frmdb->get_one_record($frmdb->entries, array('post_id' => $post->ID), $fields='id,item_key,form_id');
            if($entry){
                $display = FrmProDisplay::getAll( "form_id=".$entry->form_id." and show_count in ('single', 'dynamic', 'calendar')", '', ' LIMIT 1' );
                $entry_id = $entry->id;
            }
        }
        
        if(!$display)
            $display = $frmpro_display->getAll("insert_loc != 'none' and post_id=".$post->ID, '', ' LIMIT 1');
            
        if ($display){
            global $frm_displayed, $frm_display_position;
            
            $display->options = maybe_unserialize($display->options);
            if(!isset($display->options['insert_pos']))
                $display->options['insert_pos'] = 1;
                
            if(!$frm_displayed)
                $frm_displayed = array();
            
            if(!$frm_display_position)
                $frm_display_position = array();
            
            if(!isset($frm_display_position[$display->id]))
                $frm_display_position[$display->id] = 0;
            
            $frm_display_position[$display->id]++;
            
            //make sure this isn't loaded multiple times but still works with themes and plugins that call the_content multiple times
            if(in_the_loop() and !in_array($display->id, (array)$frm_displayed) and $frm_display_position[$display->id] >= (int)$display->options['insert_pos']){ 
                $entry_id = (in_array($display->show_count, array('dynamic', 'calendar')) and $display->type == 'display_key') ? ($entry_id ? $entry->item_key : $entry_id) : $entry_id;
                $frm_displayed[] = $display->id;  
                $content = $this->get_display_data($display, $content, $entry_id); 
            }   
        }

        return $content;
    }
    
    function new_form(){
        global $frmpro_display, $frmpro_settings, $frm_settings, $frm_siteurl, $frm_ajax_url;
          
        $values = FrmProDisplaysHelper::setup_new_vars();
        $submit = __('Create', 'formidable');
        require_once(FRMPRO_VIEWS_PATH.'/displays/new.php');
    }
    
    function create(){
        global $frmpro_display, $frmpro_settings, $frm_settings, $frm_siteurl, $frm_ajax_url;
        $errors = $frmpro_display->validate($_POST);
        
        if( count($errors) > 0 ){
            $submit = __('Create', 'formidable');
            $values = FrmProDisplaysHelper::setup_new_vars();
            require_once(FRMPRO_VIEWS_PATH.'/displays/new.php');
        }else{
            $_SERVER['REQUEST_URI'] = str_replace('&frm_action=new', '', $_SERVER['REQUEST_URI']);
            if($record = $frmpro_display->create( $_POST ))
                $message = __('Custom Display was Successfully Created', 'formidable');
            else
                $message = __('Oops! There was a problem saving your Custom Display. Please try deactivating and reactivating Formidable to correct the problem.', 'formidable');
            return $this->display_list($this->get_params(), $message);
        }
         
    }
    
    function create_from_template($path){
        global $frmpro_display;
        $templates = glob($path."/*.php");
        
        for($i = count($templates) - 1; $i >= 0; $i--){
            $filename = str_replace('.php', '', str_replace($path.'/', '', $templates[$i]));
            $display = $frmpro_display->getAll(array('display_key' => $filename), '', 1);
            
            $values = FrmProDisplaysHelper::setup_new_vars();
            $values['display_key'] = $filename;
            
            include_once($templates[$i]);
        }
    }
    
    function edit(){
        $id = FrmAppHelper::get_param('id');
        return $this->get_edit_vars($id);
    }
    
    function update(){
        global $frmpro_display, $frmpro_settings;
        $errors = $frmpro_display->validate($_POST);
        $id = FrmAppHelper::get_param('id');
        if( count($errors) > 0 ){
            return $this->get_edit_vars($id, $errors);
        }else{
            $record = $frmpro_display->update( $id, $_POST );
            $message = __('Custom Display was Successfully Updated', 'formidable');
            return $this->get_edit_vars($id, '', $message);
        }
    }
    
    function duplicate(){
        global $frmpro_display;
        
        $params = $this->get_params();
        $record = $frmpro_display->duplicate( $params['id'] );
        $message = __('Custom Display was Successfully Copied', 'formidable');
        if ($record)
            return $this->get_edit_vars($record, '', $message);
        else
            return $this->display_list($params, __('There was a problem creating new Entry Display settings.', 'formidable'));
    }
    
    function destroy(){
        global $frmpro_display;
        $params = $this->get_params();
        $message = '';
        if ($frmpro_display->destroy( $params['id'] ))
            $message = __('Custom Display was Successfully Deleted', 'formidable');
        $this->display_list($params, $message, '', 1);
    }
    
    function bulk_actions($action=''){
        $params = $this->get_params();
        $errors = '';
        $bulkaction = '-1';
        
        if($action == 'list-form'){
            if($_POST['bulkaction'] != '-1')
                $bulkaction = $_POST['bulkaction'];
            else if($_POST['bulkaction2'] != '-1')
                $bulkaction = $_POST['bulkaction2'];
        }else{
            $bulkaction = str_replace('bulk_', '', $action);
        }

        if (empty($_REQUEST['item-action'])){
            $errors[] = __('No displays were specified', 'formidable');
        }else{
            $items = $_REQUEST['item-action'];
            
            if($bulkaction == 'delete'){
                if(!current_user_can('frm_edit_displays')){
                    global $frm_settings;
                    $errors[] = $frm_settings->admin_permission;
                }else{
                    global $frmpro_display;
                    if(is_array($items)){
                        if($bulkaction == 'delete'){
                            foreach($items as $item_id)
                                $frmpro_display->destroy($item_id);
                        }
                    }
                }
            }else if($bulkaction == 'export'){
                $controller = 'displays';
                $ids = $items;
                $ids = implode(',', $ids);
                include_once(FRMPRO_VIEWS_PATH.'/shared/xml.php');
            }
        }
        $this->display_list($params, '', false, false, $errors);
    }

    function get_where_row(){
        $this->add_where_row($_POST['where_key'], $_POST['form_id']);
        die();
    }
    
    function add_where_row($where_key='', $form_id='', $where_field='', $where_is='', $where_val=''){
        require(FRMPRO_VIEWS_PATH .'/displays/where_row.php');
    }
    
    function get_where_options(){
        $this->add_where_options($_POST['field_id'],$_POST['where_key']);
        die();
    }
    
    function add_where_options($field_id, $where_key, $where_val=''){
        global $frm_field;
        if(is_numeric($field_id)){
            $field = $frm_field->getOne($field_id);
            $field->field_options = maybe_unserialize($field->field_options);
        }
        
        require(FRMPRO_VIEWS_PATH .'/displays/where_options.php');
    }
    
    function calendar_header($content, $display, $show='one'){
        if($display->show_count != 'calendar' or $show == 'one') return $content;
        
        global $frm_load_css, $wp_locale;
        $frm_load_css = true;
        
        $year = FrmAppHelper::get_param('frmcal-year', date_i18n('Y')); //4 digit year
        $month = FrmAppHelper::get_param('frmcal-month', date_i18n('m')); //Numeric month without leading zeros
        
        $month_names = $wp_locale->month;
        
        $prev_year = $next_year = $year;

        $prev_month = $month-1;
        $next_month = $month+1;

        if ($prev_month == 0 ) {
            $prev_month = 12;
            $prev_year = $year - 1;
        }
        
        if ($next_month == 13 ) {
            $next_month = 1;
            $next_year = $year + 1;
        }
        
        if($next_month < 10)
            $next_month = '0'. $next_month;
        
        if($prev_month < 10)
            $prev_month = '0'. $prev_month;
        
        ob_start();
        include(FRMPRO_VIEWS_PATH.'/displays/calendar-header.php');
        $content .= ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    function build_calendar($new_content, $entries, $shortcodes, $display, $show='one'){
        if(!$display or $display->show_count != 'calendar') return $new_content;
        
        global $frm_entry_meta, $wp_locale;
        
        $display_options = maybe_unserialize($display->options);
        
        $current_year = date_i18n('Y');
        $current_month = date_i18n('n');
        
        $year = FrmAppHelper::get_param('frmcal-year', date('Y')); //4 digit year
        $month = FrmAppHelper::get_param('frmcal-month', $current_month); //Numeric month without leading zeros
        
        $timestamp = mktime(0, 0, 0, $month, 1, $year);
        $maxday = date('t', $timestamp); //Number of days in the given month
        $this_month = getdate($timestamp);
        $startday = $this_month['wday'];
        
        if($current_year == $year and $current_month == $month)
            $today = date_i18n('j');
        
        $cal_end = $maxday+$startday;
        $t = ($cal_end > 35) ? 42 : (($cal_end == 28) ? 28 : 35);
        $extrarows = $t-$maxday-$startday;
        
        $show_entres = false;
        $daily_entries = array();
        
        if(isset($display_options['date_field_id']) and is_numeric($display_options['date_field_id']))
            $field = FrmField::getOne($display_options['date_field_id']);
            
        if(isset($display_options['edate_field_id']) and is_numeric($display_options['edate_field_id']))
            $efield = FrmField::getOne($display_options['edate_field_id']);
        else
            $efield = false;
            
        foreach ($entries as $entry){
            if(isset($display_options['date_field_id']) and is_numeric($display_options['date_field_id'])){
                if(isset($entry->metas))
                    $date = isset($entry->metas[$display_options['date_field_id']]) ? $entry->metas[$display_options['date_field_id']] : false;
                else
                    $date = $frm_entry_meta->get_entry_meta_by_field($entry->id, $display_options['date_field_id']);
                    
                if($entry->post_id and !$date){
                    if($field){
                        $field->field_options = maybe_unserialize($field->field_options);
                        if($field->field_options['post_field']){
                            $date = FrmProEntryMetaHelper::get_post_value($entry->post_id, $field->field_options['post_field'], $field->field_options['custom_field'], array('form_id' => $display->form_id, 'type' => $field->type, 'field' => $field));
                        }
                    }
                }
            }else if($display_options['date_field_id'] == 'updated_at'){
                $date = $entry->updated_at;
                $i18n = true;
            }else{
                $date = $entry->created_at;
                $i18n = true;
            }
            if(empty($date)) continue;
            
            if(isset($il8n) and $il8n)
                $date = date_i18n('Y-m-d', strtotime($date));
            else
                $date = date('Y-m-d', strtotime($date));
                
            unset($i18n);
            $dates = array($date);
            
            if(isset($display_options['edate_field_id']) and !empty($display_options['edate_field_id'])){
                if(is_numeric($display_options['edate_field_id']) and $efield){
                    $edate = FrmProEntryMetaHelper::get_post_or_meta_value($entry, $efield);
                    
                    if($efield and $efield->type == 'number' and is_numeric($edate))
                        $edate = date('Y-m-d', strtotime('+'. $edate .' days', strtotime($date)));
                    
                }else if($display_options['edate_field_id'] == 'updated_at'){
                    $edate = date_i18n('Y-m-d', strtotime($entry->updated_at));
                }else{
                    $edate = date_i18n('Y-m-d', strtotime($entry->created_at));
                }

                if($edate and !empty($edate)){
                    $from_date = strtotime($date);                    
                    $to_date = strtotime($edate);
                    
                    if(!empty($from_date) and $from_date < $to_date){
                        for($current_ts = $from_date; $current_ts <= $to_date; $current_ts += (60*60*24))
                            $dates[] = date('Y-m-d', $current_ts);
                        unset($current_ts);
                    }
                    
                    unset($from_date);
                    unset($to_date);
                }
                unset($edate);
                
                $used_entries = array();
            }
            unset($date);
            
            $dates = apply_filters('frm_show_entry_dates', $dates, $entry);
            
            for ($i=0; $i<($maxday+$startday); $i++){
                $day = $i - $startday + 1;

                if(in_array(date('Y-m-d', strtotime("$year-$month-$day")), $dates)){
                    $show_entres = true;
                    $daily_entres[$i][] = $entry;
                }
                    
                unset($day);
            }
            unset($dates);
        }
        
        $day_names = $wp_locale->weekday_abbrev;
        $day_names = FrmProAppHelper::reset_keys($day_names); //switch keys to order

        ob_start();
        include(FRMPRO_VIEWS_PATH.'/displays/calendar.php');
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    function calendar_footer($content, $display, $show='one'){
        if($display->show_count != 'calendar' or $show == 'one') return $content;
        
        ob_start();
        include(FRMPRO_VIEWS_PATH.'/displays/calendar-footer.php');
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
    
    function manage_columns($columns){
        $columns['cb'] = '<input type="checkbox" />';
        $columns['id'] = 'ID';
        $columns['name'] = __('Name', 'formidable');
        $columns['description'] = __('Description', 'formidable');
        $columns['form_id'] = __('Form', 'formidable');
        $columns['show_count'] = __('Entry', 'formidable');
        $columns['post_id'] = __('Page', 'formidable');
        $columns['content'] = __('Content', 'formidable');
        $columns['dyncontent'] = __('Dynamic Content', 'formidable');
        $columns['created_at'] = __('Date', 'formidable');
        $columns['display_key'] = __('Key', 'formidable');
        $columns['shortcode'] = __('ShortCode', 'formidable');
        

        add_screen_option( 'per_page', array('label' => __('Custom Displays', 'formidable'), 'default' => 20, 'option' => 'formidable_page_formidable_entry_templates_per_page') );
        
        return $columns;
    }
    
    function save_per_page($save, $option, $value){
        if($option == 'formidable_page_formidable_entry_templates_per_page')
            $save = (int)$value;
        return $save;
    }
    
    function sortable_columns(){
        return array(
            'id'            => 'id',
            'name'          => 'name',
            'description'   => 'description',
            'display_key'   => 'display_key',
            'show_count'    => 'show_count',
            'content'       => 'content',
            'dyncontent'    => 'dyncontent',
            'created_at'    => 'created_at',
            'shortcode'     => 'id'
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

        $result[] = 'content';
        $result[] = 'dyncontent';
        $result[] = 'created_at';
                
        return $result;
    }

    function display_list($params=false, $message='', $page_params_ov = false, $current_page_ov = false, $errors = array()){
        global $wpdb, $frmprodb, $frmpro_display, $frm_form, $frm_app_helper, $frm_page_size;
        
        if(!$params)
            $params = $this->get_params();
            
        if($message=='')
            $message = FrmAppHelper::frm_get_main_message();

        $form = $params['form'];
        
        if(class_exists('WP_List_Table')){
            require_once( FRMPRO_PATH .'/classes/helpers/FrmProListHelper.php' );

            $wp_list_table = new FrmProListHelper(array('singular' => 'display', 'plural' => 'displays', 'table_name' => $frmprodb->displays, 'page_name' => 'entry-templates', 'params' => $params));

            $pagenum = $wp_list_table->get_pagenum();

            $wp_list_table->prepare_items();

            $total_pages = $wp_list_table->get_pagination_arg( 'total_pages' );
            if ( $pagenum > $total_pages && $total_pages > 0 ) {
            	wp_redirect( add_query_arg( 'paged', $total_pages ) );
            	exit;
            }
            
        }else{
            $page_params = '&action=0&frm_action=0';
            $where_clause = '';

            $form_vars = $this->get_form_sort_vars($params, $where_clause);

            $current_page = ($current_page_ov) ? $current_page_ov : $params['paged'];
            $page_params = ($page_params_ov) ? $page_params_ov : $form_vars['page_params'];

            $sort_str = $form_vars['sort_str'];
            $sdir_str = $form_vars['sdir_str'];
            $search_str = $form_vars['search_str'];
            $form = $form_vars['form'];

            $record_count = $frm_app_helper->getRecordCount($form_vars['where_clause'], $frmprodb->displays);
            $page_count = $frm_app_helper->getPageCount($frm_page_size, $record_count, $frmprodb->displays);
            $displays = $frm_app_helper->getPage($current_page, $frm_page_size, $form_vars['where_clause'], $form_vars['order_by'], $frmprodb->displays);
            $page_last_record = $frm_app_helper->getLastRecordNum($record_count,$current_page,$frm_page_size);
            $page_first_record = $frm_app_helper->getFirstRecordNum($record_count,$current_page,$frm_page_size);
        }
        require_once(FRMPRO_VIEWS_PATH.'/displays/list.php');
    }
    
    function get_form_sort_vars($params,$where_clause = ''){
        $order_by = '';
        $page_params = '';

        // These will have to work with both get and post
        $sort_str = $params['sort'];
        $sdir_str = $params['sdir'];
        $search_str = $params['search'];
        $form = $params['form'];

        // Insert search string
        if(!empty($search_str)){
            $search_params = explode(" ", $search_str);

            foreach($search_params as $search_param){
                if(!empty($where_clause))
                    $where_clause .= " AND";

                $where_clause .= " (name like '%$search_param%' OR description like '%$search_param%' OR created_at like '%$search_param%' OR content like '%$search_param%' OR dyncontent like '%$search_param%')";
            }

            $page_params .="&search=$search_str";
        }

        // make sure page params stay correct
        if(!empty($sort_str))
            $page_params .="&sort=$sort_str";

        if(!empty($sdir_str))
            $page_params .= "&sdir=$sdir_str";
        
        if(!empty($form)){
            $page_params .= "&form=$form";
            if(!empty($where_clause))
                $where_clause .= " AND";
            $where_clause .= " form_id=". (int)$form;
        }

        // Add order by clause
        switch($sort_str){
            case "id":
            case "name":
            case "description":
            case "display_key":
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

        return compact('order_by', 'sort_str', 'sdir_str', 'search_str', 'where_clause', 'page_params', 'form');
    }

    function get_edit_vars($id, $errors = '', $message=''){
        global $frmpro_display, $frmpro_displays_helper, $frmpro_settings, $frm_settings, $frm_siteurl, $frm_ajax_url;
        $record = $frmpro_display->getOne( $id );
        $values = $frmpro_displays_helper->setup_edit_vars($record);
        $submit = __('Update', 'formidable');
        require_once(FRMPRO_VIEWS_PATH.'/displays/edit.php');
    }
    
    
    function get_tags(){
        $target_id = FrmAppHelper::get_param('target_id', 'content');
        FrmProFieldsHelper::get_shortcode_select($_POST['form_id'], $target_id);
        die();
    }
    
    function get_entry_select(){
        echo FrmEntriesHelper::entries_dropdown($_POST['form_id'], 'entry_id');
        die();
    }
    
    function get_params(){
        $values = array();
        foreach (array('template' => 0, 'id' => '', 'paged' => 1, 'form' => '', 'search' => '', 'sort' => '', 'sdir' => '') as $var => $default)
            $values[$var] = FrmAppHelper::get_param($var, $default);

        return $values;
    }
    
    /* ShortCodes */
    function get_shortcode($atts){
        global $frmpro_display;
        $defaults = array(
            'id' => '', 'entry_id' => '', 'filter' => false, 
            'user_id' => false, 'limit' => '', 'page_size' => '', 
            'order_by' => '', 'order' => '', 'get' => '', 'get_value' => ''
        );
        
        extract(shortcode_atts($defaults, $atts));
        
        //if (is_numeric($id))
            $display = $frmpro_display->getOne($id);
        
        $user_id = FrmProAppHelper::get_user_id_param($user_id);
        
        if(!empty($get))
            $_GET[$get] = $get_value;
            
        foreach($defaults as $unset => $val){
            unset($atts[$unset]);
            unset($unset);
            unset($val);
        }
        
        foreach($atts as $att => $val){
            $_GET[$att] = $val;
            unset($att);
            unset($val);
        }
        
        if ($display)    
            return FrmProDisplaysController::get_display_data($display, '', $entry_id, compact('filter', 'user_id', 'limit', 'page_size', 'order_by', 'order')); 
        else
            return __('That is not a valid custom display ID', 'formidable');
    }
    
    function custom_display($id){
        global $frmpro_display;
        if ($display = $frmpro_display->getOne($id))    
            return $this->get_display_data($display);
    }
    
    function get_display_data($display, $content='', $entry_id=false, $extra_atts=array()){
        global $frmpro_display, $frm_entry, $frmpro_settings, $frm_entry_meta, $frm_forms_loaded;
        
        $frm_forms_loaded[] = true;
        
        $defaults = array(
        	'filter' => false, 'user_id' => '', 'limit' => '',
        	'page_size' => '', 'order_by' => '', 'order' => ''
        );

        extract(wp_parse_args( $extra_atts, $defaults ));

        if (FrmProAppHelper::rewriting_on() && $frmpro_settings->permalinks )
            $this->parse_pretty_entry_url();
   
        if (is_numeric($display->entry_id) && $display->entry_id > 0 and !$entry_id)
            $entry_id = $display->entry_id;
        
        $entry = false;

        $show = 'all';
        if (in_array($display->show_count, array('dynamic', 'calendar', 'one'))){
            $one_param = (isset($_GET['entry'])) ? $_GET['entry'] : $entry_id;
            $get_param = (isset($_GET[$display->param])) ? $_GET[$display->param] : (($display->show_count == 'one') ? $one_param : $entry_id);
            unset($one_param);
            
            if ($get_param){
                $where_entry = array('it.form_id' => $display->form_id);
                if(($display->type == 'id' or $display->show_count == 'one') and is_numeric($get_param))
                    $where_entry['it.id'] = $get_param;
                else
                    $where_entry['it.item_key'] = $get_param;
                $entry = $frm_entry->getAll($where_entry, '', 1, 0);
                if($entry)
                    $entry = reset($entry);
                    
                if($entry and $entry->post_id){
                    //redirect to single post page if this entry is a post
                    global $post;
                    if(in_the_loop() and $display->show_count != 'one' and !is_single($entry->post_id) and $post->ID != $entry->post_id){
                        $this_post = get_post($entry->post_id);
                        if(in_array($this_post->post_status, array('publish', 'private')))
                            die('<script type="text/javascript">window.location="'. get_permalink($entry->post_id) .'"</script>');
                    }
                }
            }
            unset($get_param);
        }

        if($entry and in_array($display->show_count, array('dynamic', 'calendar'))){    
            $new_content = stripslashes($display->dyncontent);
            $show = 'one';
        }else{
            $new_content = stripslashes($display->content);
        }
    	
        $show = ($display->show_count == 'one' or ($entry_id and is_numeric($entry_id))) ? 'one' : $show;
        $shortcodes = FrmProDisplaysHelper::get_shortcodes($new_content, $display->form_id); 
        
        $pagination = '';
            
        if ($entry and $entry->form_id == $display->form_id){
            $display_content = FrmProFieldsHelper::replace_shortcodes($new_content, $entry, $shortcodes, $display, $show);
        }else{
            global $frmdb, $wpdb;
            
            $options = maybe_unserialize($display->options);
            $empty_msg = '<div class="frm_no_entries">'. (isset($options['empty_msg']) ? stripslashes($options['empty_msg']) : '') .'</div>';
            $display_content = '';
            if($show == 'all')
                $display_content .= isset($options['before_content']) ? stripslashes($options['before_content']) : '';
                
            $display_content = apply_filters('frm_before_display_content', $display_content, $display, $show);
            
            $where = 'it.form_id='.$display->form_id;
            
            $form_posts = $frmdb->get_records($frmdb->entries, array('form_id' => $display->form_id, 'post_id >' => 1), '', '', 'id,post_id');
            $entry_ids = $frmdb->get_col($frmdb->entries, array('form_id' => $display->form_id), 'id');
            $after_where = false;
            
            if($user_id and !empty($user_id)){
                $user_id = FrmProAppHelper::get_user_id_param($user_id);
                $uid_used = false;
            }
            
            if(isset($options['where']) and !empty($options['where'])){
                $options['where'] = apply_filters('frm_custom_where_opt', $options['where'], array('display' => $display, 'entry' => $entry));
                $continue = false;
                foreach($options['where'] as $where_key => $where_opt){
                    $where_val = isset($options['where_val'][$where_key]) ? $options['where_val'][$where_key] : '';

                    if (preg_match("/\[(get|get-(.?))\b(.*?)(?:(\/))?\]/s", $where_val)){
                        $where_val = FrmProFieldsHelper::get_default_value($where_val, false, true, true);
                        
                        //if this param doesn't exist, then don't include it
                        if($where_val == '') {
                            if(!$after_where)
                                $continue = true;
                            continue;
                        }
                    }else{
                        $where_val = FrmProFieldsHelper::get_default_value($where_val, false, true, true);
                    }
                    
                    $continue = false;
                    
                    if($where_val == 'current_user'){
                        if($user_id and is_numeric($user_id)){
                            $where_val = $user_id;
                            $uid_used = true;
                        }else{
                            global $user_ID;
                            $where_val = $user_ID;
                        }
                    }
                    
                    $where_val = do_shortcode($where_val);
                    
                    if(is_array($where_val) and !empty($where_val)){
                        $new_where = '(';
                        if(strpos($options['where_is'][$where_key], 'LIKE') !== false){
                            foreach($where_val as $w){
                                if($new_where != '(')
                                    $new_where .= ',';
                                $new_where .= "'%". esc_sql(like_escape($w)). "%'";
                                unset($w);
                            }
                        }else{
                            foreach($where_val as $w){
                                if($new_where != '(')
                                    $new_where .= ',';
                                $new_where .= "'". esc_sql($w) ."'";
                                unset($w);
                            }
                        }
                        $new_where .= ')';
                        $where_val = $new_where;
                        unset($new_where);
                        
                        if(strpos($options['where_is'][$where_key], '!') === false and strpos($options['where_is'][$where_key], 'not') === false)
                            $options['where_is'][$where_key] = ' in ';
                        else
                            $options['where_is'][$where_key] = ' not in ';
                    }
                    
                    if(is_numeric($where_opt)){
                        $entry_ids = FrmProAppHelper::filter_where($entry_ids, array(
                            'where_opt' => $where_opt, 'where_is' => $options['where_is'][$where_key], 
                            'where_val' => $where_val, 'form_id' => $display->form_id, 'form_posts' => $form_posts, 
                            'after_where' => $after_where, 'display' => $display
                        ));
                        $after_where = true;
                        $continue = false;
                        if(empty($entry_ids))
                            break;
                    }else if($where_opt == 'created_at'){
                        if($where_val == 'NOW')
                            $where_val = current_time('mysql', 1);
                        $where_val = date('Y-m-d H:i:s', strtotime($where_val));
                        $where .= " and it.created_at ". $options['where_is'][$where_key];
                        if(strpos($options['where_is'][$where_key], 'in'))
                            $where .= " $where_val";
                        else
                            $where .= " '". esc_sql($where_val) ."'";
                        $continue = true;
                    }else if($where_opt == 'id' or $where_opt == 'item_key'){
                        $where .= " and it.{$where_opt} ". $options['where_is'][$where_key];
                        if(strpos($options['where_is'][$where_key], 'in'))
                            $where .= " $where_val";
                        else
                            $where .= " '". esc_sql($where_val) ."'";
                        
                        $continue = true;
                    }
                    
                }
                
                if(!$continue and empty($entry_ids)){ 
                    if ($filter)
                        $empty_msg = apply_filters('the_content', $empty_msg);
                    return $content . ' '. $empty_msg;
                }
            }
            
            if($user_id and is_numeric($user_id) and !$uid_used)
                $where .= " AND it.user_id=". (int)$user_id;

            $s = FrmAppHelper::get_param('frm_search', false);
            if ($s){
                $new_ids = FrmProEntriesHelper::get_search_ids($s, $display->form_id);
                
                if($after_where and isset($entry_ids) and !empty($entry_ids))
                    $entry_ids = array_intersect($new_ids, $entry_ids);
                else
                    $entry_ids = $new_ids;
                    
                if(empty($entry_ids)) 
                    return $content . ' '. $empty_msg;
            }
            
            if(isset($entry_ids) and !empty($entry_ids))
                $where .= ' and it.id in ('.implode(',', $entry_ids).')';
            
            if ($entry_id)
                $where .= " and it.id in ($entry_id)";

            if($show == 'one'){
                $limit = ' LIMIT 1';    
            }else if (isset($_GET['frm_cat']) and isset($_GET['frm_cat_id'])){
                //Get fields with specified field value 'frm_cat' = field key/id, 'frm_cat_id' = order position of selected option
                global $frm_field;
                if ($cat_field = $frm_field->getOne($_GET['frm_cat'])){
                    $categories = maybe_unserialize($cat_field->options);

                    if (isset($categories[$_GET['frm_cat_id']]))
                        $cat_entry_ids = $frm_entry_meta->getEntryIds("meta_value='".$categories[$_GET['frm_cat_id']]."' and fi.field_key='$_GET[frm_cat]'");
                    if ($cat_entry_ids)
                        $where .= " and it.id in (".implode(',', $cat_entry_ids).")";
                }
            }
            
            if (is_array($options)){
                if (!empty($limit) and is_numeric($limit))
                    $options['limit'] = (int)$limit;
                    
                if (is_numeric($options['limit'])){
                    $num_limit = (int)$options['limit'];
                    $limit = ' LIMIT '. $options['limit'];
                }
                
                if (!empty($order_by))
                    $options['order_by'] = $order_by;
                    
                if (!empty($order))
                    $options['order'] = $order;
                    
                if (isset($options['order_by']) && $options['order_by'] != ''){
                    /*if( $wpdb->has_cap( 'collation' ) ){
                        $charset_collate = '';
                        if( !empty($wpdb->charset) )
                            $charset_collate .= "DEFAULT CHARACTER SET $wpdb->charset";
                        if( !empty($wpdb->collate) )
                            $charset_collate .= " COLLATE $wpdb->collate";
                    }*/
                    
                    
                    $order = (isset($options['order'])) ? ' '.$options['order'] : '';
                    if ($options['order_by'] == 'rand'){
                        $order_by = ' RAND()';
                    }else if (is_numeric($options['order_by'])){
                        global $frm_entry_meta, $frm_field;
                        $order_field = $frm_field->getOne($options['order_by']);
                        $order_field->field_options = maybe_unserialize($order_field->field_options);
                        
                        $meta_order = ($order_field->type == 'number') ? ' LENGTH(meta_value),' : '';
                        
                        if(isset($order_field->field_options['post_field']) and $order_field->field_options['post_field']){
                            $posts = $form_posts; //$frmdb->get_records($frmdb->entries, array('form_id' => $display->form_id, 'post_id >' => 1), '', '', 'id, post_id');
                            $linked_posts = array();
                            foreach($posts as $post_meta)
                                $linked_posts[$post_meta->post_id] = $post_meta->id;
                            
                            if($order_field->field_options['post_field'] == 'post_custom'){
                                $ordered_ids = $wpdb->get_col("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='". $order_field->field_options['custom_field'] ."' AND post_id in (". implode(',', array_keys($linked_posts)).") ORDER BY meta_value". $order);
                                $metas = array();
                                foreach($ordered_ids as $ordered_id)
                                    $metas[] = array('item_id' => $linked_posts[$ordered_id]);
                                    
                            }else if($order_field->field_options['post_field'] != 'post_category'){
                                $ordered_ids = $wpdb->get_col("SELECT ID FROM $wpdb->posts WHERE ID in (". implode(',', array_keys($linked_posts)).") ORDER BY ".$order_field->field_options['post_field'] .' '. $order);
                                $metas = array();
                                foreach($ordered_ids as $ordered_id)
                                    $metas[] = array('item_id' => $linked_posts[$ordered_id]);
                            }
                        }else{
                            if($order_field->type == 'number'){
                                $query = "SELECT it.*, meta_value +0 as odr FROM $frmdb->entry_metas it LEFT OUTER JOIN $frmdb->fields fi ON it.field_id=fi.id WHERE fi.form_id=$display->form_id and fi.id={$options['order_by']}";
                                if(isset($entry_ids) and !empty($entry_ids))
                                    $query .= " AND it.item_id in (". implode(',', $entry_ids) .")";
                                
                                $query .= " ORDER BY odr $order $limit";
                                
                                if ($limit == ' LIMIT 1')
                                    $metas = $wpdb->get_row($query);
                                else    
                                    $metas = $wpdb->get_results($query);
                            
                            }else{
                                $metas = $frm_entry_meta->getAll('fi.form_id='.$display->form_id.' and fi.id='.$options['order_by'], ' ORDER BY '.$meta_order.' meta_value'.$order); //TODO: add previous $where and $limit
                            }
                        }
                    
                        
                        if (isset($metas) and is_array($metas) and !empty($metas)){
                            if($order_field->type == 'time' and (!isset($order_field->field_options['clock']) or
                                ($order_field->field_options['clock'] == 12))){
                                
                                $new_order = array();
                                foreach($metas as $key => $meta){
                                    $parts = str_replace(array(' PM',' AM'), '', $meta->meta_value);
                                    $parts = explode(':', $parts);
                                    if(is_array($parts)){
                                        if((preg_match('/PM/', $meta->meta_value) and ((int)$parts[0] != 12)) or 
                                            (((int)$parts[0] == 12) and preg_match('/AM/', $meta->meta_value)))
                                            $parts[0] = ((int)$parts[0] + 12);
                                    }
                                    
                                    $new_order[$key] = (int)$parts[0] . $parts[1];
                                    
                                    unset($key);
                                    unset($meta);
                                }
                                
                                //array with sorted times
                                asort($new_order);
                                
                                $final_order = array();
                                foreach($new_order as $key => $time){
                                    $final_order[] = $metas[$key];
                                    unset($key);
                                    unset($time);
                                }
                                
                                $metas = $final_order;
                                unset($final_order);
                            }
                            
                            $rev_order = ($order == 'DESC' or $order == '') ? ' ASC' : ' DESC';
                            foreach ($metas as $meta){
                                $meta = (array)$meta;
                                $order_by .= 'it.id='.$meta['item_id'] . $rev_order.', ';
                            }
                            $order_by = rtrim($order_by, ', ');  
                        }else
                            $order_by .= 'it.created_at'.$order;
                    }else
                        $order_by = 'it.'.$options['order_by'].$order;
                    $order_by = ' ORDER BY '.$order_by;
                }
            }

            if(!empty($page_size) and is_numeric($page_size))
                $options['page_size'] = (int)$page_size;

            if (isset($options['page_size']) && is_numeric($options['page_size'])){
                global $frm_app_helper;
                $current_page = FrmAppHelper::get_param('frm-page', 1);  
                $record_where = ($where == "it.form_id=$display->form_id") ? $display->form_id : $where;
                $record_count = $frm_entry->getRecordCount($record_where);
                if(isset($num_limit) and ($record_count > (int)$num_limit))
                    $record_count = (int)$num_limit;
                
                $page_count = $frm_entry->getPageCount($options['page_size'], $record_count);
                
                $entries = $frm_entry->getPage($current_page, $options['page_size'], $where, $order_by);
                $page_last_record = $frm_app_helper->getLastRecordNum($record_count, $current_page, $options['page_size']);
                $page_first_record = $frm_app_helper->getFirstRecordNum($record_count, $current_page, $options['page_size']);
                if($page_count > 1)
                    $pagination = FrmProDisplaysController::get_pagination_file(FRMPRO_VIEWS_PATH.'/displays/pagination.php', compact('current_page', 'record_count', 'page_count', 'page_last_record', 'page_first_record'));
            }else{
                $entries = $frm_entry->getAll($where, $order_by, $limit, true, false);
            }

            $filtered_content = apply_filters('frm_display_entries_content', $new_content, $entries, $shortcodes, $display, $show);
            if($filtered_content != $new_content){
                $display_content .= $filtered_content;
            }else{
                $odd = 'odd';
                $count = 0;
                if(!empty($entries)){
                    foreach ($entries as $entry){
                        $count++; //TODO: use the count with conditionals
                        $display_content .= apply_filters('frm_display_entry_content', $new_content, $entry, $shortcodes, $display, $show, $odd);
                        $odd = ($odd == 'odd') ? 'even' : 'odd';
                        unset($entry);
                    }
                    unset($count);
                }else{
                    $display_content .= $empty_msg;
                }
            }
            
            if($show == 'all')
                $display_content .= isset($options['after_content']) ? stripslashes($options['after_content']) : '';
        }
            
        $display_content .= apply_filters('frm_after_display_content', $pagination, $display, $show);
        $display_content = FrmProFieldsHelper::get_default_value($display_content, false, true, true);
        
        if ($display->insert_loc == 'after'){
            $content .= $display_content;
        }else if ($display->insert_loc == 'before'){
            $content = $display_content . $content;
        }else{
            if ($filter)
                $display_content = apply_filters('the_content', $display_content);
            $content = $display_content;
        }
            
        return $content;
    }
    
    function parse_pretty_entry_url(){
        global $frm_entry, $wpdb, $post;

        $post_url = get_permalink($post->ID);
        $request_uri = FrmProAppHelper::current_url();
        
        $match_str = '#^'.$post_url.'(.*?)([\?/].*?)?$#';
        
        if(preg_match($match_str, $request_uri, $match_val)){
            // match short slugs (most common)
            if(isset($match_val[1]) and !empty($match_val[1]) and $frm_entry->exists($match_val[1])){
                // Artificially set the GET variable
                $_GET['entry'] = $match_val[1];
            } 
        }
    }
    
    function route(){
        $action = FrmAppHelper::get_param('frm_action');
            
        if($action =='new')
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
            return $this->bulk_actions();     
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
    
    function get_pagination_file($filename, $atts){
        extract($atts);
        if (is_file($filename)) {
            ob_start();
            include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }

}

?>
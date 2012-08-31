<?php

class FrmProFieldsController{
    function FrmProFieldsController(){
        add_filter('frm_show_normal_field_type', array(&$this, 'show_normal_field'), 10, 2);
        add_filter('frm_normal_field_type_html', array(&$this, 'normal_field_html'), 10, 2);
        add_action('frm_show_other_field_type', array(&$this, 'show_other'), 10, 2);
        add_filter('frm_field_type', array( &$this, 'change_type'), 15, 2);
        add_filter('frm_field_value_saved', array( &$this, 'use_field_key_value'), 10, 3);
        add_action('frm_get_field_scripts', array(&$this, 'show_field'));
        add_action('frm_display_added_fields', array(&$this, 'show'));
        add_filter('frm_html_label_position', array(&$this, 'label_position'));
        add_filter('frm_display_field_options', array(&$this, 'display_field_options'));
        add_action('frm_form_fields', array(&$this, 'form_fields'), 10, 2);
        add_action('frm_field_input_html', array(&$this, 'input_html'));
        add_filter('frm_field_classes', array(&$this, 'add_field_class'), 20, 2);
        add_action('frm_add_multiple_opts_labels', array($this, 'add_separate_value_opt_label')); 
        add_action('frm_field_options_form', array(&$this, 'options_form'), 10, 3);
        add_action('wp_ajax_frm_get_field_selection', array(&$this, 'get_field_selection'));
        add_action('wp_ajax_frm_get_field_values', array(&$this, 'get_field_values'));
        add_action('wp_ajax_frm_get_cat_opts', array(&$this, 'get_cat_opts'));
        add_action('wp_ajax_frm_get_title_opts', array(&$this, 'get_title_opts'));
        add_action('frm_date_field_js', array(&$this, 'date_field_js'), 10, 2);
        add_action('wp_ajax_frm_add_field_option',array(&$this, 'add_option'), 5); // run before the add_option in the FrmFieldsController class
        add_action('wp_ajax_frm_add_table_row',array(&$this, 'add_table_row'));
        add_action('wp_ajax_frm_add_logic_row', array(&$this, '_logic_row'));
    }
    
    function &show_normal_field($show, $field_type){
        if (in_array($field_type, array('hidden', 'user_id', 'break')))
            $show = false;
        return $show;
    }
    
    function &normal_field_html($show, $field_type){
        if (in_array($field_type, array('hidden', 'user_id', 'break', 'divider', 'html')))
            $show = false;
        return $show;
    }
    
    function show_other($field, $form){
        $field_name = "item_meta[$field[id]]";
        require(FRMPRO_VIEWS_PATH .'/frmpro-fields/show-other.php');
    }
    
    function &change_type($type, $field){
        global $frm_show_fields;
        if($type != 'user_id' and !empty($frm_show_fields) and !in_array($field->id, $frm_show_fields) and !in_array($field->field_key, $frm_show_fields))
            $type = 'hidden';
        if($type == 'website') $type = 'url';
        
        if(!is_admin() and !current_user_can('administrator') and $type != 'hidden'){
            $field->field_options = maybe_unserialize($field->field_options);
            if(isset($field->field_options['admin_only']) and $field->field_options['admin_only'])
                $type = 'hidden';
        }
        
        return $type;    
    }
    
    function use_field_key_value($opt, $opt_key, $field){
        //if(in_array($field['post_field'], array('post_category', 'post_status')) or ($field['type'] == 'user_id' and is_admin() and current_user_can('administrator')))
        if((isset($field['use_key']) and $field['use_key']) or (isset($field['type']) and $field['type'] == 'data'))
            $opt = $opt_key;
        return $opt;
    }
    
    function show_field($field){
        if (!empty($field['hide_field'])){
            $first = reset($field['hide_field']);
            if(is_numeric($first)){
                global $frm_hidden_fields;
                $frm_hidden_fields[] = $field;
            }
        }
        
        if ($field['use_calc'] and $field['calc']){
            global $frm_calc_fields;
            $frm_calc_fields[$field['field_key']] = $field['calc'];
        }
    }
    
    function show($field){
        global $frm_ajax_url;
        $field_name = "item_meta[". $field['id'] ."]";
        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/show.php');    
    }
    
    function label_position($position){
        global $frmpro_settings;
        return ($position and $position != '') ? $position : ($frmpro_settings->position == 'none' ? 'top' : $frmpro_settings->position);
    }
    
    function display_field_options($display){
        switch($display['type']){
            case 'user_id':
            case 'hidden':
                $display['label_position'] = false;
                $display['description'] = false;
            case 'form':
                $display['required'] = false;
                $display['default_blank'] = false;
                break;
            case 'break':
                $display['required'] = false;
                $display['options'] = true;
                $display['default_blank'] = false;
                $display['css'] = false;
                break;
            case 'email':
            case 'url':
            case 'website':
            case 'phone':
            case 'image':
            case 'date':
            case 'number':
                $display['size'] = true;
                $display['invalid'] = true;
                $display['clear_on_focus'] = true;
                break;
            case 'password':
                $display['size'] = true;
                $display['clear_on_focus'] = true;
                break;
            case 'time':
                $display['size'] = true;
                break;
            case 'rte':
                $display['size'] = true;
                $display['default_blank'] = false;
                break;
            case 'file':
                $display['invalid'] = true;
                $display['size'] = true;
                break;
            case '10radio':
            case 'scale':
                $display['default_blank'] = false;
                break;
            case 'html':
                $display['label_position'] = false;
                $display['description'] = false;
            case 'divider':
                $display['required'] = false;
                $display['default_blank'] = false;
                break;
        }
        if ($display['type'] == 'data' && isset($display['field_data']['data_type']) && $display['field_data']['data_type'] == 'data'){
            $display['required'] = false;
            $display['default_blank'] = false;
        }
        return $display;
    }

    function form_fields($field, $field_name){
        global $frmpro_settings, $frm_settings, $frm_editing_entry;
        $entry_id = $frm_editing_entry;
        
        if($field['type'] == 'form' and $field['form_select'])
            $dup_fields = FrmField::getAll("fi.form_id='$field[form_select]' and fi.type not in ('break', 'captcha')");
        
        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/form-fields.php');
    }
    
    function input_html($field, $echo=true){
        global $frm_settings, $frm_novalidate;
        
        $add_html = '';
        
        if(isset($field['read_only']) and $field['read_only']){
            global $frm_readonly;

            if($frm_readonly == 'disabled' or (current_user_can('administrator') and is_admin())) return;
            //$add_html .= ' disabled="disabled" ';
            $add_html .= ' readonly="readonly" ';
        }
        
        if($frm_settings->use_html){
            if($field['type'] == 'number'){
                if(!is_numeric($field['minnum']))
                    $field['minnum'] = 0;
                if(!is_numeric($field['maxnum']))
                    $field['maxnum'] = 9999999;
                if(!is_numeric($field['step']))
                    $field['step'] = 1;
                $add_html .= ' min="'.$field['minnum'].'" max="'.$field['maxnum'].'" step="'.$field['step'].'"';
            }else if(in_array($field['type'], array('url', 'email'))){
                if(!$frm_novalidate and isset($field['value']) and $field['default_value'] == $field['value'])
                    $frm_novalidate = true;
            }
        }
        
        if(isset($field['dependent_fields']) and $field['dependent_fields']){
            $trigger = ($field['type'] == 'checkbox' or $field['type'] == 'radio') ? 'onclick' : 'onchange';            
            
            $add_html .= ' '. $trigger .'="frmCheckDependent(this.value,\''.$field['id'].'\')"';
        }
        
        if($echo)
            echo $add_html;

        return $add_html;
    }
    
    function add_field_class($class, $field){
        if($field['type'] == 'scale' and isset($field['star']) and $field['star'])
            $class .= ' star';
        else if($field['type'] == 'date')
            $class .= ' frm_date';
            
        return $class;
    }
    
    function add_separate_value_opt_label($field){
        $style = $field['separate_value'] ? '' : "style='display:none;'";
        echo '<div class="frm-show-click">';
        echo '<div class="field_'. $field['id'] .'_option_key frm_option_val_label" '. $style .'>'. __('Option Label', 'formidable') .'</div>';
        echo '<div class="field_'. $field['id'] .'_option_key frm_option_key_label" '. $style .'>'. __('Saved Value', 'formidable') .'</div>';
        echo '</div>';
    }
    
    function options_form($field, $display, $values){
        global $frm_field, $frm_settings, $frm_ajax_url;
        $form_fields = $frm_field->getAll("fi.form_id = ".$field['form_id']." and (type in ('select','radio','checkbox','10radio','scale','data') or (type = 'data' and (field_options LIKE '\"data_type\";s:6:\"select\"%' OR field_options LIKE '%\"data_type\";s:5:\"radio\"%' OR field_options LIKE '%\"data_type\";s:8:\"checkbox\"%') )) and fi.id != ".$field['id'], " ORDER BY field_order");
        
        $post_type = FrmProForm::post_type($values);
        if(function_exists('get_object_taxonomies'))
            $taxonomies = get_object_taxonomies($post_type);
        
        $frm_field_selection = FrmFieldsHelper::field_selection();     
        $field_types = array();
        $single_input = array('text', 'textarea', 'rte', 'number', 'email', 'url', 'image', 'file', 'date', 'phone', 'hidden', 'time', 'user_id', 'tag', 'password');
        $multiple_input = array('radio', 'checkbox', 'select');
        $other_type = array('divider', 'html', 'break');
        if (in_array($field['type'], $single_input)){
            $frm_pro_field_selection = FrmFieldsHelper::pro_field_selection();
            foreach($single_input as $input){
                if (isset($frm_pro_field_selection[$input]))
                    $field_types[$input] = $frm_pro_field_selection[$input];
                else
                    $field_types[$input] = $frm_field_selection[$input];
            }
        }else if (in_array($field['type'], $multiple_input)){
            $frm_pro_field_selection = FrmFieldsHelper::pro_field_selection();
            foreach($multiple_input as $input){
                if (isset($frm_pro_field_selection[$input]))
                    $field_types[$input] = $frm_pro_field_selection[$input];
                else
                    $field_types[$input] = $frm_field_selection[$input];
            }
        }else if (in_array($field['type'], $other_type)){
            $frm_pro_field_selection = FrmFieldsHelper::pro_field_selection();
            foreach($other_type as $input){
                if (isset($frm_pro_field_selection[$input]))
                    $field_types[$input] = $frm_pro_field_selection[$input];
                else
                    $field_types[$input] = $frm_field_selection[$input];
            }
        }
            
        if($field['type'] == 'date'){
            $locales = array(
                '' => __('English/Western', 'formidable'), 'af' => __('Afrikaans', 'formidable'), 
                'sq' => __('Albanian', 'formidable'), 'ar' => __('Arabic', 'formidable'), 
                'hy' => __('Armenian', 'formidable'), 'az' => __('Azerbaijani', 'formidable'), 
                'eu' => __('Basque', 'formidable'), 'bs' => __('Bosnian', 'formidable'), 
                'bg' => __('Bulgarian', 'formidable'), 'ca' => __('Catalan', 'formidable'), 
                'zh-HK' => __('Chinese Hong Kong', 'formidable'), 'zh-CN' => __('Chinese Simplified', 'formidable'), 
                'zh-TW' => __('Chinese Traditional', 'formidable'), 'hr' => __('Croatian', 'formidable'), 
                'cs' => __('Czech', 'formidable'), 'da' => __('Danish', 'formidable'), 
                'nl' => __('Dutch', 'formidable'), 'en-GB' => __('English/UK', 'formidable'), 
                'eo' => __('Esperanto', 'formidable'), 'et' => __('Estonian', 'formidable'), 
                'fo' => __('Faroese', 'formidable'), 'fa' => __('Farsi/Persian', 'formidable'), 
                'fi' => __('Finnish', 'formidable'), 'fr' => __('French', 'formidable'), 
                'fr-CH' => __('French/Swiss', 'formidable'), 'de' => __('German', 'formidable'), 
                'el' => __('Greek', 'formidable'), 'he' => __('Hebrew', 'formidable'), 
                'hu' => __('Hungarian', 'formidable'), 'is' => __('Icelandic', 'formidable'), 
                'it' => __('Italian', 'formidable'), 'ja' => __('Japanese', 'formidable'), 
                'ko' => __('Korean', 'formidable'), 'lv' => __('Latvian', 'formidable'), 
                'lt' => __('Lithuanian', 'formidable'), 'ms' => __('Malaysian', 'formidable'), 
                'no' => __('Norwegian', 'formidable'), 'pl' => __('Polish', 'formidable'), 
                'pt-BR' => __('Portuguese/Brazilian', 'formidable'), 'ro' => __('Romanian', 'formidable'), 
                'ru' => __('Russian', 'formidable'), 'sr' => __('Serbian', 'formidable'), 
                'sr-SR' => __('Serbian', 'formidable'), 'sk' => __('Slovak', 'formidable'), 
                'sl' => __('Slovenian', 'formidable'), 'es' => __('Spanish', 'formidable'), 
                'sv' => __('Swedish', 'formidable'), 'ta' => __('Tamil', 'formidable'), 
                'th' => __('Thai', 'formidable'), 'tu' => __('Turkish', 'formidable'), 
                'uk' => __('Ukranian', 'formidable'), 'vi' => __('Vietnamese', 'formidable') 
            );
        }else if($field['type'] == 'file'){
            $mimes = get_allowed_mime_types();
        }
        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/options-form.php');  
    }
    
    function get_field_selection(){
        global $frm_field;
        $ajax = true;
        $current_field_id = (int)$_POST['field_id'];
        if(is_numeric($_POST['form_id'])){
            $selected_field = '';
            $fields = $frm_field->getAll(array('fi.form_id' => (int)$_POST['form_id']));
            if ($fields)
                require(FRMPRO_VIEWS_PATH.'/frmpro-fields/field-selection.php');
        }else{
            $selected_field = $_POST['form_id'];
        }
        
        include(FRMPRO_VIEWS_PATH.'/frmpro-fields/data_cat_selected.php');
        
        die();
    }
    
    function get_field_values(){
        global $frm_field, $frm_entry_meta;
        $current_field_id = $_POST['current_field'];
        $new_field = $frm_field->getOne($_POST['field_id']);
        $new_field->field_options = maybe_unserialize($new_field->field_options);
            
        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/field-values.php');
        die();
    }
    
    function get_cat_opts(){
        global $frmpro_display, $frm_field;
        $display = $frmpro_display->getOne($_POST['display_id']);
        $fields = $frm_field->getAll("fi.form_id=$display->form_id and fi.type in ('select','radio')", 'field_order');
        echo '<option value=""></option>';
        foreach ($fields as $field)
            echo '<option value="'. $field->id .'">' . stripslashes($field->name) . '</option>';
        die();
    }
    
    function get_title_opts(){
        global $frmpro_display, $frm_field;
        $display = $frmpro_display->getOne($_POST['display_id']);
        
        if($display){
            $fields = $frm_field->getAll("fi.form_id=$display->form_id and fi.type not in ('divider','captcha','break','html')", 'field_order');
            echo '<option value=""></option>';
            foreach ($fields as $field)
                echo '<option value="'. $field->id .'">' . stripslashes($field->name) . '</option>';
        }
        die();
    }
    
    
    function date_field_js($field_id, $options){
        if($options['unique']){
            global $frmdb, $wpdb, $frm_field;
            
            $field = $frm_field->getOne($options['field_id']);
            $field->field_options = maybe_unserialize($field->field_options);

            if(isset($field->field_options['post_field']) and $field->field_options['post_field'] != ''){
                if($field->field_options['post_field'] == 'post_custom'){
                    $query = "SELECT meta_value FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON (p.ID=pm.post_id) WHERE meta_value != '' AND meta_key='". $field->field_options['custom_field'] ."'";
                }else{
                    $query = "SELECT $post_field FROM $wpdb->posts WHERE 1=1";
                }
                $query .= " and post_status in ('publish','draft','pending','future','private')";

                $post_dates = $wpdb->get_col($query);
            }
            
            $query = "SELECT meta_value FROM $frmdb->entry_metas WHERE field_id=". (int)$options['field_id'];
            if(is_numeric($options['entry_id'])){
                $query .= " and item_id != ". (int)$options['entry_id'];
            }else{
                $disabled = wp_cache_get($options['field_id'], 'frm_used_dates');
            }
            
            if(!isset($disabled) or !$disabled)
                $disabled = $wpdb->get_col($query);
            
            if(isset($post_dates) and $post_dates)
                $disabled = array_merge((array)$post_dates, (array)$disabled);

            $disabled = apply_filters('frm_used_dates', $disabled, $field, $options);
            
            if(!$disabled)
                return;
                
            if(!is_numeric($options['entry_id']))
                wp_cache_set($options['field_id'], $disabled, 'frm_used_dates');
                
            $formatted = array();    
            foreach($disabled as $dis) //format to match javascript dates
               $formatted[] = date('Y-n-j', strtotime($dis)); 
            
            $disabled = $formatted;
            unset($formatted);
            
            echo ',beforeShowDay: function(date){var m=(date.getMonth()+1),d=date.getDate(),y=date.getFullYear();var disabled='. json_encode($disabled) .';if($.inArray(y+"-"+m+"-"+d,disabled) != -1){return [false];} return [true];}';  
        }
        //echo ',beforeShowDay: $.datepicker.noWeekends';
    }
    
    function ajax_get_data($entry_id, $field_id, $current_field){
        global $frm_entry_meta, $frm_field;
        $data_field = $frm_field->getOne($field_id);
        $current = $frm_field->getOne($current_field);
        $meta_value = FrmProEntryMetaHelper::get_post_or_meta_value($entry_id, $data_field);
        
        $value = FrmProFieldsHelper::get_display_value($meta_value, $data_field);
        
        if($value and !empty($value))
            echo "<p class='frm_show_it'>". $value ."</p>\n";
            
        echo '<input type="hidden" id="field_'. $current->field_key .'" name="item_meta['. $current_field .']" value="'. stripslashes(esc_attr($meta_value)) .'"/>';
        die();
    }
    
    function ajax_data_options($hide_field, $entry_id, $selected_field_id, $field_id){
        global $frmpro_entry_meta, $frm_field;
        $data_field = $frm_field->getOne($selected_field_id);
        $entry_id = explode(',', $entry_id);
        
        $field_name = "item_meta[$field_id]";
        $field_data = $frm_field->getOne($field_id);
        $field_data->field_options = maybe_unserialize($field_data->field_options);
        
        $field = array(
            'id' => $field_id, 'value' => '', 'default_value' => '', 'form_id' => $field_data->form_id,
            'type' => apply_filters('frm_field_type', $field_data->type, $field_data, ''),
            'options' => stripslashes_deep(maybe_unserialize($field_data->options)),
            'size' => (isset($field_data->field_options['size']) && $field_data->field_options['size'] != '') ? $field_data->field_options['size'] : '',
            //'value' => $field_data->value
        );
        
        if ($field['size'] == ''){
            global $frm_sidebar_width;
            $field['size'] = $frm_sidebar_width;
        }

        $field = apply_filters('frm_setup_new_fields_vars', stripslashes_deep($field), $field_data);
        
        if(is_numeric($selected_field_id)){
            $field['options'] = array();
            
            $metas = $frmpro_entry_meta->meta_through_join($hide_field, $data_field, $entry_id);
            foreach ($metas as $meta)
                $field['options'][$meta->item_id] = FrmProEntryMetaHelper::display_value($meta->meta_value, $data_field, 
                    array('type' => $data_field->type, 'show_icon' => true, 'show_filename' => false)
                );
        }else if($selected_field_id == 'taxonomy'){
            $cat_ids = array_keys($field['options']);
            
            $args = array('include' => implode(',', $cat_ids), 'hide_empty' => false);
            
            if(function_exists('get_object_taxonomies')){
                $post_type = FrmProForm::post_type($field_data->form_id);
                $args['taxonomy'] = FrmProAppHelper::get_custom_taxonomy($post_type, $field_data);
                if(!$args['taxonomy'])
                    return;
            }
            
            $cats = get_categories($args);
            foreach($cats as $cat){
                if(!in_array($cat->parent, (array)$entry_id))
                    unset($field['options'][$cat->term_id]);
            }
        }
        
        $auto_width = (isset($field['size']) && $field['size'] > 0) ? 'class="auto_width"' : '';
        require(FRMPRO_VIEWS_PATH.'/frmpro-fields/data-options.php');
        die();
    }
    
    function add_option(){
        global $frm_field, $frm_ajax_url;
        
        $id = $_POST['field_id'];
        $t = (isset($_POST['t'])) ? $_POST['t'] : false;
		if ($t == 'row' or $t == 'col'){
	        $field = $frm_field->getOne($id);
	        $options = maybe_unserialize($field->options);
		    list($columns,$rows) = FrmProFieldsHelper::get_table_options($options);
			if ($t == 'col'){
		        $last = (count($columns) ? array_pop(array_keys($columns)) : 'col_0');
				preg_match('/[0-9]+$/',$last,$matches);
		        $opt_key = 'col_' . ($matches[0] + 1);
		        $opt = 'Column '.(count($columns)+1);
		        $columns[$opt_key] = $opt;
		        $row_num = count($rows)-1;
		        $col_num = count($columns);
			}else{
		        $last = (count($rows) ? array_pop(array_keys($rows)) : 'row_0');
				preg_match('/[0-9]+$/',$last,$matches);
		        $opt_key = 'row_' . ($matches[0] + 1);
		        $opt = 'Row '.(count($rows)+1);
		        $rows[$opt_key] = $opt;
		        $row_num = count($rows);
			}
			$options = FrmProFieldsHelper::set_table_options($options, $columns, $rows);
	        $frm_field->update($id, array('options' => maybe_serialize($options)));

	        $field_data = $frm_field->getOne($id);
	        $field = (array) $field_data;
	        $field['value'] = null;
	        $field_name = "item_meta[$id]";
 
            $include_js = true;
            /*if($t == 'row')
                require(FRMPRO_VIEWS_PATH.'/frmpro-fields/grid-single-row.php');
            else
	            require(FRMPRO_VIEWS_PATH.'/frmpro-fields/grid-option.php'); 
	        */
	            
			die();
		}else
			FrmFieldsController::add_option();
	}
	
	function ajax_time_options(){
	    global $frmpro_settings, $frmdb, $wpdb;
	    
	    //posted vars = $time_field, $date_field, $step, $start, $end, $date, $clock
	    extract($_POST);
	    
	    $time_key = str_replace('field_', '', $time_field);
	    $date_key = str_replace('field_', '', $date_field);
	    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($date)))
	        $date = FrmProAppHelper::convert_date($date, $frmpro_settings->date_format, 'Y-m-d');
	    $date_entries = FrmEntryMeta::getEntryIds("fi.field_key='$date_key' and meta_value='$date'");

	    $opts = array('' => '');
        $time = strtotime($start);
        $end = strtotime($end);
        $step = explode(':', $step);
        $step = (isset($step[1])) ? ($step[0] * 3600 + $step[1] * 60) : ($step[0] * 60);
        $format = ($clock) ? 'H:i' : 'h:i A';
        
        while($time <= $end){
            $opts[date($format, $time)] = date($format, $time);
            $time += $step;
        }
        
	    if($date_entries and !empty($date_entries)){
	        $used_times = $wpdb->get_col("SELECT meta_value FROM $frmdb->entry_metas it LEFT JOIN $frmdb->fields fi ON (it.field_id = fi.id) WHERE fi.field_key='$time_key' and it.item_id in (". implode(',', $date_entries).")");
	        
	        if($used_times and !empty($used_times)){
	            $number_allowed = apply_filters('frm_allowed_time_count', 1, $time_key, $date_key);
	            $count = array();
	            foreach($used_times as $used){
	                if(!isset($opts[$used]))
	                    continue;
	                    
	                if(!isset($count[$used]))
	                    $count[$used] = 0;
	                $count[$used]++;
	                
	                if((int)$count[$used] >= $number_allowed)
	                    unset($opts[$used]);
	            }
	            unset($count);
	        }
	    }
	    
	    echo json_encode($opts);
	    die();
	}
	
	function _logic_row(){
	    global $frm_ajax_url;
	    
	    $meta_name = FrmAppHelper::get_param('meta_name');
	    $form_id = FrmAppHelper::get_param('form_id');
	    $field_id = FrmAppHelper::get_param('field_id');
	    $hide_field = '';
        
        $form_fields = FrmField::getAll("fi.form_id = ". $form_id ." and (type in ('select','radio','checkbox','10radio','scale','data') or (type = 'data' and (field_options LIKE '\"data_type\";s:6:\"select\"%' OR field_options LIKE '%\"data_type\";s:5:\"radio\"%' OR field_options LIKE '%\"data_type\";s:8:\"checkbox\"%') )) and fi.id != ". $field_id, " ORDER BY field_order");
        
        $field = FrmField::getOne($field_id);
        $field = FrmFieldsHelper::setup_edit_vars($field);
        
        if(!isset($field['hide_field_cond'][$meta_name]))
            $field['hide_field_cond'][$meta_name] = '==';

        include(FRMPRO_VIEWS_PATH.'/frmpro-fields/_logic_row.php');
        die();
	}

}

?>
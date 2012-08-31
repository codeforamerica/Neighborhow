<?php

class FrmAppHelper{
    
    function get_param($param, $default=''){
        if(strpos($param, '[')){
            $params = explode('[', $param);
            $param = $params[0];    
        }

        $value = (isset($_POST[$param]) ? $_POST[$param] : (isset($_GET[$param]) ? $_GET[$param] : $default));
        
        if(isset($params) and is_array($value) and !empty($value)){
            foreach($params as $k => $p){
                if(!$k or !is_array($value))
                    continue;
                    
                $p = trim($p, ']');
                $value = (isset($value[$p])) ? $value[$p] : $default;
            }
        }

        return $value;
    }
    
    function get_post_param($param, $default=''){
        return isset($_POST[$param]) ? $_POST[$param] : $default;
    }
    
    function get_pages(){
      return get_posts( array('post_type' => 'page', 'post_status' => array('publish', 'private'), 'numberposts' => 999, 'orderby' => 'title', 'order' => 'ASC'));
    }
  
    function wp_pages_dropdown($field_name, $page_id, $truncate=false){
        $pages = FrmAppHelper::get_pages();
    ?>
        <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="frm-dropdown frm-pages-dropdown">
            <option value=""></option>
            <?php foreach($pages as $page){ ?>
                <option value="<?php echo $page->ID; ?>" <?php echo (((isset($_POST[$field_name]) and $_POST[$field_name] == $page->ID) or (!isset($_POST[$field_name]) and $page_id == $page->ID))?' selected="selected"':''); ?>><?php echo ($truncate)? FrmAppHelper::truncate($page->post_title, $truncate) : $page->post_title; ?> </option>
            <?php } ?>
        </select>
    <?php
    }
    
    function wp_roles_dropdown($field_name, $capability){
        $field_value = FrmAppHelper::get_param($field_name);
    	$editable_roles = get_editable_roles();

    ?>
        <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="frm-dropdown frm-pages-dropdown">
            <?php foreach($editable_roles as $role => $details){ 
                $name = translate_user_role($details['name'] ); ?>
                <option value="<?php echo esc_attr($role) ?>" <?php echo (((isset($_POST[$field_name]) and $_POST[$field_name] == $role) or (!isset($_POST[$field_name]) and $capability == $role))?' selected="selected"':''); ?>><?php echo $name ?> </option>
            <?php } ?>
        </select>
    <?php
    }
    
    function frm_capabilities(){
        global $frmpro_is_installed;
        $cap = array(
            'frm_view_forms' => __('View Forms and Templates', 'formidable'),
            'frm_edit_forms' => __('Add/Edit Forms and Templates', 'formidable'),
            'frm_delete_forms' => __('Delete Forms and Templates', 'formidable'),
            'frm_change_settings' => __('Access this Settings Page', 'formidable')
        );
        if($frmpro_is_installed){
            $cap['frm_view_entries'] = __('View Entries from Admin Area', 'formidable');
            $cap['frm_create_entries'] = __('Add Entries from Admin Area', 'formidable');
            $cap['frm_edit_entries'] = __('Edit Entries from Admin Area', 'formidable');
            $cap['frm_delete_entries'] = __('Delete Entries from Admin Area', 'formidable');
            $cap['frm_view_reports'] = __('View Reports', 'formidable');
            $cap['frm_edit_displays'] = __('Add/Edit Custom Displays', 'formidable');
        }
        return $cap;
    }
    
    function user_has_permission($needed_role){        
        if($needed_role == '' or current_user_can($needed_role))
            return true;
            
        $roles = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' );
        foreach ($roles as $role){
        	if (current_user_can($role))
        		return true;
        	if ($role == $needed_role)
        		break;
        }
        return false;
    }
    
    function is_super_admin($user_id=false){
        if(function_exists('is_super_admin'))
            return is_super_admin($user_id);
        else
            return is_site_admin($user_id);
    }
    
    function checked($values, $current){
        if(FrmAppHelper::check_selected($values, $current))
            echo ' checked="checked"';
    }
    
    function check_selected($values, $current){
        //if(is_array($current))
        //    $current = (isset($current['value'])) ? $current['value'] : $current['label'];
        
        if(is_array($values))
            $values = array_map('trim', $values);
        else
            $values = trim($values);
        $current = trim($current);
            
        /*if(is_array($values))
            $values = array_map('htmlentities', $values);
        else
             $values = htmlentities($values);
        
        $values = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $values);
        $current = preg_replace("/&#?[a-z0-9]{2,8};/i", "", $current);
        */

        if((is_array($values) && in_array($current, $values)) or (!is_array($values) and $values == $current))
            return true;
        else
            return false;
    }
    
    function esc_textarea( $text ) {
        $safe_text = str_replace('&quot;', '"', $text);
        $safe_text = htmlspecialchars( $safe_text, ENT_NOQUOTES );
    	return apply_filters( 'esc_textarea', $safe_text, $text );
    }
    
    function script_version($handle, $list='scripts'){
        global $wp_scripts;
    	if(!$wp_scripts)
    	    return false;
        
        $ver = 0;
        
        if ( isset($wp_scripts->registered[$handle]) )
            $query = $wp_scripts->registered[$handle];
            
    	if ( is_object( $query ) )
    	    $ver = $query->ver;

    	return $ver;
    }
    
    function get_file_contents($filename){
        if (is_file($filename)){
            ob_start();
            include $filename;
            $contents = ob_get_contents();
            ob_end_clean();
            return $contents;
        }
        return false;
    }
    
    function get_unique_key($name='', $table_name, $column, $id = 0, $num_chars = 6){
        global $wpdb;

        $key = '';
        
        if (!empty($name)){
            if(function_exists('sanitize_key'))
                $key = sanitize_key($name);
            else
                $key = sanitize_title_with_dashes($name);
        }
        
        if(empty($key)){
            $max_slug_value = pow(36, $num_chars);
            $min_slug_value = 37; // we want to have at least 2 characters in the slug
            $key = base_convert( rand($min_slug_value, $max_slug_value), 10, 36 );
        }

        if (is_numeric($key) or in_array($key, array('id', 'key', 'created-at', 'detaillink', 'editlink', 'siteurl', 'evenodd')))
            $key = $key .'a';
            
        $query = "SELECT $column FROM $table_name WHERE $column = %s AND ID != %d LIMIT 1";
        $key_check = $wpdb->get_var($wpdb->prepare($query, $key, $id));
        
        if ($key_check or is_numeric($key_check)){
            $suffix = 2;
			do {
				$alt_post_name = substr($key, 0, 200-(strlen($suffix)+1)). "$suffix";
				$key_check = $wpdb->get_var($wpdb->prepare($query, $alt_post_name, $id));
				$suffix++;
			} while ($key_check || is_numeric($key_check));
			$key = $alt_post_name;
        }
        return $key;
    }

    //Editing a Form or Entry
    function setup_edit_vars($record, $table, $fields='', $default=false){
        if(!$record) return false;
        global $frm_entry_meta, $frm_form, $frm_settings, $frm_sidebar_width;
        $values = array();

        $values['id'] = $record->id;

        foreach (array('name' => $record->name, 'description' => $record->description) as $var => $default_val)
              $values[$var] = stripslashes(FrmAppHelper::get_param($var, $default_val));
        if(apply_filters('frm_use_wpautop', true))
            $values['description'] = wpautop($values['description']);
        $values['fields'] = array();
        
        if ($fields){
            foreach($fields as $field){
                $field->field_options = stripslashes_deep(maybe_unserialize($field->field_options));

                if ($default){
                    $meta_value = $field->default_value;
                }else{
                    if($record->post_id and class_exists('FrmProEntryMetaHelper') and isset($field->field_options['post_field']) and $field->field_options['post_field']){
                        if(!isset($field->field_options['custom_field']))
                            $field->field_options['custom_field'] = '';
                        $meta_value = FrmProEntryMetaHelper::get_post_value($record->post_id, $field->field_options['post_field'], $field->field_options['custom_field'], array('truncate' => false, 'type' => $field->type, 'form_id' => $field->form_id, 'field' => $field));
                    }else if(isset($record->metas)){
                        $meta_value = isset($record->metas[$field->id]) ? $record->metas[$field->id] : false;
                    }else{
                        $meta_value = $frm_entry_meta->get_entry_meta_by_field($record->id, $field->id);
                    }
                }
                
                $field_type = isset($_POST['field_options']['type_'.$field->id]) ? $_POST['field_options']['type_'.$field->id] : $field->type;
                $new_value = (isset($_POST['item_meta'][$field->id])) ? $_POST['item_meta'][$field->id] : $meta_value;
                $new_value = maybe_unserialize($new_value);
                if(is_array($new_value))
                    $new_value = stripslashes_deep($new_value);
                
                $field_array = array(
                    'id' => $field->id,
                    'value' => $new_value,
                    'default_value' => stripslashes_deep(maybe_unserialize($field->default_value)),
                    'name' => stripslashes($field->name),
                    'description' => stripslashes($field->description),
                    'type' => apply_filters('frm_field_type', $field_type, $field, $new_value),
                    'options' => stripslashes_deep(maybe_unserialize($field->options)),
                    'required' => $field->required,
                    'field_key' => $field->field_key,
                    'field_order' => $field->field_order,
                    'form_id' => $field->form_id
                );
                
                /*if(in_array($field_array['type'], array('checkbox', 'radio', 'select')) and !empty($field_array['options'])){
                    foreach((array)$field_array['options'] as $opt_key => $opt){
                        if(!is_array($opt))
                            $field_array['options'][$opt_key] = array('label' => $opt);
                        unset($opt);
                        unset($opt_key);
                    }
                }*/
                
                $opt_defaults = FrmFieldsHelper::get_default_field_opts($field_array['type'], $field, true);
                
                foreach ($opt_defaults as $opt => $default_opt){
                    $field_array[$opt] = ($_POST and isset($_POST['field_options'][$opt.'_'.$field->id]) ) ? $_POST['field_options'][$opt.'_'.$field->id] : (isset($field->field_options[$opt]) ? $field->field_options[$opt] : $default_opt);
                    if($opt == 'blank' and $field_array[$opt] == ''){
                        $field_array[$opt] = __('This field cannot be blank', 'formidable');
                    }else if($opt == 'invalid' and $field_array[$opt] == ''){
                        if($field_type == 'captcha')
                            $field_array[$opt] = $frm_settings->re_msg;
                        else
                            $field_array[$opt] = $field_array['name'] . ' ' . __('is invalid', 'formidable');
                    }
                }
                
                unset($opt_defaults);
                    
                if ($field_array['custom_html'] == '')
                    $field_array['custom_html'] = FrmFieldsHelper::get_default_html($field_type);
                
                if ($field_array['size'] == '')
                    $field_array['size'] = $frm_sidebar_width;
                
                $values['fields'][] = apply_filters('frm_setup_edit_fields_vars', stripslashes_deep($field_array), $field, $values['id']);
                unset($field);   
            }
        }
      
        if ($table == 'entries')
            $form = $frm_form->getOne( $record->form_id );
        else if ($table == 'forms')
            $form = $frm_form->getOne( $record->id );

        if ($form){
            $form->options = maybe_unserialize($form->options);
            $values['form_name'] = (isset($record->form_id))?($form->name):('');
            if (is_array($form->options)){
                foreach ($form->options as $opt => $value)
                    $values[$opt] = FrmAppHelper::get_param($opt, $value);
            }
        }
        
        $form_defaults = FrmFormsHelper::get_default_opts();
        $form_defaults['email_to'] = ''; //allow blank email address
        
        //set to posted value or default
        foreach ($form_defaults as $opt => $default){
            if (!isset($values[$opt]) or $values[$opt] == '')
                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
            unset($opt);
            unset($defaut);
        }
            
        if (!isset($values['custom_style']))
            $values['custom_style'] = ($_POST and isset($_POST['options']['custom_style'])) ? $_POST['options']['custom_style'] : ($frm_settings->load_style != 'none');

        if (!isset($values['before_html']))
            $values['before_html'] = (isset($_POST['options']['before_html']) ? $_POST['options']['before_html'] : FrmFormsHelper::get_default_html('before'));

        if (!isset($values['after_html']))
            $values['after_html'] = (isset($_POST['options']['after_html'])?$_POST['options']['after_html'] : FrmFormsHelper::get_default_html('after'));

        if ($table == 'entries')
            $values = FrmEntriesHelper::setup_edit_vars( $values, $record );
        else if ($table == 'forms')
            $values = FrmFormsHelper::setup_edit_vars( $values, $record );

        return $values;
    }
    
    function get_us_states(){
        return apply_filters('frm_us_states', array(
            'AL' => 'Alabama', 'AK' => 'Alaska', 'AR' => 'Arkansas', 'AZ' => 'Arizona', 
            'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware', 
            'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho', 
            'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas', 
            'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine','MD' => 'Maryland', 
            'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi', 
            'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada', 
            'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York', 
            'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma', 
            'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina', 
            'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah', 
            'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia', 
            'WI' => 'Wisconsin', 'WY' => 'Wyoming'
        ));
    }
    
    function get_countries(){
        return apply_filters('frm_countries', array(
            __('Afghanistan', 'formidable'), __('Albania', 'formidable'), __('Algeria', 'formidable'), 
            __('American Samoa', 'formidable'), __('Andorra', 'formidable'), __('Angola', 'formidable'),
            __('Anguilla', 'formidable'), __('Antarctica', 'formidable'), __('Antigua and Barbuda', 'formidable'), 
            __('Argentina', 'formidable'), __('Armenia', 'formidable'), __('Aruba', 'formidable'),
            __('Australia', 'formidable'), __('Austria', 'formidable'), __('Azerbaijan', 'formidable'),
            __('Bahamas', 'formidable'), __('Bahrain', 'formidable'), __('Bangladesh', 'formidable'), 
            __('Barbados', 'formidable'), __('Belarus', 'formidable'), __('Belgium', 'formidable'),
            __('Belize', 'formidable'), __('Benin', 'formidable'), __('Bermuda', 'formidable'), 
            __('Bhutan', 'formidable'), __('Bolivia', 'formidable'), __('Bosnia and Herzegovina', 'formidable'),
            __('Botswana', 'formidable'), __('Brazil', 'formidable'), __('Brunei', 'formidable'), 
            __('Bulgaria', 'formidable'), __('Burkina Faso', 'formidable'), __('Burundi', 'formidable'),
            __('Cambodia', 'formidable'), __('Cameroon', 'formidable'), __('Canada', 'formidable'), 
            __('Cape Verde', 'formidable'), __('Cayman Islands', 'formidable'), __('Central African Republic', 'formidable'), 
            __('Chad', 'formidable'), __('Chile', 'formidable'), __('China', 'formidable'),
            __('Colombia', 'formidable'), __('Comoros', 'formidable'), __('Congo', 'formidable'),
            __('Costa Rica', 'formidable'), __('C&ocirc;te d\'Ivoire', 'formidable'), __('Croatia', 'formidable'),
            __('Cuba', 'formidable'), __('Cyprus', 'formidable'), __('Czech Republic', 'formidable'), 
            __('Denmark', 'formidable'), __('Djibouti', 'formidable'), __('Dominica', 'formidable'),
            __('Dominican Republic', 'formidable'), __('East Timor', 'formidable'), __('Ecuador', 'formidable'), 
            __('Egypt', 'formidable'), __('El Salvador', 'formidable'), __('Equatorial Guinea', 'formidable'),
            __('Eritrea', 'formidable'), __('Estonia', 'formidable'), __('Ethiopia', 'formidable'), 
            __('Fiji', 'formidable'), __('Finland', 'formidable'), __('France', 'formidable'), 
            __('French Guiana', 'formidable'), __('French Polynesia', 'formidable'), __('Gabon', 'formidable'), 
            __('Gambia', 'formidable'), __('Georgia', 'formidable'), __('Germany', 'formidable'),
            __('Ghana', 'formidable'), __('Gibraltar', 'formidable'), __('Greece', 'formidable'), 
            __('Greenland', 'formidable'), __('Grenada', 'formidable'), __('Guam', 'formidable'),
            __('Guatemala', 'formidable'), __('Guinea', 'formidable'), __('Guinea-Bissau', 'formidable'), 
            __('Guyana', 'formidable'), __('Haiti', 'formidable'), __('Honduras', 'formidable'), 
            __('Hong Kong', 'formidable'), __('Hungary', 'formidable'), __('Iceland', 'formidable'), 
            __('India', 'formidable'), __('Indonesia', 'formidable'), __('Iran', 'formidable'), 
            __('Iraq', 'formidable'), __('Ireland', 'formidable'), __('Israel', 'formidable'), 
            __('Italy', 'formidable'), __('Jamaica', 'formidable'), __('Japan', 'formidable'), 
            __('Jordan', 'formidable'), __('Kazakhstan', 'formidable'), __('Kenya', 'formidable'), 
            __('Kiribati', 'formidable'), __('North Korea', 'formidable'), __('South Korea', 'formidable'), 
            __('Kuwait', 'formidable'), __('Kyrgyzstan', 'formidable'), __('Laos', 'formidable'), 
            __('Latvia', 'formidable'), __('Lebanon', 'formidable'), __('Lesotho', 'formidable'), 
            __('Liberia', 'formidable'), __('Libya', 'formidable'), __('Liechtenstein', 'formidable'), 
            __('Lithuania', 'formidable'), __('Luxembourg', 'formidable'), __('Macedonia', 'formidable'), 
            __('Madagascar', 'formidable'), __('Malawi', 'formidable'), __('Malaysia', 'formidable'), 
            __('Maldives', 'formidable'), __('Mali', 'formidable'), __('Malta', 'formidable'), 
            __('Marshall Islands', 'formidable'), __('Mauritania', 'formidable'), __('Mauritius', 'formidable'), 
            __('Mexico', 'formidable'), __('Micronesia', 'formidable'), __('Moldova', 'formidable'), 
            __('Monaco', 'formidable'), __('Mongolia', 'formidable'), __('Montenegro', 'formidable'), 
            __('Montserrat', 'formidable'), __('Morocco', 'formidable'), __('Mozambique', 'formidable'), 
            __('Myanmar', 'formidable'), __('Namibia', 'formidable'), __('Nauru', 'formidable'), 
            __('Nepal', 'formidable'), __('Netherlands', 'formidable'), __('New Zealand', 'formidable'),
            __('Nicaragua', 'formidable'), __('Niger', 'formidable'), __('Nigeria', 'formidable'), 
            __('Norway', 'formidable'), __('Northern Mariana Islands', 'formidable'), __('Oman', 'formidable'), 
            __('Pakistan', 'formidable'), __('Palau', 'formidable'), __('Palestine', 'formidable'), 
            __('Panama', 'formidable'), __('Papua New Guinea', 'formidable'), __('Paraguay', 'formidable'), 
            __('Peru', 'formidable'), __('Philippines', 'formidable'), __('Poland', 'formidable'), 
            __('Portugal', 'formidable'), __('Puerto Rico', 'formidable'), __('Qatar', 'formidable'), 
            __('Romania', 'formidable'), __('Russia', 'formidable'), __('Rwanda', 'formidable'), 
            __('Saint Kitts and Nevis', 'formidable'), __('Saint Lucia', 'formidable'), 
            __('Saint Vincent and the Grenadines', 'formidable'), __('Samoa', 'formidable'), 
            __('San Marino', 'formidable'), __('Sao Tome and Principe', 'formidable'), __('Saudi Arabia', 'formidable'),
            __('Senegal', 'formidable'), __('Serbia and Montenegro', 'formidable'), __('Seychelles', 'formidable'), 
            __('Sierra Leone', 'formidable'), __('Singapore', 'formidable'), __('Slovakia', 'formidable'), 
            __('Slovenia', 'formidable'), __('Solomon Islands', 'formidable'), __('Somalia', 'formidable'), 
            __('South Africa', 'formidable'), __('Spain', 'formidable'), __('Sri Lanka', 'formidable'), 
            __('Sudan', 'formidable'), __('Suriname', 'formidable'), __('Swaziland', 'formidable'), 
            __('Sweden', 'formidable'), __('Switzerland', 'formidable'), __('Syria', 'formidable'), 
            __('Taiwan', 'formidable'), __('Tajikistan', 'formidable'), __('Tanzania', 'formidable'), 
            __('Thailand', 'formidable'), __('Togo', 'formidable'), __('Tonga', 'formidable'), 
            __('Trinidad and Tobago', 'formidable'), __('Tunisia', 'formidable'), __('Turkey', 'formidable'), 
            __('Turkmenistan', 'formidable'), __('Tuvalu', 'formidable'), __('Uganda', 'formidable'), 
            __('Ukraine', 'formidable'), __('United Arab Emirates', 'formidable'), __('United Kingdom', 'formidable'),
            __('United States', 'formidable'), __('Uruguay', 'formidable'), __('Uzbekistan', 'formidable'), 
            __('Vanuatu', 'formidable'), __('Vatican City', 'formidable'), __('Venezuela', 'formidable'), 
            __('Vietnam', 'formidable'), __('Virgin Islands, British', 'formidable'), 
            __('Virgin Islands, U.S.', 'formidable'), __('Yemen', 'formidable'), __('Zambia', 'formidable'), 
            __('Zimbabwe', 'formidable')
        ));
    }
    
    function frm_get_main_message( $message = ''){
        global $frmpro_is_installed;
        
        if($frmpro_is_installed)
            return $message;
            
        include_once(ABSPATH.'/wp-includes/class-IXR.php');

        $url = ($frmpro_is_installed) ? 'http://formidablepro.com/' : 'http://blog.strategy11.com/';
        $client = new IXR_Client($url.'xmlrpc.php', false, 80, 5);
        
        if ($client->query('frm.get_main_message'))
            $message = $client->getResponse();

      return $message;
    }
    
    function truncate($str, $length, $minword = 3, $continue = '...'){
        $length = (int)$length;
        $str = stripslashes(strip_tags($str));
        
        if($length == 0)
            return '';
        else if($length <= 10)
            return substr($str, 0, $length);
            
        $sub = '';
        $len = 0;

        foreach (explode(' ', $str) as $word){
            $part = (($sub != '') ? ' ' : '') . $word;
            $sub .= $part;
            $len += strlen($part);

            if (strlen($word) > $minword && strlen($sub) >= $length)
                break;
        }

        return $sub . (($len < strlen($str)) ? $continue : '');
    }
    
    function prepend_and_or_where( $starts_with = ' WHERE ', $where = '' ){
        if(is_array($where)){
            global $frmdb, $wpdb;
            extract($frmdb->get_where_clause_and_values( $where ));
            $where = $wpdb->prepare($where, $values);
        }else{
            $where = (( $where == '' ) ? '' : $starts_with . $where);
        }
        
        return $where;
    }
    
    // Pagination Methods
    function getLastRecordNum($r_count,$current_p,$p_size){
      return (($r_count < ($current_p * $p_size))?$r_count:($current_p * $p_size));
    }

    function getFirstRecordNum($r_count,$current_p,$p_size){
      if($current_p == 1)
        return 1;
      else
        return ($this->getLastRecordNum($r_count,($current_p - 1),$p_size) + 1);
    }
    
    function getRecordCount($where="", $table_name){
        global $wpdb, $frm_app_helper;
        $query = 'SELECT COUNT(*) FROM ' . $table_name . $frm_app_helper->prepend_and_or_where(' WHERE ', $where);
        return $wpdb->get_var($query);
    }

    function getPageCount($p_size, $where="", $table_name){
        if(is_numeric($where))
            return ceil((int)$where / (int)$p_size);
        else
            return ceil((int)$this->getRecordCount($where, $table_name) / (int)$p_size);
    }

    function getPage($current_p,$p_size, $where = "", $order_by = '', $table_name){
        global $wpdb, $frm_app_helper;
        $end_index = $current_p * $p_size;
        $start_index = $end_index - $p_size;
        $query = 'SELECT *  FROM ' . $table_name . $frm_app_helper->prepend_and_or_where(' WHERE', $where) . $order_by .' LIMIT ' . $start_index . ',' . $p_size;
        $results = $wpdb->get_results($query);
        return $results;
    }
    
    function get_referer_query($query) {
    	if (strpos($query, "google.")) {
    	    //$pattern = '/^.*\/search.*[\?&]q=(.*)$/';
            $pattern = '/^.*[\?&]q=(.*)$/';
    	} else if (strpos($query, "bing.com")) {
    		$pattern = '/^.*q=(.*)$/';
    	} else if (strpos($query, "yahoo.")) {
    		$pattern = '/^.*[\?&]p=(.*)$/';
    	} else if (strpos($query, "ask.")) {
    		$pattern = '/^.*[\?&]q=(.*)$/';
    	} else {
    		return false;
    	}
    	preg_match($pattern, $query, $matches);
    	$querystr = substr($matches[1], 0, strpos($matches[1], '&'));
    	return urldecode($querystr);
    }
    
    function get_referer_info(){
        $referrerinfo = '';
    	$keywords = array();
    	$i = 1;
    	if(isset($_SESSION) and isset($_SESSION['frm_http_referer']) and $_SESSION['frm_http_referer']){
        	foreach ($_SESSION['frm_http_referer'] as $referer) {
        		$referrerinfo .= str_pad("Referer $i: ",20) . $referer. "\r\n";
        		$keywords_used = FrmAppHelper::get_referer_query($referer);
        		if ($keywords_used)
        			$keywords[] = $keywords_used;

        		$i++;
        	}
        	
        	$referrerinfo .= "\r\n";
	    }else{
	        $referrerinfo = $_SERVER['HTTP_REFERER'];
	    }

    	$i = 1;
    	if(isset($_SESSION) and isset($_SESSION['frm_http_pages']) and $_SESSION['frm_http_pages']){
        	foreach ($_SESSION['frm_http_pages'] as $page) {
        		$referrerinfo .= str_pad("Page visited $i: ",20) . $page. "\r\n";
        		$i++;
        	}
        	
        	$referrerinfo .= "\r\n";
	    }

    	$i = 1;
    	foreach ($keywords as $keyword) {
    		$referrerinfo .= str_pad("Keyword $i: ",20) . $keyword. "\r\n";
    		$i++;
    	}
    	$referrerinfo .= "\r\n";
    	
    	return $referrerinfo;
    }    
    
}

?>
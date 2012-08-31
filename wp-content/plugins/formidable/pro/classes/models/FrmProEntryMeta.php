<?php
class FrmProEntryMeta{

    function FrmProEntryMeta(){
        add_filter('frm_add_entry_meta', array(&$this, 'before_create'));
        add_action('frm_after_create_entry', array(&$this, 'create'), 10);
        add_action('frm_after_update_entry', array(&$this, 'create'));
        add_filter('frm_validate_field_entry', array(&$this, 'validate'), 10, 3);
    }
    
    function before_create($values){
        global $frm_field;
        $field = $frm_field->getOne($values['field_id']);
        if(!$field)
            return $values;
            
        if ($field->type == 'date'){
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($values['meta_value']))){
                global $frmpro_settings;
                $values['meta_value'] = FrmProAppHelper::convert_date($values['meta_value'], $frmpro_settings->date_format, 'Y-m-d');
            }
        }else if ($field->type == 'number'){
            if(!is_numeric($values['meta_value']))
                $values['meta_value'] = (float)$values['meta_value'];
        }else if($field->type == 'rte' and $values['meta_value'] == '<br>'){
            $values['meta_value'] = '';
        }
        return $values;
    }
    
    function create($entry){
        global $frm_entry, $frm_field, $frm_entry_meta, $wpdb, $frm_loading, $frm_detached_media;

        if (!isset($_FILES) || !is_numeric($entry)) return;
        
        $entry = $frm_entry->getOne($entry);  
        $fields = $frm_field->getAll("fi.form_id='". (int)$entry->form_id ."' and (fi.type='file' or fi.type='tag')");

        foreach ($fields as $field){
            $field->field_options = maybe_unserialize($field->field_options);
            if( isset($_FILES['file'.$field->id]) and !empty($_FILES['file'.$field->id]['name']) and (int)$_FILES['file'.$field->id]['size'] > 0){
                    
                if(!$frm_loading)
                    $frm_loading = true;
                
                $media_id = FrmProAppHelper::upload_file('file'.$field->id);
                
                if (is_numeric($media_id)){
                    $frm_entry_meta->delete_entry_meta($entry->id, $field->id);
                    //TODO: delete media?
                    $frm_entry_meta->update_entry_meta($entry->id, $field->id, $field->field_key, $media_id);
                    
                    if($_POST['item_meta'][$field->id] != $media_id)
                        $frm_detached_media[] = $_POST['item_meta'][$field->id];
                    
                    $_POST['item_meta'][$field->id] = $media_id;
                    if(isset($_POST['frm_wp_post']) and isset($field->field_options['post_field']) and $field->field_options['post_field']){
                        global $frm_media_id;
                        $frm_media_id[$field->id] = $media_id;
                        $_POST['frm_wp_post_custom'][$field->id.'='.$field->field_options['custom_field']] = $media_id;
                    }
                }else{
                    foreach ($media_id->errors as $error)
                        echo $error[0];
                }
            }

            if($field->type == 'tag'){
                $tax_type = (isset($field->field_options['taxonomy']) and !empty($field->field_options['taxonomy'])) ? $field->field_options['taxonomy'] : 'frm_tag';
                
                $tags = explode(',', $_POST['item_meta'][$field->id]);
                $terms = array();
                
                if(isset($_POST['frm_wp_post']))
                    $_POST['frm_wp_post'][$field->id.'=tags_input'] = $tags;
                    
                if($tax_type == 'frm_tag'){
                 
                    foreach($tags as $tag){
                        $slug = sanitize_title(stripslashes($tag));
                        if(!isset($_POST['frm_wp_post'])){
                            if(function_exists('term_exists'))
                                $exists = term_exists($slug, $tax_type);
                            else
                                $exists = is_term($slug, $tax_type);

                            if(!$exists)
                                wp_insert_term( trim($tag), $tax_type, array('slug' => $slug));
                        }

                        $terms[] = $slug;
                    }
                
                    wp_set_object_terms($entry->id, $terms, $tax_type);
                    
                    unset($terms);
                }

            }
        }
    }

    function validate($errors, $field, $value){
        global $frm_field, $frm_show_fields;
        $field->field_options = maybe_unserialize($field->field_options);
        
        if((($field->type != 'tag' and $value == 0) or ($field->type == 'tag' and $value == '')) and isset($field->field_options['post_field']) and $field->field_options['post_field'] == 'post_category' and $field->required == '1'){
            $errors['field'. $field->id] = (!isset($field->field_options['blank']) or $field->field_options['blank'] == '' or $field->field_options['blank'] == 'Untitled cannot be blank') ? (__('This field cannot be blank', 'formidable')) : $field->field_options['blank']; 
        }
        
        //Don't require fields hidden with shortcode fields="25,26,27"
        if(!empty($frm_show_fields) and is_array($frm_show_fields) and $field->required == '1' and isset($errors['field'.$field->id]) and !in_array($field->id, $frm_show_fields) and !in_array($field->field_key, $frm_show_fields)){
            unset($errors['field'.$field->id]);
        }
        
        //Don't require a conditionally hidden field
        if (isset($field->field_options['hide_field']) and !empty($field->field_options['hide_field'])){
            $hidden = FrmProField::is_field_hidden($field, $_POST);
            if($hidden){
                unset($errors['field'.$field->id]);
                $_POST['item_meta'][$field->id] = $value = '';
            }
        }
        
        if ($value and !empty($value) and isset($field->field_options['unique']) and $field->field_options['unique']){
            $entry_id = (isset($_POST) and isset($_POST['id'])) ? $_POST['id'] : false;
            if($field->type == 'time'){
                //TODO: add server-side validation for unique date-time
            }else if($field->type == 'date'){
                global $frmpro_settings;
                $old_value = $value;
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', trim($value)))
        	        $value = FrmProAppHelper::convert_date($value, $frmpro_settings->date_format, 'Y-m-d');
                if($this->value_exists($field->id, $value, $entry_id))
                    $errors['field'.$field->id] = $field->name.' '. __('must be unique', 'formidable');
                $value = $old_value;
            }else{
                if($this->value_exists($field->id, $value, $entry_id))
                    $errors['field'.$field->id] = $field->name.' '. __('must be unique', 'formidable');
            }
            unset($entry_id);
        }
        
        //make sure the [auto_id] is still unique
        if(!empty($field->default_value) and !empty($value) and is_numeric($value) and strpos($field->default_value, '[auto_id') !== false){
            //make sure we are not editing
            if((isset($_POST) and !isset($_POST['id'])) or !is_numeric($_POST['id']))
                $_POST['item_meta'][$field->id] = $value = FrmProFieldsHelper::get_default_value($field->default_value, $field);
        }

        if (isset($field->field_options['admin_only']) and $field->field_options['admin_only'] and !(current_user_can('administrator') or !is_admin()))
            unset($errors['field'.$field->id]);
        
        $errors = $this->set_post_fields($field, $value, $errors);
        
        //if the field is a file upload, check for a file
        if($field->type == 'file' and isset($_FILES['file'.$field->id]) and !empty($_FILES['file'.$field->id]['name'])){
            unset($errors['field'.$field->id]);
            if(isset($field->field_options['restrict']) and $field->field_options['restrict'] and isset($field->field_options['ftypes']) and !empty($field->field_options['ftypes'])){
                $mimes = $field->field_options['ftypes'];
            }else{
                $mimes = null;
            }
            //check allowed mime types for this field
            $file_type = wp_check_filetype( $_FILES['file'.$field->id]['name'], $mimes ); 

            if(!$file_type['ext'])
                $errors['field'.$field->id] = ($field->field_options['invalid'] == __('This field is invalid', 'formidable') or $field->field_options['invalid'] == '' or $field->field_options['invalid'] == $field->name.' '. __('is invalid', 'formidable')) ? __('Sorry, this file type is not permitted for security reasons.', 'formidable') : $field->field_options['invalid'];
        }else if($field->type == 'user_id'){
            //add user id to post variables to be saved with entry
            $_POST['frm_user_id'] = $value;
        }

        //Don't validate the format if field is blank
        if ($value == '' or is_array($value)) return $errors;
        
        $value = trim($value);
        //validate the format
        if (($field->type == 'number' and !is_numeric($value)) or 
            ($field->type == 'email' and !is_email($value)) or 
            (($field->type == 'website' or $field->type == 'url' or $field->type == 'image') and !preg_match('/^http.?:\/\/.*\..*$/', $value)) or 
            ($field->type == 'phone' and !preg_match('/^((\+\d{1,3}(-|.| )?\(?\d\)?(-| |.)?\d{1,5})|(\(?\d{2,6}\)?))(-|.| )?(\d{3,4})(-|.| )?(\d{4})(( x| ext)\d{1,5}){0,1}$/', $value))){
            $errors['field'.$field->id] = ($field->field_options['invalid'] == __('This field is invalid', 'formidable') || $field->field_options['invalid'] == '')?($field->name.' '. __('is invalid', 'formidable')) : $field->field_options['invalid'];
        }
        
        if($field->type == 'date'){ 
            if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)){ 
                global $frmpro_settings;
                $value = FrmProAppHelper::convert_date($value, $frmpro_settings->date_format, 'Y-m-d');
            }
            $date = explode('-', $value);

            if(count($date) != 3 or !checkdate( (int)$date[1], (int)$date[2], (int)$date[0]))
                $errors['field'.$field->id] = ($field->field_options['invalid'] == __('This field is invalid', 'formidable') || $field->field_options['invalid'] == '') ? ($field->name.' '. __('is invalid', 'formidable')) : $field->field_options['invalid'];
        }

        return $errors;
    }
    
    function set_post_fields($field, $value, $errors=null){
        $field->field_options = maybe_unserialize($field->field_options);
        
        if(isset($field->field_options['post_field']) and $field->field_options['post_field'] != ''){
            global $frmpro_settings;
            
            if ($value and !empty($value) and isset($field->field_options['unique']) and $field->field_options['unique']){
                global $frmdb;
                
                $entry_id = (isset($_POST) and isset($_POST['id'])) ? $_POST['id'] : false;
                if($entry_id)
                    $post_id = $frmdb->get_var($frmdb->entries, array('id' => $entry_id), 'post_id');
                else
                    $post_id = false;
                    
                if(isset($errors) and $this->post_value_exists($field->field_options['post_field'], $value, $post_id, $field->field_options['custom_field'])){
                    $errors['field'.$field->id] = $field->name.' '. __('must be unique', 'formidable');
                }
                    
                unset($entry_id);
                unset($post_id);
            }
            
            if($field->field_options['post_field'] == 'post_custom'){
                if ($field->type == 'date' and !preg_match('/^\d{4}-\d{2}-\d{2}/', trim($value))){
                    $value = FrmProAppHelper::convert_date($value, $frmpro_settings->date_format, 'Y-m-d');
                }else if($field->type == 'file'){
                    global $frm_media_id;
                    $frm_media_id[$field->id] = $value;
                }
                    
                $_POST['frm_wp_post_custom'][$field->id.'='.$field->field_options['custom_field']] = $value;
                
            }else{
                if($field->field_options['post_field'] == 'post_date'){
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}/', trim($value)))
                        $value = FrmProAppHelper::convert_date($value, $frmpro_settings->date_format, 'Y-m-d H:i:s');
                }else if($field->type != 'tag' and $field->field_options['post_field'] == 'post_category'){
                    $value = (array)$value;
                    if(isset($field->field_options['taxonomy']) and $field->field_options['taxonomy'] != 'category'){
                        $new_value = array();
                        foreach($value as $val){
                            $term = get_term($val, $field->field_options['taxonomy']);

                            if(!isset($term->errors))
                                $new_value[$val] = $term->name;
                            else
                                $new_value[$val] = $val;
                                
                        }
                        
                        if(!isset($_POST['frm_tax_input']))
                            $_POST['frm_tax_input'] = array();

                        if(isset($_POST['frm_tax_input'][$field->field_options['taxonomy']])){
                            foreach($new_value as $new_key => $new_name)
                                $_POST['frm_tax_input'][$field->field_options['taxonomy']][$new_key] = $new_name;
                        }else{
                            $_POST['frm_tax_input'][$field->field_options['taxonomy']] = $new_value;
                        }
                    }else{
                        $_POST['frm_wp_post'][$field->id.'='.$field->field_options['post_field']] = $value;
                    }
                    
                }else if($field->type == 'tag' and $field->field_options['post_field'] == 'post_category'){
                    //$tags = explode(',', $value);
                    
                    $tax_type = (isset($field->field_options['taxonomy']) and !empty($field->field_options['taxonomy'])) ? $field->field_options['taxonomy'] : 'frm_tag';

                    if(!isset($_POST['frm_tax_input']))
                        $_POST['frm_tax_input'] = array();
                    
                    $_POST['frm_tax_input'][$tax_type] = $value;

                    //unset($tags);
                }

            	if($field->field_options['post_field'] != 'post_category')
                    $_POST['frm_wp_post'][$field->id.'='.$field->field_options['post_field']] = $value;
            }
        }
        
        if(isset($errors))
            return $errors;
    }
    
    function meta_through_join($hide_field, $selected_field, $observed_field_val){
        if (!is_numeric($observed_field_val) and !is_array($observed_field_val)) return array();
        global $frm_field, $frm_entry_meta;
        
        $observed_info = $frm_field->getOne($hide_field);
        $observed_info->field_options = maybe_unserialize($observed_info->field_options);
        
        if($selected_field)
            $join_fields = $frm_field->getAll(array('fi.form_id' => $selected_field->form_id, 'type' => 'data'));
            
        if(isset($join_fields) and $join_fields){
            foreach ($join_fields as $jf){
                $jf_options = maybe_unserialize($jf->field_options);
                if (isset($jf_options['form_select']) and $jf_options['form_select'] == $observed_info->field_options['form_select'])
                    $join_field = $jf->id;
            }
            
            if(isset($join_field)){
                $observed_field_val = (array) $observed_field_val;
                $query = "(it.meta_value in (". implode(',', $observed_field_val) .")";
                if(is_array($observed_field_val)){
                    foreach($observed_field_val as $obs_val)
                        $query .= " or it.meta_value LIKE '%s:". strlen($obs_val). ":\"". $obs_val ."\"%'"; 
                }else{
                    $query .= " or it.meta_value LIKE '%s:". strlen($observed_field_val). ":\"". $observed_field_val ."\"%'"; 
                }
                $query .= ") and field_id ='$join_field'";
                $entry_ids = $frm_entry_meta->getEntryIds($query);
            }
        }
        
        if (isset($entry_ids) and !empty($entry_ids))
            $metas = $frm_entry_meta->getAll("item_id in (".implode(',', $entry_ids).") and field_id=". $selected_field->id, ' ORDER BY meta_value');
        else
            $metas = array();
            
        return $metas;
    }
    
    function value_exists($field_id, $value, $entry_id=false){
        global $wpdb, $frmdb;
        $query = "SELECT id FROM $frmdb->entry_metas WHERE meta_value='$value' and field_id=$field_id";
        if($entry_id)
            $query .= " and item_id != ". $entry_id;
        return $wpdb->get_var($query);
    }
    
    function post_value_exists($post_field, $value, $post_id, $custom_field=''){
        global $wpdb;
        if($post_field == 'post_custom'){
            $query = "SELECT post_id FROM $wpdb->postmeta pm LEFT JOIN $wpdb->posts p ON (p.ID=pm.post_id) WHERE meta_value='$value' and meta_key='$custom_field'";
            if($post_id and is_numeric($post_id))
                $query .= " and post_id != ". $post_id;
        }else{
            $query = "SELECT ID FROM $wpdb->posts WHERE `$post_field`='$value'";
            if($post_id and is_numeric($post_id))
                $query .= " and ID != ". $post_id;
        }
        $query .= " and post_status in ('publish','draft','pending','future')";
        
        return $wpdb->get_var($query);
    }
    
    function &get_max($field){
        global $wpdb, $frmdb;
        
        if(!is_object($field)){
            global $frm_field;
            $field = $frm_field->getOne($field);
        }
        
        if(!$field)
            return;
            
        $query = "SELECT meta_value +0 as odr FROM $frmdb->entry_metas WHERE field_id='{$field->id}' ORDER BY odr DESC LIMIT 1";
        $max = $wpdb->get_var($query);
        
        if(isset($field->field_options['post_field']) and $field->field_options['post_field'] == 'post_custom'){
            $post_max = $wpdb->get_var($wpdb->prepare("SELECT meta_value +0 as odr FROM $wpdb->postmeta WHERE meta_key= %s ORDER BY odr DESC LIMIT 1", $field->field_options['custom_field']));
            if($post_max and (float)$post_max > (float)$max)
                $max = $post_max;
        }
        
        return $max;
    }
}
?>

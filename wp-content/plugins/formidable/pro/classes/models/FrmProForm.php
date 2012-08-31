<?php
class FrmProForm{

    function FrmProForm(){
        add_filter('frm_form_options_before_update', array(&$this, 'update_options'), 10, 2);
        add_filter('frm_update_form_field_options', array(&$this, 'update_form_field_options'), 10, 3);
        add_action('frm_update_form', array(&$this, 'update'), 10, 2);
        add_filter('frm_validate_form', array(&$this, 'validate'), 10, 2);
    }
    
    function update_options($options, $values){
        global $frmpro_settings;
            
        $defaults = FrmProFormsHelper::get_default_opts();
        unset($defaults['logged_in']);
        unset($defaults['editable']);
        $defaults['inc_user_info'] = 0;
        
        foreach($defaults as $opt => $default)
            $options[$opt] = (isset($values['options'][$opt])) ? $values['options'][$opt] : $default;

        unset($defaults);
        
        $options['single_entry'] = (isset($values['options']['single_entry'])) ? $values['options']['single_entry'] : 0;
        if ($options['single_entry'])
            $options['single_entry_type'] = (isset($values['options']['single_entry_type'])) ? $values['options']['single_entry_type'] : 'cookie';
            
        if (IS_WPMU)
            $options['copy'] = (isset($values['options']['copy'])) ? $values['options']['copy'] : 0;
        return $options;
    }
    
    function update_form_field_options($field_options, $field, $values){
        $post_fields = array(
            'post_category', 'post_content', 'post_excerpt', 'post_title', 
            'post_name', 'post_date', 'post_status'
        );
        
        $field_options['post_field'] = $field_options['custom_field'] = '';
        $field_options['taxonomy'] = 'category';
        $field_options['exclude_cat'] = 0;
        
        if(!isset($values['options']['create_post']) or !$values['options']['create_post'])
            return $field_options;
            
        foreach($post_fields as $post_field){
            if(isset($values['options'][$post_field]) and $values['options'][$post_field] == $field->id)
                $field_options['post_field'] = $post_field;
        }
        
        //Set post categories
        if(isset($values['options']['post_category']) and isset($values['options']['post_category'])){
            foreach($values['options']['post_category'] as $field_name){
                if($field_name['field_id'] == $field->id){
                    $field_options['post_field'] = 'post_category';
                    $field_options['taxonomy'] = isset($field_name['meta_name']) ? $field_name['meta_name'] : 'category';
                    $field_options['exclude_cat'] = isset($field_name['exclude_cat']) ? $field_name['exclude_cat'] : 0;
                }
            }
        }
        
        //Set post custom fields
        if(isset($values['options']['post_custom_fields']) and isset($values['options']['post_custom_fields'])){
            foreach($values['options']['post_custom_fields'] as $field_name){
                if($field_name['field_id'] == $field->id){
                    $field_options['post_field'] = 'post_custom';
                    $field_options['custom_field'] = $field_name['meta_name'];
                }
            }
        }
        
        return $field_options;
    }
    
    function update($id, $values){
        global $wpdb, $frm_form, $frmdb, $frm_field;
        
        if (isset($values['options'])){
            $logged_in = isset($values['logged_in']) ? $values['logged_in'] : 0;
            $editable = isset($values['editable']) ? $values['editable'] : 0;
            $updated = $wpdb->update( $frmdb->forms, array('logged_in' => $logged_in, 'editable' => $editable), array( 'id' => $id ) );
            if($updated){
                wp_cache_delete( $id, 'frm_form');
                unset($updated);
            }
        }
        
        //create post category field
        if(isset($values['options']['post_category']) and isset($values['options']['post_category'])){
            foreach($values['options']['post_category'] as $field_name){
                if($field_name['field_id'] == 'checkbox'){
                    global $frm_field;
                    
                    //create a new field
                    $new_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $id));
                    $new_values['field_options']['taxonomy'] = isset($field_name['meta_name']) ? $field_name['meta_name'] : 'category';
                    $new_values['name'] = ucwords(str_replace('_', ' ', $new_values['field_options']['taxonomy']));
                    $new_values['field_options']['post_field'] = 'post_category';
                    $new_values['field_options']['exclude_cat'] = isset($field_name['exclude_cat']) ? $field_name['exclude_cat'] : 0;
                    $frm_field->create( $new_values );
                    unset($new_values);
                }
                unset($field_name);
            }
        }
        
        //create post status field
        if(isset($values['options']['post_status']) and $values['options']['post_status'] == 'dropdown'){
            global $frm_field;
            
            //create a new field
            $new_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $id));
            $new_values['name'] = __('Status', 'formidable');
            $new_values['field_options']['post_field'] = 'post_status';
            $values['options']['post_status'] = $frm_field->create( $new_values );
            unset($new_values);
        }
        
        //update/create custom display
        if((isset($values['frm_dyncontent']) and !empty($values['frm_dyncontent'])) or (isset($values['frm_single_content']) and !empty($values['frm_single_content']))){
            $cd_values = array();
            if(isset($values['frm_single_content']))
                $cd_values = array('content' => $values['frm_single_content']);
            else
                $cd_values = array('dyncontent' => $values['frm_dyncontent']);
                
            if(isset($values['frm_display_id']) and is_numeric($values['frm_display_id'])){
                //updating custom display
                global $frmprodb;
                
                $query_results = $wpdb->update( $frmprodb->displays, $cd_values, array( 'id' => $values['frm_display_id'] ) );
                if ($query_results){
                    wp_cache_delete( $values['frm_display_id'], 'frm_display');
                    do_action('frm_update_display', $values['frm_display_id'], $cd_values);
                }
                
                unset($query_results);
            }else{
                //create new
                global $frmpro_display;
                
                $cd_values['name'] = __('Single', 'formidable') .' '. $values['options']['post_type'];
                
                $cd_values['description'] = __('Used for the single post page', 'formidable');
                $cd_values['content'] = __('Add content here if you would like to use this as a listing page.', 'formidable');
                $cd_values['dyncontent'] = $values['frm_dyncontent'];
                $cd_values['param'] = 'entry';
                $cd_values['type'] = 'display_key';
                $cd_values['show_count'] = 'dynamic';
                
                $cd_values['form_id'] = $id;
                
                $frmpro_display->create( $cd_values );
            }
            unset($cd_values);
        }

        //update dependent fields
        if (isset($values['field_options'])){
            $all_fields = $frm_field->getAll(array('fi.form_id' => $id));
            if ($all_fields){
                foreach($all_fields as $field){
                    $option_array[$field->id] = maybe_unserialize($field->field_options);
                    $option_array[$field->id]['dependent_fields'] = false;
                    unset($field);
                }

                foreach($all_fields as $field){
                    if(isset($option_array[$field->id]['hide_field']) and 
                        !empty($option_array[$field->id]['hide_field']) and
                        (!empty($option_array[$field->id]['hide_opt']) or !empty($option_array[$field->id]['form_select']))){
                        //save hidden fields to parent field

                        foreach((array)$option_array[$field->id]['hide_field'] as $i => $f){
                            if(!empty($f) and $option_array[$f])
                                $option_array[$f]['dependent_fields'][$field->id] = true;
                        }

                    }
                    unset($field);
                }

                foreach($option_array as $field_id => $field_options){
                    $frm_field->update($field_id, array('field_options' => $field_options));
                    unset($field_options);
                }
                unset($option_array);
            }
        }
    }

    function validate( $errors, $values ){
        global $frm_field;
        /*
        if (isset($values['item_meta'])){    
            foreach($values['item_meta'] as $key => $value){
                $field = $frm_field->getOne($key);  
                if ($field && $field->type == 'hidden' and empty($value))
                    $errors[] = __("Hidden fields must have a value.", 'formidable');
            }

        }
        */
          
        if (isset($values['logged_in']) or isset($values['editable']) or (isset($values['single_entry']) and isset($values['options']['single_entry_type']) and $values['options']['single_entry_type'] == 'user')){
            $form_id = $values['id'];
            $user_field = $frm_field->getAll(array('fi.form_id' => $form_id, 'type' => 'user_id'));
            if (!$user_field){
                $new_values = FrmFieldsHelper::setup_new_vars('user_id',$form_id);
                $new_values['name'] = __('User ID', 'formidable');
                $frm_field->create($new_values);
            }
        }
        
        if (isset($values['options']['auto_responder'])){
            if (!isset($values['options']['ar_email_message']) or $values['options']['ar_email_message'] == '')
                $errors[] = __("Please insert a message for your auto responder.", 'formidable');
            if (isset($values['options']['ar_reply_to']) and !is_email(trim($values['options']['ar_reply_to'])))
                $errors[] = __("That is not a valid reply-to email address for your auto responder.", 'formidable');
        }

        return $errors;
    }
    
    function has_field($type, $form_id, $single=true){
        global $frmdb;
        if($single)
            $included = $frmdb->get_one_record($frmdb->fields, compact('form_id', 'type'));
        else
            $included = $frmdb->get_records($frmdb->fields, compact('form_id', 'type'));
        return $included;
    }
    
    function post_type($form_id){
        if(is_numeric($form_id)){
            global $frmdb;
            $cache = wp_cache_get($form_id, 'frm_form');
            if($cache)
                $form_options = $cache->options;
            else
                $form_options = $frmdb->get_var($frmdb->forms, array('id' => $form_id), 'options');
            $form_options = maybe_unserialize($form_options);
            return (isset($form_options['post_type'])) ? $form_options['post_type'] : 'post';
        }else{
            $form = (array) $form_id;
            return (isset($form['post_type'])) ? $form['post_type'] : 'post';
        }
    }
}  
?>
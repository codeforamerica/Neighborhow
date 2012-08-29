<?php
class FrmForm{

  function create( $values ){
    global $wpdb, $frmdb, $frm_settings;
    
    $new_values = array();
    $new_values['form_key'] = FrmAppHelper::get_unique_key($values['form_key'], $frmdb->forms, 'form_key');
    $new_values['name'] = $values['name'];
    $new_values['description'] = $values['description'];
    $new_values['status'] = isset($values['status']) ? $values['status'] : 'draft';
    $new_values['is_template'] = isset($values['is_template']) ? (int)$values['is_template'] : 0;
    $new_values['editable'] = isset($values['editable']) ? (int)$values['editable'] : 0;
    $new_values['default_template'] = isset($values['default_template']) ? (int)$values['default_template'] : 0;
    $new_values['prli_link_id'] = isset($link_id)?(int)$link_id:0;
    $options = array();
    
    $defaults = FrmFormsHelper::get_default_opts();
    foreach ($defaults as $var => $default)
        $options[$var] = isset($values['options'][$var]) ? $values['options'][$var] : $default;
        
    $options['before_html'] = isset($values['options']['before_html']) ? $values['options']['before_html'] : FrmFormsHelper::get_default_html('before');
    $options['after_html'] = isset($values['options']['after_html']) ? $values['options']['after_html'] : FrmFormsHelper::get_default_html('after');
    $options = apply_filters('frm_form_options_before_update', $options, $values);
    $new_values['options'] = serialize($options);
    $new_values['created_at'] = current_time('mysql', 1);
    
    //if(isset($values['id']) and is_numeric($values['id']))
    //    $new_values['id'] = $values['id'];

    $query_results = $wpdb->insert( $frmdb->forms, $new_values );
    
    return $wpdb->insert_id;
  }
  
  function duplicate( $id, $template=false, $copy_keys=false, $blog_id=false ){
    global $wpdb, $frmdb, $frm_form, $frm_field;
    
    $values = $frm_form->getOne( $id, $blog_id );
    if(!$values)
        return false;
        
    $new_values = array();
    $new_key = ($copy_keys) ? $values->form_key : '';
    $new_values['form_key'] = FrmAppHelper::get_unique_key($new_key, $frmdb->forms, 'form_key');
    $new_values['name'] = $values->name;
    $new_values['description'] = $values->description;
    $new_values['status'] = (!$template) ? 'draft' : '';
    
    if ($blog_id){
        $new_values['status'] = 'published';
        $new_options = maybe_unserialize($values->options);
        $new_options['email_to'] = get_option('admin_email');
        $new_options['copy'] = false;
        $new_values['options'] = $new_options;
    }else
        $new_values['options'] = $values->options;
    
    if(is_array($new_values['options']))
        $new_values['options'] = serialize($new_values['options']);
        
    $new_values['logged_in'] = $values->logged_in ? $values->logged_in : 0;
    $new_values['editable'] = $values->editable ? $values->editable : 0;
    $new_values['created_at'] = current_time('mysql', 1);
    $new_values['is_template'] = ($template) ? 1 : 0;

    $query_results = $wpdb->insert( $frmdb->forms, $new_values );
    
    if($query_results){
       $form_id = $wpdb->insert_id;
       $frm_field->duplicate($id, $form_id, $copy_keys, $blog_id);
           
      return $form_id;
   }else
      return false;
  }

  function update( $id, $values, $create_link = false ){
    global $wpdb, $frmdb, $frm_field, $frm_settings;

    if ($create_link or isset($values['options']) or isset($values['item_meta']) or isset($values['field_options']))
        $values['status'] = 'published';
        
    if (isset($values['form_key']))
        $values['form_key'] = FrmAppHelper::get_unique_key($values['form_key'], $frmdb->forms, 'form_key', $id);
    
    $form_fields = array('form_key', 'name', 'description', 'status', 'prli_link_id');
    
    $new_values = array();

    if (isset($values['options'])){
        $options = array();
        
        $defaults = FrmFormsHelper::get_default_opts();
        foreach ($defaults as $var => $default)
            $options[$var] = isset($values['options'][$var]) ? $values['options'][$var] : $default;
            
        $options['custom_style'] = isset($values['options']['custom_style']) ? $values['options']['custom_style'] : 0;
        $options['before_html'] = isset($values['options']['before_html']) ? $values['options']['before_html'] : FrmFormsHelper::get_default_html('before');
        $options['after_html'] = isset($values['options']['after_html']) ? $values['options']['after_html'] : FrmFormsHelper::get_default_html('after');
        $options = apply_filters('frm_form_options_before_update', $options, $values);
        $new_values['options'] = serialize($options);
    }
    
    foreach ($values as $value_key => $value){
        if (in_array($value_key, $form_fields))
            $new_values[$value_key] = $value;
    }

    if(!empty($new_values)){
        $query_results = $wpdb->update( $frmdb->forms, $new_values, array( 'id' => $id ) );
        if($query_results)
            wp_cache_delete( $id, 'frm_form');
    }else{
        $query_results = true;
    }

    $all_fields = $frm_field->getAll(array('fi.form_id' => $id));
    if ($all_fields and (isset($values['options']) or isset($values['item_meta']) or isset($values['field_options']))){
        if(!isset($values['item_meta']))
            $values['item_meta'] = array();
        $existing_keys = array_keys($values['item_meta']);
        foreach ($all_fields as $fid){
            if (!in_array($fid->id, $existing_keys))
                $values['item_meta'][$fid->id] = '';
        }
        foreach ($values['item_meta'] as $field_id => $default_value){ 
            $field = $frm_field->getOne($field_id);
            if (!$field) continue;
            $field_options = maybe_unserialize($field->field_options);
            
            if(isset($values['options']) or isset($values['field_options']['custom_html_'.$field_id])){
                //updating the settings page
                if(isset($values['field_options']['custom_html_'.$field_id])){
                    $field_options['custom_html'] = isset($values['field_options']['custom_html_'.$field_id]) ? $values['field_options']['custom_html_'.$field_id] : (isset($field_options['custom_html']) ? $field_options['custom_html'] : FrmFieldsHelper::get_default_html($field->type));
                    $field_options = apply_filters('frm_update_form_field_options', $field_options, $field, $values);
                    $frm_field->update($field_id, array('field_options' => $field_options));
                }else if($field->type == 'hidden'){
                    $prev_opts = $field_options;
                    $field_options = apply_filters('frm_update_form_field_options', $field_options, $field, $values);
                    if($prev_opts != $field_options)
                        $frm_field->update($field_id, array('field_options' => $field_options));
                    unset($prev_opts);
                }
            }else{
                //updating the form
                
                foreach (array('size', 'max', 'label', 'invalid', 'required_indicator', 'blank', 'classes') as $opt)
                    $field_options[$opt] = isset($values['field_options'][$opt.'_'.$field_id]) ? trim($values['field_options'][$opt.'_'.$field_id]) : ''; 
                $field_options['separate_value'] = isset($values['field_options']['separate_value_'.$field_id]) ? trim($values['field_options']['separate_value_'.$field_id]) : 0; 
                    
                $field_options = apply_filters('frm_update_field_options', $field_options, $field, $values);
                $default_value = maybe_serialize($values['item_meta'][$field_id]);
                $field_key = (isset($values['field_options']['field_key_'.$field_id]))? $values['field_options']['field_key_'.$field_id] : $field->field_key;
                $field_type = (isset($values['field_options']['type_'.$field_id]))? $values['field_options']['type_'.$field_id] : $field->type;
                $field_description = (isset($values['field_options']['description_'.$field_id]))? $values['field_options']['description_'.$field_id] : $field->description;

                $frm_field->update($field_id, array('field_key' => $field_key, 'type' => $field_type, 'default_value' => $default_value, 'field_options' => $field_options, 'description' => $field_description));
            }
        }
    }    
    
    if (isset($values['form_key']) && class_exists('PrliLink')){
        $form = $this->getOne($id);
        global $prli_link;
        $prlink = $prli_link->getOne($form->prli_link_id);
        if ($prlink){
            $prli = array();
            $prli['url'] = FrmFormsHelper::get_direct_link($values['form_key']);
            $prli['slug'] = $prlink->slug;
            $prli['name'] = $prlink->name;
            $prli['param_forwarding'] = $prlink->param_forwarding;
            $prli['param_struct'] = $prlink->param_struct;
            $prli['redirect_type'] = $prlink->redirect_type;
            $prli['description'] = $prlink->description;
            $prli['track_me'] = $prlink->track_me;
            $prli['nofollow'] = $prlink->nofollow;
            $prli['group_id'] = $prlink->group_id;
            $prli_link->update($form->prli_link_id, $prli); //update target url
        }else if($create_link && $form->is_template != 1){
            $link_id = prli_create_pretty_link(FrmFormsHelper::get_direct_link($values['form_key']), $values['form_key'], $form->name, $form->description, $group_id = '' );
            $wpdb->update( $frmdb->forms, array('prli_link_id' => $link_id), array( 'id' => $id ) );
        }
    }    
    do_action('frm_update_form', $id, $values);
    do_action('frm_update_form_'. $id, $values);
     
    return $query_results;
  }

  function destroy( $id ){
    global $wpdb, $frmdb, $frm_entry;

    $form = $this->getOne($id);
    if (!$form or $form->default_template)
        return false;
        
    // Disconnect the items from this form
    $entries = $frm_entry->getAll(array('it.form_id' => $id));
    foreach ($entries as $item)
        $frm_entry->destroy($item->id);

    // Disconnect the fields from this form
    $query_results = $wpdb->query("DELETE FROM `$frmdb->fields` WHERE `form_id` = '$id'");

    $query_results = $wpdb->query("DELETE FROM `$frmdb->forms` WHERE `id` = '$id'");
    if ($query_results){
        do_action('frm_destroy_form', $id);
        do_action('frm_destroy_form_'. $id);
    }
    return $query_results;
  }
  
  function getName( $id ){
      global $wpdb, $frmdb;
      $query = "SELECT `name` FROM $frmdb->forms WHERE ";
      $query .= (is_numeric($id)) ? "id" : "form_key";
      $query .= "='{$id}'";
      return $wpdb->get_var($query);
  }
  
  function getIdByName( $name ){
      global $wpdb, $frmdb;
      $query = "SELECT id FROM $frmdb->forms WHERE name='$name';";
      return $wpdb->get_var($query);
  }
  
  function getIdByKey( $key ){
      global $wpdb, $frmdb;
      $query = "SELECT id FROM $frmdb->forms WHERE form_key='$key' LIMIT 1";
      return $wpdb->get_var($query);
  }

  function getOne( $id, $blog_id=false ){
      global $wpdb, $frmdb;
      
      if ($blog_id and IS_WPMU){
          global $wpmuBaseTablePrefix;
          if($wpmuBaseTablePrefix)
              $prefix = "{$wpmuBaseTablePrefix}{$blog_id}_";
          else
              $prefix = $wpdb->get_blog_prefix( $blog_id );
              
          $table_name = "{$prefix}frm_forms";
      }else{
          $table_name = $frmdb->forms;
          $cache = wp_cache_get($id, 'frm_form');
          if($cache)
            return $cache;
      }
      
      if (is_numeric($id))
          $where = array('id' => $id);
      else
          $where = array('form_key' => $id);
          
      $results = $frmdb->get_one_record($table_name, $where);
      
      if(isset($results->options)){
          $results->options = stripslashes_deep(maybe_unserialize($results->options));
          wp_cache_set($results->id, $results, 'frm_form');
      }
      return $results;
  }

    function getAll( $where = array(), $order_by = '', $limit = '' ){
        global $wpdb, $frmdb, $frm_app_helper;
        
        if(is_numeric($limit))
            $limit = " LIMIT {$limit}";
            
        $query = 'SELECT * FROM ' . $frmdb->forms . $frm_app_helper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
            
        if ($limit == ' LIMIT 1' or $limit == 1){
            if(is_array($where))
                $results = $frmdb->get_one_record($frmdb->forms, $where, '*', $order_by);
            else
                $results = $wpdb->get_row($query);
                
            if($results)
                wp_cache_set($results->id, $results, 'frm_form');
        }else{
            if(is_array($where))
                $results = $frmdb->get_records($frmdb->forms, $where, $order_by, $limit);
            else
                $results = $wpdb->get_results($query);
            
            if($results){
                foreach($results as $result)
                    wp_cache_set($result->id, $result, 'frm_form');
            }
        }
      
        return $results;
    }

  function validate( $values ){
      $errors = array();

      /*if( $values['form_key'] == null or $values['form_key'] == '' ){
          if( $values['name'] == null or $values['name'] == '' )
              $errors[] = "Key can't be blank";
          else 
             $_POST['form_key'] = $values['name'];
      }*/
      
      return apply_filters('frm_validate_form', $errors, $values);
  }

}
?>
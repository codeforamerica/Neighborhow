<?php
class FrmProDisplay{

    function create( $values ){
        global $wpdb, $frmprodb;

        $new_values = array();
        $values['display_key'] = isset($values['display_key']) ? $values['display_key'] : $values['name'];
        $new_values['display_key'] = FrmAppHelper::get_unique_key($values['display_key'], $frmprodb->displays, 'display_key');
        $new_values['name'] = $values['name'];
        $new_values['description'] = $values['description'];
        $new_values['content'] = $values['content'];
        $new_values['dyncontent'] = $values['dyncontent'];
        $new_values['insert_loc'] = isset($values['insert_loc']) ? $values['insert_loc'] : 'none';
        $new_values['param'] = isset($values['param']) ? sanitize_title_with_dashes($values['param']) : '';
        $new_values['type'] = isset($values['type']) ? $values['type'] : '';
        $new_values['show_count'] = isset($values['show_count']) ? $values['show_count'] : 'all';
        $new_values['form_id'] = isset($values['form_id']) ? (int)$values['form_id'] : 0;
        $new_values['entry_id'] = isset($values['entry_id']) ? (int)$values['entry_id'] : 0;
        $new_values['post_id'] = isset($values['post_id']) ? (int)$values['post_id'] : 0;
        $new_values['created_at'] = current_time('mysql', 1);

        if (isset($values['options'])){
            $new_values['options'] = array();
            foreach ($values['options'] as $key => $value)
                $new_values['options'][$key] = $value;
            $new_values['options'] = maybe_serialize($new_values['options']);
        }
        
        $wpdb->insert( $frmprodb->displays, $new_values );
        $display_id = $wpdb->insert_id;
        if ($display_id)
            do_action('frm_create_display', $display_id, $values);

        return $display_id;
    }

    function duplicate( $id, $copy_keys=false, $blog_id=false ){
        global $wpdb, $frmprodb;

        $values = $this->getOne( $id, $blog_id );
        if(!$values)
            return false;
            
        $new_values = array();
        $new_key = ($copy_keys) ? $values->display_key : '';
        $new_values['display_key'] = FrmAppHelper::get_unique_key($new_key, $frmprodb->displays, 'display_key');
        $new_values['name'] = $values->name;
        $new_values['description'] = $values->description;
        $new_values['content'] = $values->content;
        $new_values['dyncontent'] = $values->dyncontent;
        $new_values['insert_loc'] = 'none';
        $new_values['param'] = $values->param;
        $new_values['type'] = $values->type;
        $new_values['show_count'] = $values->show_count;
        $options = maybe_unserialize($values->options);
        $options['copy'] = false;
        $new_values['options'] = maybe_serialize($options);
        if ($blog_id){
            global $frm_form;
            $old_form = $frm_form->getOne($values->form_id, $blog_id);
            $new_form = $frm_form->getOne($old_form->form_key);
            $new_values['form_id'] = $new_form->id;
        }else    
            $new_values['form_id'] = $values->form_id;
        $new_values['entry_id'] = $values->entry_id;
        $new_values['post_id'] = $values->post_id;
        $new_values['options'] = $values->options;
        $new_values['created_at'] = current_time('mysql', 1);

        $query_results = $wpdb->insert( $frmprodb->displays, $new_values );

       if($query_results)
           return $wpdb->insert_id;
       else
          return false;
    }

    function update( $id, $values ){
        global $wpdb, $frmprodb, $frm_field;

        $new_values = array();
        $values['display_key'] = (isset($values['display_key'])) ? $values['display_key'] : $values['name'];
        $new_values['display_key'] = FrmAppHelper::get_unique_key($values['display_key'], $frmprodb->displays, 'display_key', $id);
        $new_values['param'] = isset($values['param']) ? sanitize_title_with_dashes($values['param']) : '';

        $fields = array('name', 'description', 'content', 'dyncontent', 'insert_loc', 'type', 'show_count', 'form_id', 'entry_id', 'post_id');
        foreach ($fields as $field)
            $new_values[$field] = $values[$field];
            
        $new_values['entry_id'] = isset($values['entry_id']) ? (int)$values['entry_id'] : 0;
        
        if (isset($values['options'])){
            $new_values['options'] = array();
            foreach ($values['options'] as $key => $value)
                $new_values['options'][$key] = $value;
            $new_values['options'] = maybe_serialize($new_values['options']);
        }

        $query_results = $wpdb->update( $frmprodb->displays, $new_values, array( 'id' => $id ) );
        if ($query_results){
            wp_cache_delete( $id, 'frm_display');
            do_action('frm_update_display', $id, $values);
        }
        return $query_results;
    }

    function destroy( $id ){
        global $wpdb, $frmprodb;

        $display = $this->getOne($id);
        if (!$display) return false;

        $query_results = $wpdb->query("DELETE FROM $frmprodb->displays WHERE id=$id");
        if ($query_results){
            wp_cache_delete($id, 'frm_display');
            do_action('frm_destroy_display', $id);
        }
        return $query_results;
        
    }

    function &getName( $id ){
        $cache = wp_cache_get($id, 'frm_display');
        if($cache)
            return $cache->name;
        
        global $wpdb, $frmprodb;   
        $query = "SELECT name FROM $frmprodb->displays WHERE id='$id';";
        return $wpdb->get_var($query);
    }

    function &getIdByName( $name ){
        global $wpdb, $frmprodb;
        $query = "SELECT id FROM $frmprodb->displays WHERE name='$name';";
        return $wpdb->get_var($query);
    }
    
    function getOne( $id, $blog_id=false ){
        global $wpdb, $frmprodb, $frmdb;
        
        if(!$blog_id){   
            $cache = wp_cache_get($id, 'frm_display');
            if($cache)
                return $cache;
        }

        if ($blog_id and IS_WPMU){
            global $wpmuBaseTablePrefix;
            if($wpmuBaseTablePrefix)
                $prefix = "{$wpmuBaseTablePrefix}{$blog_id}_";
            else
                $prefix = $wpdb->get_blog_prefix( $blog_id );
                
            $table_name = "{$prefix}frm_display";
        }else{
            $table_name = $frmprodb->displays;
        } 
        
        if (is_numeric($id))
            $where = array('id' => $id);
        else
            $where = array('display_key' => $id);

        $results = $frmdb->get_one_record($table_name, $where);
        if($results and !$blog_id){
            wp_cache_set($results->id, 'frm_display');
            wp_cache_set($results->display_key, 'frm_display');
        }
        return $results;
    }

    function getAll( $where = '', $order_by = '', $limit = '' ){
        global $wpdb, $frmprodb, $frm_app_helper;
        
        if(is_numeric($limit))
            $limit = " LIMIT {$limit}";
            
        $query = 'SELECT * FROM ' . $frmprodb->displays . $frm_app_helper->prepend_and_or_where(' WHERE ', $where) . $order_by . $limit;
        if ($limit == ' LIMIT 1')
            $results = $wpdb->get_row($query);
        else
            $results = $wpdb->get_results($query);
        return $results;
    }

    function validate( $values ){
        $errors = array();

        if( $values['name'] == '' )
            $errors[] = __('Name cannot be blank', 'formidable');
            
        if( $values['description'] == __('This is not displayed anywhere, but is just for your reference. (optional)', 'formidable' ))
            $_POST['description'] = '';
        
        if( $values['content'] == '' )
            $errors[] = __('Content cannot be blank', 'formidable');
        
        if ($values['insert_loc'] != 'none' && $values['post_id'] == '')
            $errors[] = __('Page cannot be blank if you want the content inserted automatically', 'formidable');
            
        if (!empty($values['options']['limit']) && !is_numeric($values['options']['limit']))
            $errors[] = __('Limit must be a number', 'formidable');
        
        if ($values['show_count'] == 'dynamic'){
            if ($values['dyncontent'] == '')
                $errors[] = __('Dynamic Content cannot be blank', 'formidable');
            
            if( !FrmProAppHelper::rewriting_on() ){
                if ($values['param'] == '')
                     $errors[] = __('Parameter Name cannot be blank if content is dynamic', 'formidable');

                 if ($values['type'] == '')
                     $errors[] = __('Parameter Value cannot be blank if content is dynamic', 'formidable');
            }else{
                if ($values['type'] == '')
                     $errors[] = __('Detail Link cannot be blank if content is dynamic', 'formidable');
            }
        }
        
        if(isset($values['options']['where'])){
            $_POST['options']['where'] = FrmProAppHelper::reset_keys($values['options']['where']);
            $_POST['options']['where_is'] = FrmProAppHelper::reset_keys($values['options']['where_is']);
            $_POST['options']['where_val'] = FrmProAppHelper::reset_keys($values['options']['where_val']);
        }
        
        return $errors;
    }

}
?>
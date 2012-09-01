<?php

class FrmProEntryMetaHelper{
    function FrmProEntryMetaHelper(){
        add_filter('frm_email_value', array(&$this, 'email_value'), 10, 3);
    }
    
    function email_value($value, $meta, $entry){
        global $frm_field, $frm_entry;
        
        if($entry->id != $meta->item_id)
            $entry = $frm_entry->getOne($meta->item_id);
        
        $field = $frm_field->getOne($meta->field_id);
        if(!$field)
            return $value;
            
        $field->field_options = maybe_unserialize($field->field_options);
        
        if(isset($field->field_options['post_field']) and $field->field_options['post_field']){
            if($entry->post_id){
                $value = FrmProEntryMetaHelper::get_post_value($entry->post_id, $field->field_options['post_field'], $field->field_options['custom_field'], array('truncate' => false, 'form_id' => $entry->form_id, 'field' => $field, 'type' => $field->type, 'truncate' => true));
            }else if($meta->field_type == 'tag' or $field->field_options['post_field'] == 'post_category' and !empty($value)){
                $value = (array)$value;
                
                $new_value = array();
                foreach($value as $tax_id){
                    if(is_numeric($tax_id)){
                        $cat = $term = get_term( $tax_id, $field->field_options['taxonomy'] );
                        $new_value[] = ($cat) ? $cat->name : $tax_id;
                        unset($cat);
                    }else{
                        $new_value[] = $tax_id;
                    }
                }
                
                $value = $new_value;
            }
        }
        
        switch($field->type){
            case 'user_id':
                $value = FrmProFieldsHelper::get_display_name($value);
                break;
            case 'data':
                if (is_array($value)){
                    $new_value = array();
                    foreach($value as $val)
                        $new_value[] = FrmProFieldsHelper::get_data_value($val, $field);
                    $value = $new_value;
                }else{
                    $value = FrmProFieldsHelper::get_data_value($value, $field);
                }
                break;
            case 'file':
                $value = FrmProFieldsHelper::get_file_name($value);
                break;
            case 'date':
                $value = FrmProFieldsHelper::get_date($value);
        }
        
        if (is_array($value)){
            $new_value = '';
            foreach($value as $val){
                if (is_array($val))
                    $new_value .= implode(', ', $val) . "\n";
            }
            if ($new_value != '')
                $value = $new_value;
        }
        
        return $value;
    }
    
    function display_value($value, $field, $atts=array()){
        global $wpdb;
        
        $defaults = array(
            'type' => '', 'show_icon' => true, 'show_filename' => true, 
            'truncate' => false, 'sep' => ', ', 'post_id' => 0, 'form_id' => $field->form_id,
            'field' => $field
        );
        
        $atts = wp_parse_args( $atts, $defaults );
        $field->field_options = maybe_unserialize($field->field_options);
        
        if(!isset($field->field_options['post_field']))
            $field->field_options['post_field'] = '';
        
        if(!isset($field->field_options['custom_field']))
            $field->field_options['custom_field'] = '';
               
        if($atts['post_id'] and ($field->field_options['post_field'] or $atts['type'] == 'tag')){
            $atts['pre_truncate'] = $atts['truncate'];
            $atts['truncate'] = true;
            $atts['exclude_cat'] = isset($field->field_options['exclude_cat']) ? $field->field_options['exclude_cat'] : 0;
                
            $value = FrmProEntryMetaHelper::get_post_value($atts['post_id'], $field->field_options['post_field'], $field->field_options['custom_field'], $atts);
            $atts['truncate'] = $atts['pre_truncate'];
        }
            
        if ($value == '') return $value;
        
        $value = maybe_unserialize($value);
        if(is_array($value))
            $value = stripslashes_deep($value);
        $value = apply_filters('frm_display_value_custom', $value, $field, $atts);
        
        $new_value = '';
        
        if (is_array($value)){
            foreach($value as $val){
                if (is_array($val)){ //TODO: add options for display (li or ,)
                    $new_value .= implode($atts['sep'], $val);
                    if($atts['type'] != 'data')
                        $new_value .= "<br/>";
                }
                unset($val);
            }
        }

        if (!empty($new_value))
            $value = $new_value;
        else if (is_array($value))
            $value = implode($atts['sep'], $value);

        if ($atts['truncate'] and $atts['type'] != 'image')
            $value = FrmAppHelper::truncate($value, 50);

        if ($atts['type'] == 'image'){
            $value = '<img src="'.$value.'" height="50px" alt="" />';
        }else if ($atts['type'] == 'user_id'){
            $value = FrmProFieldsHelper::get_display_name($value);
        }else if ($atts['type'] == 'file'){
            $old_value = $value;
            $value = '';
            if ($atts['show_icon'])
                $value .= FrmProFieldsHelper::get_file_icon($old_value);
            
            if ($atts['show_icon'] and $atts['show_filename'])
                $value .= '<br/>';
                
            if ($atts['show_filename'])
                $value .= FrmProFieldsHelper::get_file_name($old_value);
        }else if ($atts['type'] == 'date'){
            $value = FrmProFieldsHelper::get_date($value);
        }else if ($atts['type'] == 'data'){
            if(!is_numeric($value)){
                $value = explode($atts['sep'], $value);
                if(is_array($value)){
                    $new_value = '';
                    foreach($value as $entry_id){
                        if(!empty($new_value))
                            $new_value .= $atts['sep'];
                            
                        if(is_numeric($entry_id))
                            $new_value .= FrmProFieldsHelper::get_data_value($entry_id, $field, $atts);
                        else
                            $new_value .= $entry_id;
                    }
                    $value = $new_value;
                }
            }else{
                //replace item id with specified field
                $value = FrmProFieldsHelper::get_data_value($value, $field, $atts);
                
                if($field->field_options['data_type'] == 'data' or $field->field_options['data_type'] == ''){
                    $linked_field = FrmField::getOne($field->field_options['form_select']);
                    if($linked_field->type == 'file'){
                        $old_value = $value;
                        $value = '<img src="'. $value .'" height="50px" alt="" />';
                        if ($atts['show_filename'])
                            $value .= '<br/>'. $old_value;
                    }
                }
            }
        }
        
        //$value = stripslashes_deep($value);

        return apply_filters('frm_display_value', $value, $field, $atts);
    }
    
    function get_post_or_meta_value($entry, $field, $atts=array()){
        global $frm_entry_meta;
        
        if(!is_object($entry)){
            global $frm_entry;
            $entry = $frm_entry->getOne($entry);
        }
        
        $field->field_options = maybe_unserialize($field->field_options);
         
        if($entry->post_id){
            if(!isset($field->field_options['custom_field']))
                $field->field_options['custom_field'] = '';
            
            if(!isset($field->field_options['post_field']))
                $field->field_options['post_field'] = '';
              
            $links = true;
            if(isset($atts['links']))
                $links = $atts['links'];
                
            if($field->type == 'tag' or $field->field_options['post_field']){
                $post_args = array('type' => $field->type, 'form_id' => $field->form_id, 'field' => $field, 'links' => $links, 'exclude_cat' => $field->field_options['exclude_cat']);
                if(isset($atts['show']))
                    $post_args['show'] = $atts['show'];
                $value = FrmProEntryMetaHelper::get_post_value($entry->post_id, $field->field_options['post_field'], $field->field_options['custom_field'], $post_args);
                unset($post_args);
            }else{
                $value = $frm_entry_meta->get_entry_meta_by_field($entry->id, $field->id);
            }
        }else{
            $value = $frm_entry_meta->get_entry_meta_by_field($entry->id, $field->id);
        }

        return $value;
    }
    
    function get_post_value($post_id, $post_field, $custom_field, $atts){
        if(!$post_id) return '';
        $post = get_post($post_id);
        if(!$post) return '';
        
        $defaults = array(
            'sep' => ', ', 'truncate' => true, 'form_id' => false, 
            'field' => array(), 'links' => false, 'show' => ''
        );
        
		$atts = wp_parse_args( $atts, $defaults );

        $value = ''; 
        if ($atts['type'] == 'tag'){
            if(isset($atts['field']->field_options)){
                $field_options = maybe_unserialize($atts['field']->field_options);
                $tax = $field_options['taxonomy'];

            
                if($tags = get_the_terms($post_id, $tax)){
                    $names = array();
                    foreach($tags as $tag){
                        $tag_name = $tag->name;
                        if($atts['links']){
                            $tag_name = '<a href="' . esc_attr( get_term_link($tag, $tax) ) . '" title="' . esc_attr( sprintf(__( 'View all posts filed under %s', 'formidable' ), $tag_name) ) . '">'. $tag_name . '</a>';
                        }
                        $names[] = $tag_name;
                    }
                    $value = implode($atts['sep'], $names);
                }
            }
        }else{
            if($post_field == 'post_custom'){ //get custom post field value
                $value = get_post_meta($post_id, $custom_field, true);
            }else if($post_field == 'post_category'){
                if($atts['form_id']){
                    $post_type = FrmProForm::post_type($atts['form_id']);
                    $taxonomy = FrmProAppHelper::get_custom_taxonomy($post_type, $atts['field']);
                }else{
                    $taxonomy = 'category';
                }
                
                $categories = get_the_terms( $post_id, $taxonomy );

                $names = array();
                $cat_ids = array();
                if($categories){
                    foreach($categories as $cat){
                        if(isset($atts['exclude_cat']) and in_array($cat->term_id, (array)$atts['exclude_cat']))
                            continue;
                            
                        $cat_name = $cat->name;
                        if($atts['links']){
                            $cat_name = '<a href="' . esc_attr( get_term_link($cat, $taxonomy) ) . '" title="' . esc_attr( sprintf(__( 'View all posts filed under %s', 'formidable' ), $cat_name) ) . '">'. $cat_name . '</a>';
                        }
                        
                        $names[] = $cat_name;
                        $cat_ids[] = $cat->term_id;
                    }
                }
            
                if($atts['show'] == 'id')
                    $value = implode($atts['sep'], $cat_ids);
                else if($atts['truncate'])
                    $value = implode($atts['sep'], $names);
                else
                    $value = $cat_ids;
            }else{
                $post = (array)$post;
                $value = $post[$post_field];
            }
        }
        return $value;
    }

}
?>
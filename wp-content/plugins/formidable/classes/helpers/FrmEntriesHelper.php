<?php

class FrmEntriesHelper{

    function setup_new_vars($fields, $form='', $reset=false){
        global $frm_form, $frm_settings, $frm_sidebar_width;
        $values = array();
        foreach (array('name' => '', 'description' => '', 'item_key' => '') as $var => $default)
            $values[$var] = stripslashes(FrmAppHelper::get_post_param($var, $default));
            
        $values['fields'] = array();
        if ($fields){
            foreach($fields as $field){
                $field->field_options = maybe_unserialize($field->field_options);
                $default = $field->default_value;
              
                if ($reset)
                    $new_value = $default;
                else
                    $new_value = ($_POST and isset($_POST['item_meta'][$field->id]) and $_POST['item_meta'][$field->id] != '') ? $_POST['item_meta'][$field->id] : $default;
                
                $is_default = ($new_value == $default) ? true : false;
                    
                $new_value = stripslashes_deep(maybe_unserialize($new_value));
                if (!is_array($new_value))
                    $new_value = apply_filters('frm_get_default_value', $new_value, $field);
                
                $new_value = str_replace('"', '&quot;', $new_value);
                if($is_default)
                    $field->default_value = $new_value;
                else
                    $field->default_value = apply_filters('frm_get_default_value', $field->default_value, $field);
                    
                $field_array = array(
                    'id' => $field->id,
                    'value' => $new_value,
                    'default_value' => maybe_unserialize($field->default_value),
                    'name' => stripslashes($field->name),
                    'description' => stripslashes($field->description),
                    'type' => apply_filters('frm_field_type', $field->type, $field, $new_value),
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
                } */

                $opt_defaults = FrmFieldsHelper::get_default_field_opts($field_array['type'], $field, true);
                $opt_defaults['required_indicator'] = '';
                
                foreach ($opt_defaults as $opt => $default_opt){
                    $field_array[$opt] = (isset($field->field_options[$opt]) && $field->field_options[$opt] != '') ? $field->field_options[$opt] : $default_opt;
                    unset($opt);
                    unset($default_opt);
                }
                  
                unset($opt_defaults);
                
                if ($field_array['size'] == '')
                    $field_array['size'] = $frm_sidebar_width;
            
                
                if ($field_array['custom_html'] == '')
                    $field_array['custom_html'] = FrmFieldsHelper::get_default_html($field->type);

                $values['fields'][] = apply_filters('frm_setup_new_fields_vars', stripslashes_deep($field_array), $field);
             
                if (!$form or !isset($form->id))
                    $form = $frm_form->getOne($field->form_id);
            }

            $form->options = maybe_unserialize($form->options);
            if (is_array($form->options)){
                foreach ($form->options as $opt => $value)
                    $values[$opt] = FrmAppHelper::get_post_param($opt, $value);
            }
            
            if (!isset($values['custom_style']))
                $values['custom_style'] = ($frm_settings->load_style != 'none');
                
            if (!isset($values['email_to']))
                $values['email_to'] = '';

            if (!isset($values['submit_value']))
                $values['submit_value'] = $frm_settings->submit_value;

            if (!isset($values['success_msg']))
                $values['success_msg'] = $frm_settings->success_msg;

            if (!isset($values['akismet']))
                $values['akismet'] = '';

            if (!isset($values['before_html']))
                $values['before_html'] = FrmFormsHelper::get_default_html('before');

            if (!isset($values['after_html']))
                $values['after_html'] = FrmFormsHelper::get_default_html('after');
        }
        return $values;
    }
    
    function setup_edit_vars($values, $record){
        //$values['description'] = maybe_unserialize( $record->description );
        $values['item_key'] = ($_POST and isset($_POST['item_key']))?$_POST['item_key']:$record->item_key;
        $values['form_id'] = $record->form_id;
        return apply_filters('frm_setup_edit_entry_vars', $values, $record);
    }

    function entries_dropdown( $form_id, $field_name, $field_value='', $blank=true, $blank_label='', $onchange=false ){
        global $wpdb, $frmdb;

        $entries = $frmdb->get_records($frmdb->entries, array('form_id' => $form_id), 'name', 999, 'id,item_key,name');
        ?>
        <select name="<?php echo $field_name; ?>" id="<?php echo $field_name; ?>" class="frm-dropdown" <?php if ($onchange) echo 'onchange="'.$onchange.'"'; ?>>
            <?php if ($blank){ ?>
            <option value=""><?php echo $blank_label; ?></option>
            <?php } ?>
            <?php foreach($entries as $entry){ ?>
                <option value="<?php echo $entry->id; ?>" <?php selected($field_value, $entry->id); ?>><?php echo FrmAppHelper::truncate((!empty($entry->name)) ? stripslashes($entry->name) : $entry->item_key, 40); ?></option>
            <?php 
                unset($entry);
            } ?>
        </select>
        <?php
    }
    
    function enqueue_scripts($params){
        do_action('frm_enqueue_form_scripts', $params);
    }
}

?>
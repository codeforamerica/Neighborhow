<?php 
if ($field['type'] == 'hidden'){
    $frm_action = (isset($_GET) and isset($_GET['frm_action'])) ? 'frm_action' : 'action';
    if (is_admin() and (!isset($_GET[$frm_action]) or $_GET[$frm_action] != 'new')){ ?>
<div id="frm_field_<?php $field['id'] ?>_container" class="frm_form_field form-field frm_top_container">
<label class="frm_primary_label"><?php echo $field['name'] ?>:</label> <?php echo $field['value']; ?>
</div>
<?php } 
if (is_array($field['value'])){
    foreach ($field['value'] as $checked){ 
        $checked = apply_filters('frm_hidden_value', $checked, $field); ?>
<input type="hidden" name="<?php echo $field_name ?>[]" value="<?php echo esc_attr($checked) ?>"/>
<?php
    }
}else{ ?>
<input type="hidden" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['value']) ?>"/>
<?php
} 

}else if ($field['type'] == 'user_id'){
    global $user_ID;
    echo '<input type="hidden" id="field_'. $field['field_key'] .'" name="'. $field_name .'" value="'. ((is_numeric($field['value'])) ? $field['value'] : ($user_ID ? $user_ID : '' )) .'"/>'."\n";

}else if ($field['type'] == 'break'){
    global $frm_prev_page, $frm_field;

    if (isset($frm_prev_page[$field['form_id']]) and $frm_prev_page[$field['form_id']] == $field['field_order']){ 
        echo FrmFieldsHelper::replace_shortcodes($field['custom_html'], $field, array(), $form);
        
        $previous_fields = $frm_field->getAll("fi.type not in ('divider','captcha','break','html') and fi.form_id=$field[form_id] and fi.field_order < $field[field_order]"); 
        foreach ($previous_fields as $prev){ 
            if (isset($_POST['item_meta'][$prev->id])){ 
                if (is_array($_POST['item_meta'][$prev->id])){
                    foreach ($_POST['item_meta'][$prev->id] as $checked){
                        $checked = apply_filters('frm_hidden_value', $checked, (array)$prev);
                        echo '<input type="hidden" name="item_meta['.$prev->id.'][]" value="'. $checked .'"/>'."\n";
                    }
                }else{ ?>
<input type="<?php echo apply_filters('frm_paged_field_type', 'hidden', array('field' => $prev)) ?>" id="field_<?php echo $prev->field_key ?>" name="item_meta[<?php echo $prev->id ?>]" value="<?php echo stripslashes(esc_html($_POST['item_meta'][$prev->id])) ?>" />
    <?php       }
            }
        } 
    }else{ ?>
<input type="hidden" name="frm_page_order_<?php echo $field['form_id'] ?>" value="<?php echo esc_attr($field['field_order']); ?>" />
    <?php    
    } 
} ?>
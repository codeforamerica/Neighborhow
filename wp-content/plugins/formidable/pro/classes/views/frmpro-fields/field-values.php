<?php
if(!$new_field)
    return;
    
if ($new_field->type == 'data'){

    if (isset($new_field->field_options['form_select']) && is_numeric($new_field->field_options['form_select']))
        $new_entries = $frm_entry_meta->getAll("it.field_id=".$new_field->field_options['form_select']);
        
    $new_field->options = array();
    if (isset($new_entries) && !empty($new_entries)){
        foreach ($new_entries as $ent)
            $new_field->options[$ent->item_id] = $ent->meta_value;
    }
}else if(isset($new_field->field_options['post_field']) and $new_field->field_options['post_field'] == 'post_status'){
    $new_field->options = FrmProFieldsHelper::get_status_options($new_field);
}else{
    $new_field->options = stripslashes_deep(maybe_unserialize($new_field->options));
}
    

if(isset($new_field->field_options['post_field']) and $new_field->field_options['post_field'] == 'post_category'){
    $new_field = (array)$new_field;
    $new_field['value'] = (isset($field) and isset($field['hide_opt'][$meta_name])) ? $field['hide_opt'][$meta_name] : '';
    $new_field['exclude_cat'] = (isset($new_field->field_options['exclude_cat'])) ? $new_field->field_options['exclude_cat'] : '';
    echo FrmFieldsHelper::dropdown_categories(array('name' => "field_options[hide_opt_{$current_field_id}][]", 'id' => "field_options[hide_opt_{$current_field_id}]", 'field' => $new_field) );
}else{ 
    $temp_field = (array)$new_field;
    foreach($new_field->field_options as $fkey => $fval){
        $temp_field[$fkey] = $fval;
        unset($fkey);
        unset($fval);
    }
?>
<select name="field_options[hide_opt_<?php echo $current_field_id ?>][]">
    <option value=""><?php echo ($new_field->type == 'data') ? 'Anything' : 'Select'; ?></option>
<?php 
    if($new_field->options){
    foreach ($new_field->options as $opt_key => $opt){
        $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $temp_field); //use VALUE instead of LABEL
        $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $temp_field);
        unset($field_array);
        
    $selected = (isset($field) && $field['hide_opt'][$meta_name] == $field_val) ? ' selected="selected"' : ''; ?>
    <option value="<?php echo esc_attr($field_val); ?>"<?php echo $selected; ?>><?php echo FrmAppHelper::truncate($opt, 25); ?></option>
<?php } 
    } ?>
</select>
<?php 
} ?>
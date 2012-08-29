<?php
if ($field['data_type'] == 'select'){ 
    if(!empty($field['options'])){ ?>
<select name="<?php echo $field_name ?>" id="field_<?php echo $field['id'] ?>" <?php do_action('frm_field_input_html', $field) ?>>
<option value=""></option>
<?php if ($field['options']){
    foreach (stripslashes_deep($field['options']) as $opt_key => $opt){ 
$selected = ($field['value'] == $opt_key or in_array($opt_key, (array)$field['value'])) ? ' selected="selected"' : ''; ?>
<option value="<?php echo $opt_key ?>"<?php echo $selected ?>><?php echo $opt ?></option>
<?php } 
} ?>
</select>
<?php 
    }
}else if ($field['data_type'] == 'data' && is_numeric($field['hide_opt']) && is_numeric($field['form_select'])){ 
    global $frm_entry_meta;
    echo $value = $frm_entry_meta->get_entry_meta_by_field($field['hide_opt'], $field['form_select']); ?>
    <input type="hidden" value="<?php echo esc_attr(stripslashes($value)) ?>" name="item_meta[<?php echo $field['form_select'] ?>]" />
<?php }else if ($field['data_type'] == 'data' && is_numeric($field['hide_field']) && is_numeric($field['form_select'])){
    global $frm_entry_meta; 
    if (isset($_POST) && isset($_POST['item_meta']))
        $observed_field_val = $_POST['item_meta'][$field['hide_field']]; 
    else if(isset($_GET) && isset($_GET['id']))
        $observed_field_val = $frm_entry_meta->get_entry_meta_by_field($_GET['id'], $field['hide_field']);
    
    if(isset($observed_field_val) and is_numeric($observed_field_val)) 
        $value = $frm_entry_meta->get_entry_meta_by_field($observed_field_val, $field['form_select']);
    else
        $value = '';
?>
<p><?php echo stripslashes($value) ?></p>
<input type="hidden" value="<?php echo esc_attr(stripslashes($value)) ?>" name="item_meta[<?php echo $field['form_select'] ?>]" />
<?php }else if ($field['data_type'] == 'data' and !is_array($field['value'])){ ?>
<p><?php echo stripslashes($field['value']); ?></p>
<input type="hidden" value="<?php echo esc_attr(stripslashes($field['value'])) ?>" name="item_meta[<?php echo $field['form_select'] ?>]" />
<?php }else if ($field['data_type'] == 'text' && is_numeric($field['form_select'])){ 
    global $frm_entry_meta; 
    if (isset($_POST) && isset($_POST['item_meta']))
        $observed_field_val = $_POST['item_meta'][$field['hide_field']]; 
    else if(isset($_GET) && isset($_GET['id']))
        $observed_field_val = $frm_entry_meta->get_entry_meta_by_field($_GET['id'], $field['hide_field']);
    
    if(isset($observed_field_val) and is_numeric($observed_field_val)) 
        $value = $frm_entry_meta->get_entry_meta_by_field($observed_field_val, $field['form_select']);
    else
        $value = '';
?>
<input type="text" value="<?php echo esc_attr(stripslashes($value)) ?>" name="item_meta[<?php echo $field['id'] ?>]" />

<?php }else if ($field['data_type'] == 'checkbox'){ 
    $checked_values = $field['value'];

    if ($field['options']){
        foreach (stripslashes_deep($field['options']) as $opt_key => $opt){ 
            $checked = ((!is_array($field['value']) && $field['value'] == $opt_key ) || (is_array($field['value']) && in_array($opt_key, $field['value'])))?' checked="true"':''; ?>
<div class="frm_checkbox"><input type="checkbox" name="<?php echo $field_name ?>[]"  id="field_<?php echo $field['id'] ?>-<?php echo $opt_key ?>" value="<?php echo $opt_key ?>" <?php echo $checked ?> <?php do_action('frm_field_input_html', $field) ?> /><label for="field_<?php echo $field['id'] ?>-<?php echo $opt_key ?>"><?php echo $opt ?></label></div>
        <?php }
    }//else echo 'There are no options'; ?>
<?php }else if ($field['data_type'] == 'radio'){
    if ($field['options']){
        foreach (stripslashes_deep($field['options']) as $opt_key => $opt){ 
            $checked = ($field['value'] == $opt_key)?(' checked="checked"'):(''); ?>
<div class="frm_radio"><input type="radio" name="<?php echo $field_name ?>" id="field_<?php echo $field['id'] ?>-<?php echo $opt_key ?>" value="<?php echo $opt_key ?>" <?php echo $checked; ?> <?php do_action('frm_field_input_html', $field) ?> /><label for="field_<?php echo $field['id'] ?>-<?php echo $opt_key ?>"><?php echo $opt ?></label></div>
        <?php }
    }//else echo 'There are no options'; ?> 
<?php }
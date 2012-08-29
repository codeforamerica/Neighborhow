<?php if ($form_list){ ?>
<tr valign="top"><td><?php _e('Import Data from', 'formidable') ?></td>
<td><select name="frm_options_field_<?php echo $field['id'] ?>" id="frm_options_field_<?php echo $field['id'] ?>" onchange="frmGetFieldSelection(this.value,<?php echo $field['id']; ?>,'<?php echo $frm_ajax_url ?>')">  
    <option value="">- <?php _e('Select Form', 'formidable') ?> -</option>
    <option value="taxonomy" <?php if(!is_object($selected_field)) selected($selected_field, 'taxonomy') ?>>- <?php _e('Use a Category/Taxonomy', 'formidable') ?> -</option>
    <?php foreach ($form_list as $form_opts){
    $selected = (is_object($selected_field) and $form_opts->id == $selected_field->form_id) ? ' selected="selected"' : ''; ?>
    <option value="<?php echo $form_opts->id ?>"<?php echo $selected ?>><?php echo stripslashes($form_opts->name) ?></option>
    <?php } ?>
</select>

<span id="frm_show_selected_fields_<?php echo $field['id'] ?>">
    <?php if (is_object($selected_field)) require(FRMPRO_VIEWS_PATH .'/frmpro-fields/field-selection.php');
        else if($selected_field == 'taxonomy') include(FRMPRO_VIEWS_PATH .'/frmpro-fields/data_cat_selected.php');
    ?>
</span>
</td>
</tr>

<tr><td><?php _e('Entries', 'formidable') ?></td> 
    <td><input type="checkbox" name="field_options[restrict_<?php echo $field['id'] ?>]" value="1" <?php echo ($field['restrict'] == 1)? 'checked="checked"' : ''; ?>/> <?php _e('Limit selection choices to those created by the user filling out this form', 'formidable') ?></td>
</tr>
<?php } ?>
<select name="field_options[form_select_<?php echo $current_field_id ?>]">
    <option value=""> - <?php _e('Select Field', 'formidable') ?> - </option>
    <?php foreach ($fields as $field_option){ ?>
    <option value="<?php echo $field_option->id ?>"<?php if(isset($selected_field) and is_object($selected_field)) selected($selected_field->id, $field_option->id) ?>><?php echo stripslashes($field_option->name) ?></option>
    <?php } ?>
</select>
<div id="frm_logic_<?php echo $field['id'] ?>_<?php echo $meta_name ?>" class="frm_logic_row">
<span><a href="javascript:frm_remove_tag('#frm_logic_<?php echo $field['id'] ?>_<?php echo $meta_name ?>');"> X </a></span>
&nbsp;
<select name="field_options[hide_field_<?php echo $field['id'] ?>][]" onchange="frmGetFieldValues(this.value,<?php echo $field['id'] ?>,<?php echo $meta_name ?>,'<?php echo $frm_ajax_url ?>')">
    <option value=""><?php _e('Select Field', 'formidable') ?></option>
    <?php foreach ($form_fields as $ff){ 
        $selected = ($ff->id == $hide_field)?' selected="selected"':''; ?>
    <option value="<?php echo $ff->id ?>"<?php echo $selected ?>><?php echo FrmAppHelper::truncate($ff->name, 30); ?></option>
    <?php } ?>
</select>
<?php _e('is', 'formidable'); ?>
<select name="field_options[hide_field_cond_<?php echo $field['id'] ?>][]">
    <option value="==" <?php selected($field['hide_field_cond'][$meta_name], '==') ?>><?php _e('equal to', 'formidable') ?></option>
    <option value="!=" <?php selected($field['hide_field_cond'][$meta_name], '!=') ?>><?php _e('NOT equal to', 'formidable') ?> &nbsp;</option>
    <option value=">" <?php selected($field['hide_field_cond'][$meta_name], '>') ?>><?php _e('greater than', 'formidable') ?></option>
    <option value="<" <?php selected($field['hide_field_cond'][$meta_name], '<') ?>><?php _e('less than', 'formidable') ?></option>
</select>

<span id="frm_show_selected_values_<?php echo $field['id']; ?>_<?php echo $meta_name ?>" class="no_taglist">
    <?php if ($hide_field and is_numeric($hide_field)){
        global $frm_field, $frm_entry_meta;
        $current_field_id = $field['id'];
        $new_field = $frm_field->getOne($hide_field);
        if($new_field)
            $new_field->field_options = maybe_unserialize($new_field->field_options);

        require(FRMPRO_VIEWS_PATH .'/frmpro-fields/field-values.php');
    } ?>
</span>
</div>
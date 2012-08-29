<?php if($selected_field == 'taxonomy'){ ?>
<span class="howto"><?php _e('Select a taxonomy on the Post tab of the Form Settings page', 'formidable'); ?></span>
<input type="hidden" name="field_options[form_select_<?php echo $current_field_id ?>]" value="taxonomy" />
<?php } ?>
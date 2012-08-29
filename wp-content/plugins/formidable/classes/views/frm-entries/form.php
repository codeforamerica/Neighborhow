<?php 
global $frm_forms_loaded, $frm_load_css, $frm_css_loaded, $frm_settings;
$frm_forms_loaded[] = $form; 
if($values['custom_style']) $frm_load_css = true;

if(!$frm_css_loaded and $frm_load_css){
echo FrmAppController::footer_js('header');
$frm_css_loaded = true;
}

echo FrmFormsHelper::replace_shortcodes($values['before_html'], $form, $title, $description); ?>
<div class="frm_form_fields">
<fieldset>
<div>
<input type="hidden" name="frm_action" value="<?php echo esc_attr($form_action) ?>" />
<input type="hidden" name="form_id" value="<?php echo esc_attr($form->id) ?>" />
<input type="hidden" name="form_key" value="<?php echo esc_attr($form->form_key) ?>" />
<?php if (isset($id)){ ?><input type="hidden" name="id" value="<?php echo esc_attr($id) ?>" /><?php } ?>
<?php if (isset($controller) && isset($plugin)){ ?>
<input type="hidden" name="controller" value="<?php echo esc_attr($controller); ?>" />
<input type="hidden" name="plugin" value="<?php echo esc_attr($plugin); ?>" />
<?php }

if($values['fields']){
foreach($values['fields'] as $field){
    $field_name = "item_meta[". $field['id'] ."]";
    if (apply_filters('frm_show_normal_field_type', true, $field['type']))
        echo FrmFieldsHelper::replace_shortcodes($field['custom_html'], $field, $errors, $form);
    else
        do_action('frm_show_other_field_type', $field, $form);
    
    do_action('frm_get_field_scripts', $field);
}    
}

if (is_admin() && !$frm_settings->lock_keys){ ?>
<div class="frm_form_field form-field">
<label class="frm_primary_label"><?php _e('Entry Key', 'formidable') ?></label>   
<input type="text" name="item_key" value="<?php echo esc_attr($values['item_key']) ?>" />
</div>
<?php }else{ ?>
<input type="hidden" name="item_key" value="<?php echo esc_attr($values['item_key']) ?>" />
<?php }

do_action('frm_entry_form', $form, $form_action, $errors);

global $frm_div;
if($frm_div){
    echo "</div>\n";
    $frm_div = false;
} ?>
</div>
</fieldset>
</div>
<?php echo FrmFormsHelper::replace_shortcodes($values['after_html'], $form); ?>
<script type="text/javascript">
<?php do_action('frm_entries_footer_scripts', $values['fields'], $form); ?>
</script>

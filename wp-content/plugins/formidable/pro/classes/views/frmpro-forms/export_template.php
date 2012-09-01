<?php

$filename = FrmAppHelper::get_unique_key($form->form_key, $frmdb->forms,  'form_key') . '.php';
header("Content-Type: application/x-php");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

echo '<?php ';
?>    
$values['name'] = '<?php echo addslashes($form->name) ?>';
$values['description'] = '<?php echo addslashes($form->description) ?>';
$values['editable'] = <?php echo ($form->editable) ? 1 : 0 ?>;
$values['logged_in'] = <?php echo ($form->logged_in) ? 1 : 0 ?>;
$values['options'] = array();
$values['options']['email_to'] = '<?php echo isset($form->options['email_to']) ? $form->options['email_to'] : '' ?>'; 
$values['options']['submit_value'] = '<?php echo addslashes(isset($form->options['submit_value']) ? $form->options['submit_value'] : $frm_settings->submit_value) ?>'; 
$values['options']['success_msg'] = '<?php echo addslashes(isset($form->options['success_msg']) ? $form->options['success_msg'] : $frm_settings->success_msg) ?>';
$values['options']['show_form'] = <?php echo isset($form->options['show_form']) ? $form->options['show_form'] : 0 ?>;
$values['options']['akismet'] = '<?php echo isset($form->options['akismet']) ? $form->options['akismet'] : '' ?>';
$values['options']['custom_style'] = <?php echo isset($form->options['custom_style']) ? $form->options['custom_style'] : 0 ?>;
$values['options']['before_html'] = '<?php echo addslashes(isset($form->options['before_html']) ? $form->options['before_html'] : FrmFormsHelper::get_default_html('before')) ?>';
$values['options']['after_html'] = '<?php echo addslashes(isset($form->options['after_html']) ? $form->options['after_html'] : FrmFormsHelper::get_default_html('after')) ?>';
$values['options']['single_entry'] = <?php echo (isset($form->options['single_entry'])) ? $form->options['single_entry'] : 0 ?>;
<?php if (isset($form->options['single_entry'])){ ?>
$values['options']['single_entry_type'] = '<?php echo (isset($form->options['single_entry_type'])) ? $form->options['single_entry_type'] : 'cookie' ?>';<?php } ?>

$values['options']['logged_in_role'] = '<?php echo addslashes((isset($form->options['logged_in_role'])) ? $form->options['logged_in_role'] : '') ?>';
$values['options']['editable_role'] = '<?php echo addslashes((isset($form->options['editable_role'])) ? $form->options['editable_role'] : '') ?>';
$values['options']['open_editable'] = <?php echo (isset($form->options['open_editable'])) ? $form->options['open_editable'] : 0 ?>;
$values['options']['open_editable_role'] = '<?php echo (isset($form->options['open_editable_role'])) ? $form->options['open_editable_role'] : '' ?>';
$values['options']['edit_value'] = '<?php echo addslashes((isset($form->options['edit_value'])) ? $form->options['edit_value'] : $frmpro_settings->update_value) ?>';
$values['options']['edit_msg'] = '<?php echo addslashes((isset($form->options['edit_msg'])) ? $form->options['edit_msg'] : '') ?>';

$values['options']['plain_text'] = <?php echo (isset($form->options['plain_text'])) ? $form->options['plain_text'] : 0 ?>;
//$values['options']['reply_to'] = '<?php echo addslashes((isset($form->options['reply_to'])) ? $form->options['reply_to'] : '') ?>';
//$values['options']['reply_to_name'] = '<?php echo addslashes((isset($form->options['reply_to_name'])) ? $form->options['reply_to_name'] : '') ?>';
$values['options']['email_subject'] = '<?php echo addslashes((isset($form->options['email_subject'])) ? $form->options['email_subject'] : '') ?>';
$values['options']['email_message'] = '<?php echo addslashes((isset($form->options['email_message'])) ? $form->options['email_message'] : '[default-message]') ?>';
$values['options']['inc_user_info'] = <?php echo (isset($form->options['inc_user_info'])) ? $form->options['inc_user_info'] : 0 ?>;

$values['options']['auto_responder'] = <?php echo (isset($form->options['auto_responder'])) ? $form->options['auto_responder'] : 0 ?>;
$values['options']['ar_plain_text'] = <?php echo (isset($form->options['ar_plain_text'])) ? $form->options['ar_plain_text'] : 0 ?>;
//$values['options']['ar_email_to'] = '<?php echo addslashes((isset($form->options['ar_email_to'])) ? $form->options['ar_email_to'] : '') ?>';
$values['options']['ar_reply_to'] = '<?php echo addslashes((isset($form->options['ar_reply_to'])) ? $form->options['ar_reply_to'] : '') ?>';
$values['options']['ar_reply_to_name'] = '<?php echo addslashes((isset($form->options['ar_reply_to_name'])) ? $form->options['ar_reply_to_name'] : '') ?>';
$values['options']['ar_email_subject'] = '<?php echo addslashes((isset($form->options['ar_email_subject'])) ? $form->options['ar_email_subject'] : '') ?>';
$values['options']['ar_email_message'] = '<?php echo addslashes((isset($form->options['ar_email_message'])) ? $form->options['ar_email_message'] : '') ?>';

<?php if (IS_WPMU){ ?>$values['options']['copy'] = <?php echo (isset($form->options['copy'])) ? $form->options['copy'] : 0 ?>;<?php } ?>

if ($form){
    $form_id = $form->id;
    $frm_form->update($form_id, $values );
    $form_fields = $frm_field->getAll(array('fi.form_id' => $form_id));
    if (!empty($form_fields)){
        foreach ($form_fields as $field)
            $frm_field->destroy($field->id);
    }
}else
    $form_id = $frm_form->create( $values );

<?php foreach ($fields as $field){ 
    $field->field_options = maybe_unserialize($field->field_options);
    $new_key = FrmAppHelper::get_unique_key($field->field_key, $frmdb->fields, 'field_key'); ?>
    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('<?php echo $field->type ?>', $form_id));
$field_values['field_key'] = '<?php echo $new_key ?>';
<?php foreach (array('name', 'description', 'type', 'default_value', 'options', 'required', 'field_order') as $col){ ?>
$field_values['<?php echo $col ?>'] = '<?php echo ($col != 'options') ? addslashes($field->$col) : $field->$col; ?>';
<?php } ?>
<?php foreach($field->field_options as $opt_key => $field_opt){ 
        if($opt_key == 'custom_html' and $field_opt == FrmFieldsHelper::get_default_html($field->type)) continue; ?>
$field_values['field_options']['<?php echo $opt_key ?>'] = '<?php echo addslashes(maybe_serialize($field_opt)) ?>';
<?php } ?>
$frm_field->create( $field_values );

<?php } ?>
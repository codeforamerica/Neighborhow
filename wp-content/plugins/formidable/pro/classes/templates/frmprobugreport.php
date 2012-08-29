<?php

$values['name'] = 'Defect/Bug/Issue Report';
$values['description'] = 'The following fields are all required to process this report. Enter \'N/A\' in fields that are not applicable.';
$values['options']['custom_style'] = 1;

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


$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Full Name';
$field_values['required'] = 1;
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Company Name';
$field_values['required'] = 1;
$field_values['field_options']['classes'] = 'frm_right_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('phone', $form_id));
$field_values['name'] = 'Phone Number';
$field_values['required'] = 1;
$field_values['field_options']['size'] = '';
$field_values['field_options']['classes'] = 'frm_left_half';
$field_values['field_options']['invalid'] = 'Please enter a valid phone number';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('email', $form_id));
$field_values['name'] = 'Email';
$field_values['required'] = 1;
$field_values['field_options']['classes'] = 'frm_right_half';
$field_values['field_options']['invalid'] = 'Please enter a valid email address';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Problem Title';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Summary Information';
$field_values['description'] = 'Restate the problem title and/or include more descriptive summary information.';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Steps to Reproduce';
$field_values['description'] = 'Include any setup or preparation work and the steps we can take to reproduce the problem.';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Results';
$field_values['description'] = 'Describe your results and how they differed from what you expected.';
$field_values['required'] = 1;
$field_values['field_options']['max'] = 3;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Regression';
$field_values['description'] = 'Provide information on steps taken to isolate the problem. Under what conditions or circumstances does the problem occur or not occur.';
$field_values['required'] = 1;
$field_values['field_options']['max'] = 3;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('radio', $form_id));
$field_values['name'] = 'Is there a Workaround?';
$field_values['field_options']['label'] = 'inline';
$field_values['field_options']['align'] = 'inline';
$field_values['options'] = serialize(array('Yes', 'No'));
$field_values['required'] = 1;
$workaround = $frm_field->create( $field_values, true );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Workaround';
$field_values['description'] = 'If there is a workaround for the problem, please describe it in detail.';
$field_values['required'] = 1;
$field_values['field_options']['max'] = 3;
//$field_values['field_options']['hide_field'] = $workaround;
//$field_values['field_options']['hide_opt'] = 'Yes';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Documentation & Notes';
$field_values['description'] = 'Document any additional information that might be useful in resolving the problem. ';
$field_values['required'] = 1;
$field_values['field_options']['max'] = 3;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Reproducibility';
$field_values['options'] = serialize(array('', 'I didn\'t try', 'Rarely', 'Sometimes', 'Always'));
$field_values['field_options']['classes'] = 'frm_left_half';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Classification';
$field_values['options'] = serialize(array('', 'Security', 'Crash/Hang/Data Loss', 'Performance', 'UI/Usability', 'Serious Bug', 'Other Bug/Has Workaround', 'Feature (New)', 'Enhancement'));
$field_values['field_options']['classes'] = 'frm_right_half';
$field_values['required'] = 1;
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('captcha', $form_id));
$field_values['field_key'] = 'captcha';
$field_values['name'] = 'Captcha';
$field_values['field_options']['label'] = 'none';
$frm_field->create( $field_values );
unset($field_values);
  
?>
<?php     
$values['name'] = 'Create Neighborhow Guide';
$values['description'] = '';
$values['editable'] = 1;
$values['logged_in'] = 0;
$values['options'] = array();
$values['options']['email_to'] = '[admin_email]'; 
$values['options']['submit_value'] = 'Save Guide'; 
$values['options']['success_msg'] = 'Thank you for writing a Neighborhow Guide. It\'s saved as a Draft on your <a href=\"[siteurl]/author/[userloginname]\">Profile page</a>. So you can keep working on it, and when you\'re ready submit it to be posted.';
$values['options']['show_form'] = 1;
$values['options']['akismet'] = '';
$values['options']['custom_style'] = 1;
$values['options']['before_html'] = '[if form_name]<h3>[form_name]</h3>[/if form_name]
[if form_description]<div class=\"frm_description\">[form_description]</div>[/if form_description]
<p class=\"submit\">
<input type=\"submit\" value=\"Save Guide\" />';
$values['options']['after_html'] = '';
$values['options']['single_entry'] = 0;
$values['options']['single_entry_type'] = 'user';
$values['options']['logged_in_role'] = '';
$values['options']['editable_role'] = '';
$values['options']['open_editable'] = 1;
$values['options']['open_editable_role'] = 'administrator';
$values['options']['edit_value'] = 'Save Guide';
$values['options']['edit_msg'] = 'Changes to your Guide were successfully saved.';

$values['options']['plain_text'] = 0;
//$values['options']['reply_to'] = '';
//$values['options']['reply_to_name'] = '';
$values['options']['email_subject'] = 'New Neighborhow Guide created';
$values['options']['email_message'] = 'A new Guide was created.

<a href=\"http://localhost/neighborhow-pagodas/edit-guide?action=edit&entry=[key]\">[guide-title]</a>';
$values['options']['inc_user_info'] = 0;

$values['options']['auto_responder'] = 1;
$values['options']['ar_plain_text'] = 0;
//$values['options']['ar_email_to'] = '249';
$values['options']['ar_reply_to'] = 'information@neighborhow.org';
$values['options']['ar_reply_to_name'] = 'Neighborhow';
$values['options']['ar_email_subject'] = 'Thanks for creating a Neighborhow Guide';
$values['options']['ar_email_message'] = 'You can find your new Neighborhow Guide from your Profile page. Or go directly to the <a href=\"http://localhost/neighborhow-pagodas/edit-guide?action=edit&entry=[key]\">editing page</a>.

When you\'re ready to publish your new Guide, click the \"Publish Guide\" button. Neighborhow Editors will send you an email when it\'s posted so you can share the link with your friends!

Keep creating great content for Neighborhow. And let us know if you have any feedback about the process of creating a Guide or anything else.

--The Neighborhow Team';


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
$field_values['field_key'] = 'guide-title3';
$field_values['name'] = 'Guide Title';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '2';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '40';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Guide.';
$field_values['field_options']['required_indicator'] = '<span class=\\\"required\\\">required</span>';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = 'gde-title';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '1';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = 'post_title';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'guide-description3';
$field_values['name'] = 'Guide Description';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '3';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Guide.';
$field_values['field_options']['required_indicator'] = '<span class=\\\"required\\\">required</span>';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = 'gde-description';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = 'post_content';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'guide-image3';
$field_values['name'] = 'Guide Image';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '4';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please upload an image that represents this Guide.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = 'Invalid image type. Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = 'gde-image';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:3:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '_thumbnail_id';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'guide-user-city3';
$field_values['name'] = 'Guide City';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '5';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter at least one city for this Guide.';
$field_values['field_options']['required_indicator'] = '<span class=\\\"required\\\">required</span>';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = 'gde-user-city';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'gde-user-city';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'cojsq03';
$field_values['name'] = 'Step 1';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '6';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-124';
$field_values['name'] = 'Title 1';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '7';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-01';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-124';
$field_values['name'] = 'Description 1';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '8';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-01';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-17';
$field_values['name'] = 'Media 1';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '9';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-01';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-23';
$field_values['name'] = 'Add Step 2';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '10';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:4:{i:173;b:1;i:174;b:1;i:175;b:1;i:251;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = '4zusbk3';
$field_values['name'] = 'Step 2';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '11';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"172\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-23';
$field_values['name'] = 'Title 2';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '12';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"172\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-02';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-23';
$field_values['name'] = 'Description 2';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '13';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"172\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-02';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-23';
$field_values['name'] = 'Media 2';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '14';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-02';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = '4jioo53';
$field_values['name'] = 'Step 3';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '16';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"251\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-33';
$field_values['name'] = 'Title 3';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '17';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"251\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-03';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-33';
$field_values['name'] = 'Description 3';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '18';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"251\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-03';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-33';
$field_values['name'] = 'Media 3';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '19';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"251\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-03';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-43';
$field_values['name'] = 'Add Step 4';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '20';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"251\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:184;b:1;i:185;b:1;i:186;b:1;i:187;b:1;i:188;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'nk2wjk3';
$field_values['name'] = 'Step 4';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '21';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"183\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-43';
$field_values['name'] = 'Title 4';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '22';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"183\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-04';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-43';
$field_values['name'] = 'Description 4';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '23';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"183\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-04';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-43';
$field_values['name'] = 'Media 4';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '24';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"183\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-04';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-53';
$field_values['name'] = 'Add Step 5';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '25';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"183\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:189;b:1;i:190;b:1;i:191;b:1;i:192;b:1;i:193;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'fbj1ci3';
$field_values['name'] = 'Step 5';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '26';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"188\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-53';
$field_values['name'] = 'Title 5';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '27';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"188\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-05';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-53';
$field_values['name'] = 'Description 5';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '28';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"188\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-05';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-53';
$field_values['name'] = 'Media 5';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '29';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"188\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-05';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-63';
$field_values['name'] = 'Add Step 6';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '30';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"188\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:194;b:1;i:195;b:1;i:196;b:1;i:197;b:1;i:198;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = '111in63';
$field_values['name'] = 'Step 6';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '31';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"193\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-63';
$field_values['name'] = 'Title 6';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '32';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"193\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-06';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-63';
$field_values['name'] = 'Description 6';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '33';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"193\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-06';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-63';
$field_values['name'] = 'Media 6';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '34';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Step Media is invalid';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"193\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-06';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-73';
$field_values['name'] = 'Add Step 7';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '35';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"193\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:199;b:1;i:200;b:1;i:201;b:1;i:202;b:1;i:203;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'nann1x3';
$field_values['name'] = 'Step 7';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '36';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"198\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-73';
$field_values['name'] = 'Title 7';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '37';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"198\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-07';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-73';
$field_values['name'] = 'Description 7';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '38';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"198\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-07';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-73';
$field_values['name'] = 'Media 7';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '39';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"198\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-07';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-83';
$field_values['name'] = 'Add Step 8';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '40';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"198\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:204;b:1;i:205;b:1;i:206;b:1;i:207;b:1;i:208;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = '8ccbuc3';
$field_values['name'] = 'Step 8';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '41';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"203\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-83';
$field_values['name'] = 'Title 8';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '42';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"203\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-83';
$field_values['name'] = 'Description 8';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '43';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"203\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-08';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-83';
$field_values['name'] = 'Media 8';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '44';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Step Media is invalid';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"203\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-08';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-93';
$field_values['name'] = 'Add Step 9';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '45';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"203\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:209;b:1;i:210;b:1;i:211;b:1;i:212;b:1;i:213;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'u6jlro3';
$field_values['name'] = 'Step 9';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '46';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"208\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-93';
$field_values['name'] = 'Title 9';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '47';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"208\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-09';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-93';
$field_values['name'] = 'Description 9';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '48';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"208\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-09';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-93';
$field_values['name'] = 'Media 9';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '49';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"208\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-09';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-103';
$field_values['name'] = 'Add Step 10';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '50';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"208\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:214;b:1;i:215;b:1;i:216;b:1;i:217;b:1;i:218;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'xy2vzc3';
$field_values['name'] = 'Step 10';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '51';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"213\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-103';
$field_values['name'] = 'Title 10';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '52';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"213\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-10';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-103';
$field_values['name'] = 'Description 10';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '53';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"213\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-10';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-103';
$field_values['name'] = 'Media 10';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '54';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"213\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-10';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-113';
$field_values['name'] = 'Add Step 11';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '55';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"213\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:219;b:1;i:220;b:1;i:221;b:1;i:222;b:1;i:223;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'hnokax3';
$field_values['name'] = 'Step 11';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '56';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"218\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-113';
$field_values['name'] = 'Title 11';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '57';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"218\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-11';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-113';
$field_values['name'] = 'Description 11';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '58';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"218\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-11';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-113';
$field_values['name'] = 'Media 11';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '59';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"218\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-11';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-123';
$field_values['name'] = 'Add Step 12';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '60';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"218\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:224;b:1;i:225;b:1;i:226;b:1;i:227;b:1;i:228;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'pva7h83';
$field_values['name'] = 'Step 12';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '61';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"223\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-1223';
$field_values['name'] = 'Title 12';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '62';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"223\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-12';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-1223';
$field_values['name'] = 'Description 12';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '63';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"223\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-12';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-123';
$field_values['name'] = 'Media 12';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '64';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"223\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-12';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-133';
$field_values['name'] = 'Add Step 13';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '65';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"223\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:229;b:1;i:230;b:1;i:231;b:1;i:233;b:1;i:239;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'zu64bb3';
$field_values['name'] = 'Step 13';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '66';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"228\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-133';
$field_values['name'] = 'Title 13';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '67';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"228\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-13';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-133';
$field_values['name'] = 'Media 13';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '69';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"228\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-13';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = 'jjualj3';
$field_values['name'] = 'Step 14';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '71';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"233\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-143';
$field_values['name'] = 'Add Step 14';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '70';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"228\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:232;b:1;i:234;b:1;i:235;b:1;i:236;b:1;i:238;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-143';
$field_values['name'] = 'Title 14';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '72';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"233\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-14';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-143';
$field_values['name'] = 'Media 14';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '74';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"233\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-14';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = 'add-step-153';
$field_values['name'] = 'Add Step 15';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:1;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '75';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"233\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:4:{i:237;b:1;i:240;b:1;i:241;b:1;i:242;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['field_key'] = '81vais3';
$field_values['name'] = 'Step 15';
$field_values['description'] = '';
$field_values['type'] = 'divider';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '76';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"nh_step_container frm_form_field form-field[error_class]\\\">
<h2 class=\\\"frm_pos_[label_position][collapse_class]\\\">[field_name]</h2>
[collapse_this]
[if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"236\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-143';
$field_values['name'] = 'Description 14';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '73';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"233\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-14';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-133';
$field_values['name'] = 'Description 13';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '68';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"228\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-13';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['field_key'] = 'step-title-153';
$field_values['name'] = 'Title 15';
$field_values['description'] = '';
$field_values['type'] = 'text';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '77';
$field_values['field_options']['size'] = '60';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a title for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"236\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-title-15';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['field_key'] = 'step-description-153';
$field_values['name'] = 'Description 15';
$field_values['description'] = '';
$field_values['type'] = 'textarea';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '1';
$field_values['field_order'] = '78';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '5';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'Please enter a description for this Step.';
$field_values['field_options']['required_indicator'] = 'required';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"236\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-description-15';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['field_key'] = 'step-media-153';
$field_values['name'] = 'Media 15';
$field_values['description'] = '';
$field_values['type'] = 'file';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '79';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = 'Please upload an image in JPG, GIF, or PNG format.';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
<a href=\\\"javascript:removeMyFile(\\\'[id]\\\')\\\" id=\\\"remove_link_[id]\\\">Remove File</a>
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"236\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:4:{s:12:\"jpg|jpeg|jpe\";s:10:\"image/jpeg\";s:3:\"gif\";s:9:\"image/gif\";s:3:\"png\";s:9:\"image/png\";s:3:\"pdf\";s:15:\"application/pdf\";}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '1';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = 'step-media-15';
$field_values['field_options']['post_field'] = 'post_custom';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('user_id', $form_id));
$field_values['field_key'] = 'wxy3zt3';
$field_values['name'] = 'User ID';
$field_values['description'] = '';
$field_values['type'] = 'user_id';
$field_values['default_value'] = '';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '80';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['field_key'] = '1gggxt3';
$field_values['name'] = 'Add Step 3';
$field_values['description'] = '';
$field_values['type'] = 'checkbox';
$field_values['default_value'] = '';
$field_values['options'] = 'a:1:{i:0;s:16:"Add another Step";}';
$field_values['required'] = '0';
$field_values['field_order'] = '15';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input class=\\\"nh_add_another\\\"]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'inline';
$field_values['field_options']['hide_field'] = 'a:1:{i:0;s:3:\"172\";}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:1:{i:0;s:16:\"Add another Step\";}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = 'a:5:{i:178;b:1;i:180;b:1;i:181;b:1;i:182;b:1;i:183;b:1;}';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('hidden', $form_id));
$field_values['field_key'] = 'zw1nhx2';
$field_values['name'] = 'Guide Post ID';
$field_values['description'] = '';
$field_values['type'] = 'hidden';
$field_values['default_value'] = '[post_id]';
$field_values['options'] = '';
$field_values['required'] = '0';
$field_values['field_order'] = '0';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['required_indicator'] = '';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '0';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = '';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );

    
$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['field_key'] = 'zk6zhp2';
$field_values['name'] = 'Guide Category';
$field_values['description'] = '';
$field_values['type'] = 'select';
$field_values['default_value'] = '24';
$field_values['options'] = 'a:2:{i:0;s:0:"";i:1;s:8:"Option 1";}';
$field_values['required'] = '0';
$field_values['field_order'] = '1';
$field_values['field_options']['size'] = '';
$field_values['field_options']['max'] = '';
$field_values['field_options']['label'] = '';
$field_values['field_options']['blank'] = 'This field cannot be blank';
$field_values['field_options']['required_indicator'] = '*';
$field_values['field_options']['invalid'] = '';
$field_values['field_options']['separate_value'] = '0';
$field_values['field_options']['clear_on_focus'] = '0';
$field_values['field_options']['default_blank'] = '0';
$field_values['field_options']['classes'] = '';
$field_values['field_options']['custom_html'] = '<div id=\\\"frm_field_[id]_container\\\" class=\\\"frm_form_field form-field [required_class][error_class]\\\">
    <label class=\\\"frm_primary_label\\\">[field_name]
        <span class=\\\"frm_required\\\">[required_label]</span>
    </label>
    [input]
    [if description]<div class=\\\"frm_description\\\">[description]</div>[/if description]
    [if error]<div class=\\\"frm_error\\\">[error]</div>[/if error]
</div>';
$field_values['field_options']['slide'] = '0';
$field_values['field_options']['form_select'] = '';
$field_values['field_options']['show_hide'] = 'show';
$field_values['field_options']['any_all'] = 'any';
$field_values['field_options']['align'] = 'block';
$field_values['field_options']['hide_field'] = 'a:0:{}';
$field_values['field_options']['hide_field_cond'] = 'a:1:{i:0;s:2:\"==\";}';
$field_values['field_options']['hide_opt'] = 'a:0:{}';
$field_values['field_options']['star'] = '0';
$field_values['field_options']['ftypes'] = 'a:0:{}';
$field_values['field_options']['data_type'] = '';
$field_values['field_options']['restrict'] = '0';
$field_values['field_options']['start_year'] = '2000';
$field_values['field_options']['end_year'] = '2020';
$field_values['field_options']['read_only'] = '0';
$field_values['field_options']['admin_only'] = '1';
$field_values['field_options']['locale'] = '';
$field_values['field_options']['attach'] = '';
$field_values['field_options']['minnum'] = '0';
$field_values['field_options']['maxnum'] = '9999';
$field_values['field_options']['step'] = '1';
$field_values['field_options']['clock'] = '12';
$field_values['field_options']['start_time'] = '00:00';
$field_values['field_options']['end_time'] = '23:59';
$field_values['field_options']['unique'] = '0';
$field_values['field_options']['use_calc'] = '0';
$field_values['field_options']['calc'] = '';
$field_values['field_options']['duplication'] = '1';
$field_values['field_options']['rte'] = 'nicedit';
$field_values['field_options']['dyn_default_value'] = '';
$field_values['field_options']['dependent_fields'] = '';
$field_values['field_options']['custom_field'] = '';
$field_values['field_options']['post_field'] = 'post_category';
$field_values['field_options']['taxonomy'] = 'category';
$field_values['field_options']['exclude_cat'] = '0';
$frm_field->create( $field_values );


<?php

$values['name'] = 'Job Application';
$values['description'] = '';
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
$field_values['name'] = 'Name';
$field_values['description'] = 'First';
$field_values['required'] = 1;
$field_values['field_order'] = '1';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = $field_values['description'] = 'Last';
$field_values['required'] = 1;
$field_values['field_order'] = '2';
$field_values['field_options']['label'] = 'hidden';
$field_values['field_options']['classes'] = 'frm_right_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('email', $form_id));
$field_values['name'] = 'Email';
$field_values['required'] = 1;
$field_values['field_order'] = '3';
$field_values['field_options']['classes'] = 'frm_full';
$field_values['field_options']['invalid'] = 'Please enter a valid email address';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('phone', $form_id));
$field_values['name'] = 'Home Phone';
$field_values['field_order'] = '4';
$field_values['field_options']['size'] = '';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('phone', $form_id));
$field_values['name'] = 'Mobile Phone';
$field_values['field_order'] = '5';
$field_values['field_options']['size'] = '';
$field_values['field_options']['classes'] = 'frm_right_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Address';
$field_values['required'] = 1;
$field_values['field_order'] = '6';
$field_values['field_options']['classes'] = 'frm_full';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Address Line 2';
$field_values['field_order'] = '7';
$field_values['field_options']['classes'] = 'frm_full';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'City';
$field_values['required'] = 1;
$field_values['field_order'] = '8';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'State';
$field_values['required'] = 1;
$field_values['field_order'] = '9';
$field_values['options'] = serialize(array('', 'AL', 'AK', 'AR', 'AZ', 'CA', 'CO', 'CT', 'DE', 'DC', 'FL', 'GA', 'HI', 'ID', 'IL', 'IN', 'IA', 'KS', 'KY', 'LA', 'ME', 'MH', 'MD', 'MA', 'MI', 'MN', 'MS', 'MO', 'MT', 'NE', 'NV', 'NH', 'NJ', 'NM', 'NY', 'NC', 'ND', 'OH', 'OK', 'OR', 'PA', 'RI', 'SC', 'SD', 'TN', 'TX', 'UT', 'VT', 'VA', 'WA', 'WV', 'WI', 'WY'));
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Postal Code';
$field_values['field_order'] = '10';
$field_values['field_options']['classes'] = 'frm_right_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Country';
$field_values['required'] = 1;
$field_values['field_order'] = '11';
$field_values['field_options']['size'] = 1;
$field_values['options'] = serialize(array('', 'Afghanistan', 'Albania', 'Algeria', 'American Samoa', 'Andorra', 'Angola', 'Anguilla', 'Antarctica', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Aruba', 'Australia', 'Austria', 'Azerbaijan', 'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bermuda', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Bouvet Island', 'Brazil', 'British Indian Ocean Territory', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Cayman Islands', 'Central African Republic', 'Chad', 'Chile', 'China', 'Christmas Island', 'Cocos (Keeling) Islands', 'Colombia', 'Comoros', 'Congo', 'Cook Islands', 'Costa Rica', 'Côte d\'Ivoire', 'Croatia (Hrvatska)', 'Cuba', 'Cyprus', 'Czech Republic', 'Congo (DRC)', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Falkland Islands (Islas Malvinas)', 'Faroe Islands', 'Fiji Islands', 'Finland', 'France', 'French Guiana', 'French Polynesia', 'French Southern and Antarctic Lands', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Gibraltar', 'Greece', 'Greenland', 'Grenada', 'Guadeloupe', 'Guam', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hong Kong SAR', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macao SAR', 'Macedonia, Former Yugoslav Republic of', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Martinique', 'Mauritania', 'Mauritius', 'Mayotte', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montserrat', 'Morocco', 'Mozambique', 'Myanmar', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Puerto Rico', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia and Montenegro', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('radio', $form_id));
$field_values['name'] = 'Employment Desired';
$field_values['required'] = 1;
$field_values['field_order'] = '12';
$field_values['options'] = serialize(array('Full-time', 'Part-time', 'Full- or Part- time'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['name'] = 'Attach Resume';
$field_values['field_order'] = '13';
$field_values['field_options']['attach'] = 1;
$frm_field->create( $field_values );
unset($field_values);
?>
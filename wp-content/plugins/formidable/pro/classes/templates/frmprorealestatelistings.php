<?php

$values['name'] = 'Real Estate Listings';
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
$field_values['name'] = 'MLS ID';
$field_values['required'] = 1;
$field_values['field_order'] = '0';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Featured';
$field_values['options'] = serialize(array('Featured'));
$field_values['field_order'] = '1';
$field_values['field_options']['label'] = 'hidden';
$field_values['field_options']['classes'] = 'frm_right_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Street Address';
$field_values['description'] = 'e.g., "123 Main St"';
$field_values['field_order'] = '2';
$field_values['required'] = 1;
$field_values['field_options']['classes'] = 'frm_full';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'City';
$field_values['description'] = 'e.g., "Anytown"';
$field_values['required'] = 1;
$field_values['field_order'] = '3';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'State';
$field_values['required'] = 1;
$field_values['options'] = 'a:51:{i:0;s:0:"";i:1;s:7:"Alabama";i:2;s:6:"Alaska";i:3;s:8:"Arkansas";i:4;s:7:"Arizona";i:5;s:10:"California";i:6;s:8:"Colorado";i:7;s:11:"Connecticut";i:8;s:8:"Delaware";i:9;s:7:"Florida";i:10;s:7:"Georgia";i:11;s:6:"Hawaii";i:12;s:5:"Idaho";i:13;s:8:"Illinois";i:14;s:7:"Indiana";i:15;s:4:"Iowa";i:16;s:6:"Kansas";i:17;s:8:"Kentucky";i:18;s:9:"Louisiana";i:19;s:5:"Maine";i:20;s:8:"Maryland";i:21;s:13:"Massachusetts";i:22;s:8:"Michigan";i:23;s:9:"Minnesota";i:24;s:11:"Mississippi";i:25;s:8:"Missouri";i:26;s:7:"Montana";i:27;s:8:"Nebraska";i:28;s:6:"Nevada";i:29;s:13:"New Hampshire";i:30;s:10:"New Jersey";i:31;s:10:"New Mexico";i:32;s:8:"New York";i:33;s:14:"North Carolina";i:34;s:12:"North Dakota";i:35;s:4:"Ohio";i:36;s:8:"Oklahoma";i:37;s:6:"Oregon";i:38;s:12:"Pennsylvania";i:39;s:12:"Rhode Island";i:40;s:14:"South Carolina";i:41;s:12:"South Dakota";i:42;s:9:"Tennessee";i:43;s:5:"Texas";i:44;s:4:"Utah";i:45;s:7:"Vermont";i:46;s:8:"Virginia";i:47;s:10:"Washington";i:48;s:13:"West Virginia";i:49;s:9:"Wisconsin";i:50;s:7:"Wyoming";}';
$field_values['field_order'] = '4';
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Zip Code';
$field_values['required'] = 1;
$field_values['field_order'] = '5';
$field_values['field_options']['classes'] = 'frm_right_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Featured';
$field_values['options'] = serialize(array('Featured'));
$field_values['field_options']['label'] = 'none';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Type';
$field_values['options'] = serialize(array('', 'Single Family Home', 'Condo/Townhome/Row Home/Co-Op', 'Multi-Family Home', 'Mfd/Mobile Home', 'Farms/Ranches', 'Land'));
$field_values['field_order'] = '6';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('select', $form_id));
$field_values['name'] = 'Property Status';
$field_values['required'] = 1;
$field_values['field_order'] = '7';
$field_values['options'] = serialize(array('Active', 'Sale Pending', 'Sold', 'Lease Pending', 'Rented' ));
$field_values['field_options']['classes'] = 'frm_right_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'List Price';
$field_values['required'] = 1;
$field_values['field_order'] = '8';
$field_values['field_options']['size'] = '12';
$field_values['field_options']['classes'] = 'frm_left_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('date', $form_id));
$field_values['name'] = 'List Date';
$field_values['default_value'] = '[date]';
$field_values['field_order'] = '9';
$field_values['field_options']['size'] = 10;
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Sale Price';
$field_values['field_order'] = '10';
$field_values['field_options']['size'] = 12;
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('date', $form_id));
$field_values['name'] = 'Sale Date';
$field_values['field_order'] = '11';
$field_values['field_options']['blank'] = '';
$field_values['field_options']['size'] = 10;
$field_values['field_options']['classes'] = 'frm_right_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Brief Blurb';
$field_values['description'] = 'e.g., "Nice 4BR home west of Lantana"';
$field_values['field_order'] = '12';
$field_values['field_options']['classes'] = 'frm_full';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('textarea', $form_id));
$field_values['name'] = 'Description';
$field_values['description'] = 'A more detailed description';
$field_values['required'] = 1;
$field_values['field_order'] = '13';
$field_values['field_options']['classes'] = 'frm_full';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Bedrooms';
$field_values['field_order'] = '15';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Full Baths';
$field_values['field_order'] = '16';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Garage Spaces';
$field_values['field_order'] = '17';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_right_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Sqft (Living)';
$field_values['field_order'] = '18';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_left_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Sqft (Total)';
$field_values['field_order'] = '19';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Acres';
$field_values['field_order'] = '20';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'Year Built';
$field_values['field_order'] = '14';
$field_values['field_options']['size'] = '5';
$field_values['field_options']['classes'] = 'frm_left_fourth';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('image', $form_id));
$field_values['name'] = 'Main Photo URL';
$field_values['description'] = 'If using a photo that is already online, you can insert the URL here.';
$field_values['field_order'] = '21';
$field_values['field_options']['classes'] = 'frm_left_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('file', $form_id));
$field_values['name'] = 'Main Photo Upload';
$field_values['description'] = 'Or if you would like to upload the photo, this would be a good spot.';
$field_values['field_order'] = '22';
$field_values['field_options']['classes'] = 'frm_right_half';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('text', $form_id));
$field_values['name'] = 'NextGen Gallery ID';
$field_values['description'] = 'If you would like to post a Photo Gallery, insert the NextGen gallery ID for this home here.';
$field_values['field_order'] = '23';
$field_values['field_options']['size'] = '5';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['name'] = 'Property Features';
$field_values['field_order'] = '24';
$field_values['field_options']['label'] = 'top';
$field_values['field_options']['classes'] = 'frm_left_third';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'General Features';
$field_values['options'] = serialize(array('Balcony', 'BBQ', 'Courtyard', 'Horse Facilities', 'Greenhouse', 'Lease Option', 'Pets Allowed', 'RV/Boat Parking', 'Spa/Hot Tub', 'Tennis Court(s)'));
$field_values['field_order'] = '25';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Interior';
$field_values['options'] = serialize(array('Ceiling Fans', 'Custom Window Covering', 'Disability Features', 'Energy Efficient Home', 'Hardwood Floors', 'Home Warranty', 'Intercom', 'Pool', 'Skylight', 'Window Blinds', 'Window Coverings', 'Window Drapes/Curtains', 'Window Shutters', 'Vaulted Ceiling'));
$field_values['field_order'] = '26';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['name'] = 'Column 2';
$field_values['field_order'] = '27';
$field_values['field_options']['label'] = 'hidden';
$field_values['field_options']['classes'] = 'frm_third';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Rooms';
$field_values['options'] = serialize(array('Dining Room', 'Family Room', 'Den/Office', 'Basement', 'Laundry Room', 'Game Room'));
$field_values['field_order'] = '28';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Air Conditioning';
$field_values['options'] = serialize(array('Central Air', 'Forced Air'));
$field_values['field_order'] = '29';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Heat';
$field_values['options'] = serialize(array('Central', 'Electric', 'Multiple Units', 'Natural Gas', 'Solar', 'Wall Furnace', 'Wood', 'None'));
$field_values['field_order'] = '30';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Fireplace';
$field_values['options'] = serialize(array('Freestanding', 'Gas Burning', 'Two-way', 'Wood Burning'));
$field_values['field_order'] = '31';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['name'] = 'Column 3';
$field_values['field_order'] = '32';
$field_values['field_options']['label'] = 'hidden';
$field_values['field_options']['classes'] = 'frm_right_third';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Lot Features';
$field_values['field_order'] = '33';
$field_values['options'] = serialize(array('Corner Lot', 'Cul-de-Sac', 'Golf Course Lot/Frontage', 'Golf Course View', 'Waterfront', 'City View', 'Lake View', 'Hill/Mountain View', 'Ocean View', 'Park View', 'River View', 'Water View', 'View'));
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('checkbox', $form_id));
$field_values['name'] = 'Community Features';
$field_values['options'] = serialize(array('Recreation Facilities', 'Community Security Features', 'Community Swimming Pool(s)', 'Community Boat Facilities', 'Community Clubhouse(s)', 'Community Horse Facilities', 'Community Tennis Court(s)', 'Community Park(s)', 'Community Golf', 'Senior Community', 'Community Spa/Hot Tub(s)'));
$field_values['field_order'] = '34';
$frm_field->create( $field_values );
unset($field_values);

$field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars('divider', $form_id));
$field_values['name'] = 'End Columns';
$field_values['field_order'] = '35';
$field_values['field_options']['label'] = 'none';
$frm_field->create( $field_values );
unset($field_values);


  
?>
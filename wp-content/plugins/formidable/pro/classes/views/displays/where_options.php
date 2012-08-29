<?php if(isset($field) and $field->type == 'user_id'){ ?>
<select name="options[where_val][<?php echo $where_key; ?>]">
<option value="current_user">Current User</option>
<?php
$users = FrmProFieldsHelper::get_user_options();
foreach($users as $user_id => $user_login){ 
    if(empty($user_id)) continue;
?>
<option value="<?php echo $user_id ?>" <?php selected($where_val, $user_id) ?>><?php echo $user_login ?></option>
<?php } ?>
</select>
<?php //}else if(in_array($field->type, array('select', 'radio'))){ ?>
<?php }else if(isset($field->field_options) and isset($field->field_options['post_field']) and $field->field_options['post_field'] == 'post_status'){ 
$options = FrmProFieldsHelper::get_status_options($field); ?>
<select name="options[where_val][<?php echo $where_key; ?>]">
    <?php foreach($options as $opt_key => $opt){ ?>
    <option value="<?php echo $opt_key ?>" <?php selected($where_val, $opt_key)?>><?php echo $opt ?></option>
    <?php } ?>
</select>
<?php }else{ ?>
<?php if(isset($field) and $field->type == 'date'){ ?><img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Date options: \'NOW\' or a date in yyyy-mm-dd format.', 'formidable') ?>" /> <?php } ?>
<input type="text" size="15" value="<?php echo esc_attr($where_val) ?>" name="options[where_val][<?php echo $where_key; ?>]"/>
<?php } ?>
<?php if(!empty($values['fields'])){ ?>
<tr valign="top">
    <td><label><?php _e('and/or', 'formidable') ?></label> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Select a user id, hidden, or email field to send a notification.', 'formidable') ?>" /></td>
    <td>
        <?php 
            $field_select = array('email', 'user_id', 'hidden', 'select', 'radio', 'checkbox');
        foreach($values['fields'] as $val_key => $fo){
            if(in_array($fo['type'], $field_select)){ ?>
                <input type="checkbox" name="options[also_email_to][]" value="<?php echo $fo['id'] ?>" <?php FrmAppHelper::checked($values['also_email_to'], $fo['id']); ?> /> <?php echo $fo['name'] ?><br/>
        <?php }else if($fo['type'] == 'data' and $fo['data_type'] != 'show'){
                $linked_form_id = $frmdb->get_var($frmdb->fields, array('id' => $fo['form_select']), 'form_id');

                if($linked_form_id)
                    $linked_fields = $wpdb->get_results("SELECT * FROM $frmdb->fields WHERE type in ('".implode("','", $field_select)."', 'text') and form_id=$linked_form_id");
                if(isset($linked_fields) and $linked_fields){ 
                $values['fields'][$val_key]['linked'] = $linked_fields;
                foreach($linked_fields as $linked_field){ 
                    if(!in_array($linked_field->type, $field_select)) continue; ?>
                    <input type="checkbox" name="options[also_email_to][]" value="<?php echo $fo['id'] .'|'. $linked_field->id ?>" <?php FrmAppHelper::checked($values['also_email_to'], $fo['id'] .'|'. $linked_field->id); ?> /> <?php echo $fo['name'] .': '. FrmAppHelper::truncate($linked_field->name, 80) ?><br/>
                <?php } 
                }
            }
        } ?>
    </td>
</tr>
<?php } ?>
<tr valign="top">
    <td><label><?php _e('Subject', 'formidable') ?></label></td>
    <td><?php if(isset($values['id'])) FrmProFieldsHelper::get_shortcode_select($values['id'], 'email_subject'); ?><br/>
<input type="text" name="options[email_subject]" id="email_subject" size="55" value="<?php echo esc_attr($values['email_subject']); ?>" style="width:98%"/></td>
</tr>

<tr valign="top">
    <td><label><?php _e('Message', 'formidable') ?> </label></td>
    <td><?php if(isset($values['id'])) FrmProFieldsHelper::get_shortcode_select($values['id'], 'email_message', 'email'); ?><br/>
        <textarea name="options[email_message]" id="email_message" cols="50" rows="5" class="frm_long_input"><?php echo FrmAppHelper::esc_textarea($values['email_message']) ?></textarea>
    </td>
</tr>

<tr valign="top">
    <td></td>
    <td><input type="checkbox" name="options[inc_user_info]" id="inc_user_info" value="1" <?php checked($values['inc_user_info'], 1); ?> /> <?php _e('Append IP Address, Browser, and Referring URL to message', 'formidable') ?></td>
</tr>

<tr valign="top">
    <td><label><?php _e('Email Format', 'formidable') ?></label></td>
    <td><input type="checkbox" name="options[plain_text]" id="plain_text" value="1" <?php checked($values['plain_text'], 1); ?> /> <?php _e('Send Emails in Plain Text', 'formidable') ?></td>
</tr>

<tr valign="top">
    <td><label><?php _e('Update Emails', 'formidable') ?></label></td>
    <td><input type="checkbox" name="options[update_email]" value="1" <?php checked($values['update_email'], 1); ?> /> <?php _e('Automatically resend this email notification when entries are updated', 'formidable') ?></td>
</tr>
</table>
</div>

<div class="notification_settings tabs-panel panel_secondary" style="display:none;margin-top:10px">
	<table class="form-table">
<tr valign="top">
    <td width="200px"><label style="font-weight:bold"><?php _e('Auto Responder', 'formidable') ?></label></td>
    <td><input type="checkbox" name="options[auto_responder]" id="auto_responder" value="1" <?php checked($values['auto_responder'], 1); ?> onclick="frm_show_div('hide_ar',this.checked,1,'.')" /> <?php _e('Send an automatic response to users submitting the form', 'formidable') ?></td>
</tr>

<tr valign="top" class="hide_ar">
    <td><label><?php _e('From/Reply to', 'formidable') ?></label></td>
    <td><span class="howto"><?php _e('Name', 'formidable') ?></span> <input type="text" name="options[ar_reply_to_name]" value="<?php echo esc_attr($values['ar_reply_to_name']) ?>" title="<?php _e('Name', 'formidable') ?>">
    
    <span class="howto" style="margin-left:10px"><?php _e('Email', 'formidable') ?></span> &lt;<input type="text" name="options[ar_reply_to]" value="<?php echo esc_attr($values['ar_reply_to']) ?>" title="<?php _e('Email Address', 'formidable') ?>">>
    </td>
</tr>

<tr valign="top" class="hide_ar">
    <td><label><?php _e('Email Recipient', 'formidable') ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Select a user id, hidden, or email field to send the autoresponse.', 'formidable') ?>" /></label></td>
    <td><select name="options[ar_email_to]">
        <option value=""></option>
        <?php 
        if(!empty($values['fields'])){
        foreach($values['fields'] as $val_key => $fo){
            if(in_array($fo['type'], array('email', 'user_id', 'hidden'))){ ?>
                <option value="<?php echo $fo['id'] ?>" <?php selected($values['ar_email_to'], $fo['id']); ?>><?php echo $fo['name'] ?></option>
        <?php }else if($fo['type'] == 'data' and $fo['data_type'] != 'show'){
                if(isset($values['fields'][$val_key]['linked'])){ ?>
                <?php foreach($values['fields'][$val_key]['linked'] as $linked_field){ 
                    if(!in_array($linked_field->type, $field_select)) continue; ?>
                    <option value="<?php echo $fo['id'] ?>|<?php echo $linked_field->id ?>" <?php selected($values['ar_email_to'], $fo['id'] .'|'. $linked_field->id); ?>><?php echo $fo['name'] .': '. FrmAppHelper::truncate($linked_field->name, 80) ?></option>
                <?php } 
                }
            }
        }
        } ?>
    </select>
    </td>
</tr>

<tr valign="top" class="hide_ar">
    <td><label><?php _e('Subject', 'formidable') ?></label></td>
    <td><?php if(isset($values['id'])) FrmProFieldsHelper::get_shortcode_select($values['id'], 'ar_email_subject'); ?><br/>
<input type="text" name="options[ar_email_subject]" id="ar_email_subject" size="55" value="<?php echo esc_attr($values['ar_email_subject']); ?>" style="width:98%"/></td>
</tr>

<tr valign="top" class="hide_ar">
    <td><label><?php _e('Message', 'formidable') ?></label></td>
    <td><?php if(isset($values['id'])) FrmProFieldsHelper::get_shortcode_select($values['id'], 'ar_email_message', 'email'); ?><br/>
        <textarea name="options[ar_email_message]" id="ar_email_message" cols="50" rows="5" style="width:98%"><?php echo FrmAppHelper::esc_textarea($values['ar_email_message']) ?></textarea>
    </td>
</tr>

<tr valign="top" class="hide_ar">
    <td><label><?php _e('Email Format', 'formidable') ?></label></td>
    <td><input type="checkbox" name="options[ar_plain_text]" id="ar_plain_text" value="1" <?php checked($values['ar_plain_text'], 1); ?> /> <?php _e('Send Emails in Plain Text', 'formidable') ?></td>
</tr>

<tr valign="top" class="hide_ar">
    <td><label><?php _e('Update Emails', 'formidable') ?></label></td>
    <td><input type="checkbox" name="options[ar_update_email]" value="1" <?php checked($values['ar_update_email'], 1); ?> /> <?php _e('Automatically resend this email notification when entries are updated', 'formidable') ?></td>
</tr>
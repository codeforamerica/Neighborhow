<?php
class FrmProNotification{
    function FrmProNotification(){
        add_filter('frm_stop_standard_email', array(&$this, 'stop_standard_email'));
        add_action('frm_after_create_entry', array(&$this, 'entry_created'), 11, 2);
        add_action('frm_after_update_entry', array(&$this, 'entry_updated'), 11, 2);
        add_action('frm_after_create_entry', array(&$this, 'autoresponder'), 11, 2);
    }
    
    function stop_standard_email(){
        return true;
    }
    
    function entry_created($entry_id, $form_id){
        if(defined('WP_IMPORTING'))
            return;
            
        global $frm_form, $frm_entry, $frm_entry_meta, $frm_notification, $frmpro_settings;

        $form = $frm_form->getOne($form_id);
        $form_options = maybe_unserialize($form->options);
        $entry = $frm_entry->getOne($entry_id, true);
        
        $to_email = $form_options['email_to'];
        $email_fields = (isset($form_options['also_email_to'])) ? (array)$form_options['also_email_to'] : array();
        $entry_ids = array($entry->id);
        $exclude_fields = array();
        
        foreach($email_fields as $key => $email_field){
            $email_fields[$key] = (int)$email_field;
            if(preg_match('/|/', $email_field)){
                $email_opt = explode('|', $email_field);
                if(isset($email_opt[1])){
                    if(isset($entry->metas[$email_opt[0]])){
                        $add_id = $entry->metas[$email_opt[0]];

                        $add_id = maybe_unserialize($add_id);
                        if(is_array($add_id)){
                            foreach($add_id as $add)
                                $entry_ids[] = $add;
                        }else{
                            $entry_ids[] = $add_id;
                        }
                    }

                    //skip the data field if it will be fetched through the other form
                    $exclude_fields[] = $email_opt[0];
                    $email_fields[$key] = (int)$email_opt[1];
                }
                unset($email_opt);
            }
        }

        if ($to_email == '' and empty($email_fields)) return;
        
        foreach($email_fields as $email_field){
            if(isset($form_options['reply_to_name']) and preg_match('/|/', $email_field)){
                $email_opt = explode('|', $form_options['reply_to_name']);
                if(isset($email_opt[1])){
                    if(isset($entry->metas[$email_opt[0]]))
                        $entry_ids[] = $entry->metas[$email_opt[0]];
                    //skip the data field if it will be fetched through the other form
                    $exclude_fields[] = $email_opt[0];
                }
                unset($email_opt);
            }
        }

        $where = '';
        if(!empty($exclude_fields))
            $where = " and it.field_id not in (".implode(',', $exclude_fields).")";
        $values = $frm_entry_meta->getAll("it.field_id != 0 and it.item_id in (". implode(',', $entry_ids).")". $where, " ORDER BY fi.field_order");
        
        $to_emails = array();
        if($to_email)
            $to_emails = explode(',', $to_email);
        
        $plain_text = (isset($form_options['plain_text']) and $form_options['plain_text']) ? true : false;
        $custom_message = false;
        $get_default = true;
        $mail_body = '';
        if(isset($form_options['email_message']) and trim($form_options['email_message']) != ''){
            if(!preg_match('/\[default-message\]/', $form_options['email_message']))
                $get_default = false;
            
            $custom_message = true;
            $shortcodes = FrmProAppHelper::get_shortcodes($form_options['email_message'], $entry->form_id);
            $mail_body  = FrmProFieldsHelper::replace_shortcodes($form_options['email_message'], $entry, $shortcodes);
        }

        if($get_default)
            $default = '';
            
        if($get_default and !$plain_text){
            $default .= "<table cellspacing='0' style='font-size:12px;line-height:135%; border-bottom:{$frmpro_settings->field_border_width} solid #{$frmpro_settings->border_color};'><tbody>\r\n";
            $bg_color = " style='background-color:#{$frmpro_settings->bg_color};'";
            $bg_color_alt = " style='background-color:#{$frmpro_settings->bg_color_active};'";
        }
        
        $reply_to_name = $frm_blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);  //default sender name
        $odd = true;
        $attachments = array();
        
        foreach ($values as $value){
            
            if($value->field_type == 'file'){
                global $frmdb;
                $file_options = $frmdb->get_var($frmdb->fields, array('id' => $value->field_id), 'field_options');
                $file_options = maybe_unserialize($file_options);
                if(isset($file_options['attach']) and $file_options['attach']){
                    $file = get_post_meta( $value->meta_value, '_wp_attached_file', true);
                	if($file){
                	    if(!isset($uploads) or !isset($uploads['basedir']))
                	        $uploads = wp_upload_dir();
                	    $attachments[] = $uploads['basedir'] . "/$file";
                	}
                }
                    
            }
            
            $val = apply_filters('frm_email_value', maybe_unserialize($value->meta_value), $value, $entry);
            
            if($value->field_type == 'textarea' and !$plain_text)
                $val = str_replace(array("\r\n", "\r", "\n"), ' <br/>', $val);
            
            
            if (is_array($val))
                $val = implode(', ', $val);
            
            if($get_default and $plain_text){
                $default .= $value->field_name . ': ' . $val . "\r\n\r\n";
            }else if($get_default){
                $row_style = "valign='top' style='text-align:left;color:#{$frmpro_settings->text_color};padding:7px 9px;border-top:{$frmpro_settings->field_border_width} solid #{$frmpro_settings->border_color}'";
                $default .= "<tr".(($odd)?$bg_color:$bg_color_alt)."><th $row_style>$value->field_name</th><td $row_style>$val</td></tr>\r\n";
                $odd = ($odd) ? false : true;
            }
                
            if(isset($form_options['reply_to']) and (int)$form_options['reply_to'] == $value->field_id){
                if($value->field_type == 'user_id'){
                    $user_data = get_userdata($value->meta_value);
                    $reply_to = $user_data->user_email;
                }else if(is_email($val))
                    $reply_to = $val;
            }
            
            if(isset($form_options['reply_to_name']) and (int)$form_options['reply_to_name'] == $value->field_id){
                if($value->field_type == 'user_id'){
                    $user_data = get_userdata($value->meta_value);
                    $reply_to_name = $user_data->display_name;
                }else
                    $reply_to_name = $val;
            }

            if(in_array($value->field_id, $email_fields)){
                if($value->field_type == 'user_id'){
                    $user_data = get_userdata($value->meta_value);
                    $to_emails[] = $user_data->user_email;
                }else{
                    $val = explode(',', $val);
                    if(is_array($val)){
                        foreach($val as $v){
                            $v = trim($v);
                            if(is_email($v))
                                $to_emails[] = $v;
                        }
                    }else if(is_email($val))
                        $to_emails[] = $val;
                }
            }
        }
        
        $attachments = apply_filters('frm_notification_attachment', $attachments, $form, array('entry' => $entry));
        
        
        if(!isset($reply_to)){
            $reply_to = '[admin_email]';
            //global $frm_settings;
            //$reply_to = $frm_settings->email_to;
        }
        
        if(isset($form_options['inc_user_info']) and $form_options['inc_user_info']){
            $data = maybe_unserialize($entry->description);
            if($plain_text or !$get_default){
                $mail_body .= "\r\n\r\n" . __('User Information', 'formidable') ."\r\n";
                $mail_body .= __('IP Address', 'formidable') . ": ". $entry->ip ."\r\n";
                $mail_body .= __('User-Agent (Browser/OS)', 'formidable') . ": ". $data['browser']."\r\n";
                $mail_body .= __('Referrer', 'formidable') . ": ". $data['referrer']."\r\n";
            }else{
                $default .= "<tr".(($odd)?$bg_color:$bg_color_alt)."><th $row_style>". __('IP Address', 'formidable') . "</th><td $row_style>". $entry->ip ."</td></tr>\r\n";
                $odd = ($odd) ? false : true;
                $default .= "<tr".(($odd)?$bg_color:$bg_color_alt)."><th $row_style>".__('User-Agent (Browser/OS)', 'formidable') . "</th><td $row_style>". $data['browser']."</td></tr>\r\n";
                $odd = ($odd) ? false : true;
                $default .= "<tr".(($odd)?$bg_color:$bg_color_alt)."><th $row_style>".__('Referrer', 'formidable') . "</th><td $row_style>". str_replace("\r\n", '<br/>', $data['referrer']) ."</td></tr>\r\n";
            }
        }

        if($get_default and !$plain_text)
            $default .= "</tbody></table>";
        
        if(isset($form_options['email_subject']) and $form_options['email_subject'] != ''){
            $shortcodes = FrmProAppHelper::get_shortcodes($form_options['email_subject'], $entry->form_id);
            $subject = FrmProFieldsHelper::replace_shortcodes($form_options['email_subject'], $entry, $shortcodes);
            $subject = apply_filters('frm_email_subject', $subject, compact('form', 'entry'));
        }else{
            //set default subject
            $subject = sprintf(__('%1$s Form submitted on %2$s', 'formidable'), stripslashes($form->name), $frm_blogname);
        }
        
        if($get_default and $custom_message)
            $mail_body = str_replace('[default-message]', $default, $mail_body);
        else if($get_default)
            $mail_body = $default;
        
        $to_emails = apply_filters('frm_to_email', $to_emails, $values, $form_id);
        foreach((array)$to_emails as $to_email){
            $to_email = apply_filters('frm_content', $to_email, $form, $entry_id);
            $frm_notification->send_notification_email(trim($to_email), $subject, $mail_body, $reply_to, $reply_to_name, $plain_text, $attachments);
        }

        return $to_emails;
    }
    
    function entry_updated($entry_id, $form_id){
        //send update email notification
        global $frm_form;
        $form = $frm_form->getOne($form_id);
        $form->options = maybe_unserialize($form->options);
        if(isset($form->options['update_email']) and $form->options['update_email'])
            $this->entry_created($entry_id, $form_id);
            
        if(isset($form->options['ar_update_email']) and $form->options['ar_update_email'])
            $this->autoresponder($entry_id, $form_id);
    }
    
    function autoresponder($entry_id, $form_id){
        if(defined('WP_IMPORTING'))
            return;
            
        global $frm_form, $frm_entry, $frm_entry_meta, $frm_notification;

        $form = $frm_form->getOne($form_id);
        $form_options = maybe_unserialize($form->options);

        if (!isset($form_options['auto_responder']) or !$form_options['auto_responder'] or !isset($form_options['ar_email_message']) or $form_options['ar_email_message'] == '') 
            return; //don't continue forward unless a message has been inserted
        
        $entry = $frm_entry->getOne($entry_id, true);
        $entry_ids = array($entry->id);
        
        $email_field = (isset($form_options['ar_email_to'])) ? $form_options['ar_email_to'] : 0;
        if(preg_match('/|/', $email_field)){
            $email_fields = explode('|', $email_field);
            if(isset($email_fields[1])){
                if(isset($entry->metas[$email_fields[0]])){
                    $add_id = $entry->metas[$email_fields[0]];
                
                    $add_id = maybe_unserialize($add_id);
                    if(is_array($add_id)){
                        foreach($add_id as $add)
                            $entry_ids[] = $add;
                    }else{
                        $entry_ids[] = $add_id;
                    }
                }
                
                $email_field = $email_fields[1];
            }
            unset($email_fields);
        }
        
        $inc_fields = array();
        foreach(array($email_field) as $inc_field){
            if($inc_field)
                $inc_fields[] = $inc_field;
        }
        
        $where = "it.item_id in (". implode(',', $entry_ids).")";
        if(!empty($inc_fields)){
            $inc_fields = implode(',', $inc_fields);
            $where .= " and it.field_id in ($inc_fields)";
        }
        
        $values = $frm_entry_meta->getAll($where, " ORDER BY fi.field_order");

        $plain_text = (isset($form_options['ar_plain_text']) and $form_options['ar_plain_text']) ? true : false;
        
        $message = apply_filters('frm_ar_message', $form_options['ar_email_message'], array('entry' => $entry, 'form' => $form));
        $shortcodes = FrmProAppHelper::get_shortcodes($message, $form_id);
        $mail_body  = FrmProFieldsHelper::replace_shortcodes($message, $entry, $shortcodes);
        
        $frm_blogname = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
        $reply_to_name = (isset($form_options['ar_reply_to_name'])) ? $form_options['ar_reply_to_name'] : $frm_blogname; //default sender name
        $reply_to = (isset($form_options['ar_reply_to'])) ? $form_options['ar_reply_to'] : '[admin_email]';  //default sender email

        foreach ($values as $value){
            /*
            if((int)$reply_field == $value->field_id){
                if($value->field_type == 'user_id'){
                    $user_data = get_userdata($value->meta_value);
                    $reply_to = $user_data->user_email;
                }else{
                    $val = apply_filters('frm_email_value', maybe_unserialize($value->meta_value), $value, $entry);
                    if(is_email($val))
                        $reply_to = $val;
                }
            }
            
            if((int)$reply_name_field == $value->field_id){
                if($value->field_type == 'user_id'){
                    $user_data = get_userdata($value->meta_value);
                    $reply_to_name = $user_data->display_name;
                }else
                    $reply_to_name = apply_filters('frm_email_value', maybe_unserialize($value->meta_value), $value, $entry);
            } */

            if((int)$email_field == $value->field_id){
                if($value->field_type == 'user_id'){
                    $user_data = get_userdata($value->meta_value);
                    $to_email = $user_data->user_email;
                }else{
                    $val = apply_filters('frm_email_value', maybe_unserialize($value->meta_value), $value, $entry);
                    if(is_email($val))
                        $to_email = $val;
                }
            }
        }
        
        if(!isset($to_email)) return;
        
        if(isset($form_options['ar_email_subject']) and $form_options['ar_email_subject'] != ''){
            $shortcodes = FrmProAppHelper::get_shortcodes($form_options['ar_email_subject'], $form_id);
            $subject = FrmProFieldsHelper::replace_shortcodes($form_options['ar_email_subject'], $entry, $shortcodes);
        }else{
            $subject = sprintf(__('%1$s Form submitted on %2$s', 'formidable'), stripslashes($form->name), $frm_blogname); //subject
        }
        
        $attachments = apply_filters('frm_autoresponder_attachment', array(), $form);
        
        $frm_notification->send_notification_email($to_email, $subject, $mail_body, $reply_to, $reply_to_name, $plain_text, $attachments);
        
        return $to_email;
    }

}
?>
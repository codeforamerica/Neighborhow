<?php
class FrmNotification{
    function FrmNotification(){
        add_action('frm_after_create_entry', array(&$this, 'entry_created'), 10, 2);
    }
    
    function entry_created($entry_id, $form_id){
        if (apply_filters('frm_stop_standard_email', false, $entry_id)) return;
        global $frm_form, $frm_entry, $frm_entry_meta;

        $frm_blogname = wp_specialchars_decode( get_option('blogname'), ENT_QUOTES );
        $entry = $frm_entry->getOne($entry_id);
        $form = $frm_form->getOne($form_id);
        $form->options = maybe_unserialize($form->options);
        $values = $frm_entry_meta->getAll("it.item_id = $entry_id", " ORDER BY fi.field_order");
        
        $to_email = $form->options['email_to'];
        if ($to_email == '')
            $to_email = get_option('admin_email');
            
        $to_emails = explode(',', $to_email);
        
        $reply_to = $reply_to_name = '';
            
        $opener = sprintf(__('%1$s form has been submitted on %2$s.', 'formidable'), $form->name, $frm_blogname) ."\r\n\r\n";
        
        $entry_data = '';
        foreach ($values as $value){
            $val = apply_filters('frm_email_value', maybe_unserialize($value->meta_value), $value, $entry);
            if (is_array($val))
                $val = implode(', ', $val);
            
            if($value->field_type == 'textarea'){
                //$val = str_replace(array("\r\n", "\r", "\n"), "\r\n", $val);
                $val = "\r\n". $val;
            }
            
            $entry_data .= $value->field_name . ': ' . $val . "\r\n\r\n";
            
            if(isset($form->options['reply_to']) and (int)$form->options['reply_to'] == $value->field_id and is_email($val))
                $reply_to = $val;
            
            if ($reply_to == '' and is_email($val))
                $reply_to = $val;
                
            if(isset($form->options['reply_to_name']) and (int)$form->options['reply_to_name'] == $value->field_id)
                $reply_to_name = $val;
        }
          
        $data = maybe_unserialize($entry->description);  
        $user_data = __('User Information', 'formidable') ."\r\n";
        $user_data .= __('IP Address', 'formidable') . ": ". $entry->ip ."\r\n";
        $user_data .= __('User-Agent (Browser/OS)', 'formidable') . ": ". $data['browser']."\r\n";
        $user_data .= __('Referrer', 'formidable') . ": ". $data['referrer']."\r\n";

        $mail_body = $opener . $entry_data ."\r\n". $user_data;
        $subject = sprintf(__('%1$s Form submitted on %2$s', 'formidable'), $form->name, $frm_blogname); //subject

        if(is_array($to_emails)){
            foreach($to_emails as $to_email)
                $this->send_notification_email(trim($to_email), $subject, $mail_body, $reply_to, $reply_to_name);
        }else
            $this->send_notification_email($to_email, $subject, $mail_body, $reply_to, $reply_to_name);
    }
  
    function send_notification_email($to_email, $subject, $message, $reply_to='', $reply_to_name='', $plain_text=true, $attachments=array()){
        $content_type   = ($plain_text) ? 'text/plain' : 'text/html';
        $reply_to_name  = ($reply_to_name == '') ? wp_specialchars_decode( get_option('blogname'), ENT_QUOTES ) : $reply_to_name; //senders name
        $reply_to       = ($reply_to == '' or $reply_to == '[admin_email]') ? get_option('admin_email') : $reply_to; //senders e-mail address
        
        if($to_email == '[admin_email]')
            $to_email = get_option('admin_email');
            
        $recipient      = $to_email; //recipient
        $header         = "From: \"{$reply_to_name}\" <{$reply_to}>\r\n Reply-To: \"{$reply_to_name}\" <{$reply_to}>\r\n Content-Type: {$content_type}; charset=\"" . get_option('blog_charset') . "\"\r\n"; //optional headerfields
        $subject        = wp_specialchars_decode(strip_tags(stripslashes($subject)), ENT_QUOTES );
        
        $message        = do_shortcode($message);
        $message        = wordwrap(stripslashes($message), 70, "\r\n"); //in case any lines are longer than 70 chars
        if($plain_text)
            $message    = wp_specialchars_decode(strip_tags($message), ENT_QUOTES );

        $header         = apply_filters('frm_email_header', $header, compact('to_email', 'subject'));
        
        remove_filter('wp_mail_from', 'bp_core_email_from_address_filter' );
        remove_filter('wp_mail_from_name', 'bp_core_email_from_name_filter');
          
        if (!wp_mail($recipient, $subject, $message, $header, $attachments)){
            $header = "From: \"{$reply_to_name}\" <{$reply_to}>\r\n";
            mail($recipient, $subject, $message, $header);
        }

        do_action('frm_notification', $recipient, $subject, $message);
    }

}
?>
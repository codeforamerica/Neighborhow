<?php

class FrmProFormsHelper{
    function FrmProFormsHelper(){
        add_filter('frm_setup_new_form_vars', array(&$this, 'setup_new_vars'));
        add_filter('frm_setup_edit_form_vars', array(&$this, 'setup_edit_vars'));
    }
    
    function setup_new_vars($values){
        
        foreach (FrmProFormsHelper::get_default_opts() as $var => $default)
            $values[$var] = FrmAppHelper::get_param($var, $default);
        return $values;
    }
    
    function setup_edit_vars($values){
        global $frm_form, $frmpro_settings;
        
        $record = $frm_form->getOne($values['id']);
        foreach (array('logged_in' => $record->logged_in, 'editable' => $record->editable) as $var => $default)
            $values[$var] = FrmAppHelper::get_param($var, $default);
        
        foreach (FrmProFormsHelper::get_default_opts() as $opt => $default){
            if (!isset($values[$opt]))
                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
        }
        $values['also_email_to'] = (array)$values['also_email_to'];
          
        return $values;
    }
    
    function get_default_opts(){
        global $frmpro_settings;
        
        return array(
            'edit_value' => $frmpro_settings->update_value, 'edit_msg' => $frmpro_settings->edit_msg, 
            'logged_in' => 0, 'logged_in_role' => '', 'editable' => 0, 
            'editable_role' => '', 'open_editable' => 0, 'open_editable_role' => '', 
            'copy' => 0, 'single_entry' => 0, 'single_entry_type' => 'user', 
            'success_page_id' => '', 'success_url' => '', 'ajax_submit' => 0, 
            'create_post' => 0, 'cookie_expiration' => 8000,
            'post_type' => 'post', 'post_category' => array(), 'post_content' => '', 
            'post_excerpt' => '', 'post_title' => '', 'post_name' => '', 'post_date' => '',
            'post_status' => '', 'post_custom_fields' => array(), 'post_password' => '',
            'plain_text' => 0, 'also_email_to' => array(), 'update_email' => 0,
            'email_subject' => '', 'email_message' => '[default-message]', 
            'inc_user_info' => 1, 'auto_responder' => 0, 'ar_plain_text' => 0, 
            'ar_email_to' => '', 'ar_reply_to' => get_option('admin_email'), 
            'ar_reply_to_name' => get_option('blogname'), 'ar_email_subject' => '', 
            'ar_email_message' => '', 'ar_update_email' => 0,
        );
    }
    
    function get_taxonomy_count($taxonomy, $post_categories, $tax_count=0){
        if(isset($post_categories[$taxonomy . $tax_count])){
            $tax_count++;
            $tax_count = FrmProFormsHelper::get_taxonomy_count($taxonomy, $post_categories, $tax_count);
        }
        return $tax_count;
    }
}

?>
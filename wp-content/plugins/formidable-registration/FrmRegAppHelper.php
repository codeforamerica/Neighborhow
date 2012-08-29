<?php
 
class FrmRegAppHelper{
    
    function get_default_options(){
        return array(
            'registration' => 0, 
            'login' => 0, 
            'reg_username' => '', 
            'reg_email' => '', 
            'reg_password' => '',
            'reg_last_name' => '',
            'reg_first_name' => '',
            'reg_display_name' => '',
            'reg_role' => 'subscriber',
            'reg_usermeta' => array(),
            'reg_delay_email' => 0,
            'reg_email_subject' => '[sitename] '. __('Your username and password', 'formidable'),
            'reg_email_msg' => (sprintf(__('Username: %s', 'formidable'), '[username]') . "\r\n" .
                sprintf(__('Password: %s', 'formidable'), '[password]') . "\r\n" . wp_login_url())
        );
    }
    
    function username_exists($username){
        $username = sanitize_user($username, true);
        
        if(!function_exists('username_exists'))
            require_once(ABSPATH . WPINC . '/registration.php');
        
        return username_exists( $username );
    }
    
    function generate_unique_username($username, $count=0){
        $count = (int)$count;
        $new_username = ($count > 0) ? $username . $count : $username;

        if (FrmRegAppHelper::username_exists($new_username))
            $new_username = FrmRegAppHelper::generate_unique_username($username, $count+1);
        
        return $new_username;
    }
    
    function get_user_meta($user_ID, $meta_key){
        if(function_exists('get_user_meta'))
            get_user_meta($user_ID, $meta_key, true);
        else
            get_usermeta($user_ID, $meta_key, true);
    }
}

?>
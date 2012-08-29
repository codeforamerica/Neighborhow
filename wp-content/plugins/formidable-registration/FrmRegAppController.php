<?php
 
class FrmRegAppController{
    function FrmRegAppController(){
        add_filter('frm_add_form_settings_section', array( &$this, 'add_registration_options'));
        add_action('wp_ajax_frm_add_usermeta_row', array(&$this, '_usermeta_row'));
        add_filter('frm_setup_new_form_vars', array(&$this, 'setup_new_vars'));
        add_filter('frm_setup_edit_form_vars', array(&$this, 'setup_edit_vars'));
        add_filter('frm_form_options_before_update', array(&$this, 'update_options'), 15, 2);
        
        add_action('frm_entry_form', array( &$this, 'hidden_form_fields' ));
        add_filter('frm_validate_field_entry', array( &$this, 'validate'), 20, 3);
        add_action('frm_after_create_entry', array( &$this, 'create_user'), 30, 2);
        
        add_action('frm_after_update_entry', array( &$this, 'update_user'), 25, 2);
        add_filter('frm_setup_edit_fields_vars', array(&$this, 'check_updated_user_meta'), 10, 3); 
        
        add_action('show_user_profile', array(&$this, 'show_usermeta'), 200);
        add_action('edit_user_profile', array(&$this, 'show_usermeta'), 200);
        
        add_shortcode('frm-login', array(&$this, 'login_form'));
        
        add_action('frm_standalone_route', array(&$this, 'standalone_route'), 10, 2);
    }
    
    function add_registration_options($sections){
        $sections['registration'] = array('class' => 'FrmRegAppController', 'function' => 'registration_options');
        return $sections;
    }
    
    function registration_options($values){
        global $frm_ajax_url;
        if(isset($values['id']))
            $fields = FrmField::getAll("fi.form_id='$values[id]' and fi.type not in ('divider', 'html', 'break', 'captcha', 'rte')", ' ORDER BY field_order');
        $echo = true;    
        include(FRM_REG_PATH .'/views/registration_options.php');
    }
    
    function _usermeta_row($meta_name=false, $field_id=''){
        if(!$meta_name and isset($_POST['meta_name']))
            $meta_name = $_POST['meta_name'];
        
        if(isset($_POST['form_id']))
            $fields = FrmField::getAll("fi.form_id='$_POST[form_id]' and fi.type not in ('divider', 'html', 'break', 'captcha')", ' ORDER BY field_order');
        $echo = false;   
        include(FRM_REG_PATH .'/views/_usermeta_row.php');
        die();
    }
    
    function setup_new_vars($values){
        $defaults = FrmRegAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            $values[$opt] = FrmAppHelper::get_param($opt, $default);
            unset($default);
            unset($opt);
        }
        return $values;
    }
    
    function setup_edit_vars($values){
        $defaults = FrmRegAppHelper::get_default_options();
        foreach ($defaults as $opt => $default){
            if (!isset($values[$opt]))
                $values[$opt] = ($_POST and isset($_POST['options'][$opt])) ? $_POST['options'][$opt] : $default;
            unset($default);
            unset($opt);
        }
        
        return $values;
    }
    
    function update_options($options, $values){
        $defaults = FrmRegAppHelper::get_default_options();
        unset($defaults['reg_usermeta']);
        
        foreach($defaults as $opt => $default){
            $options[$opt] = (isset($values['options'][$opt])) ? $values['options'][$opt] : $default;
            unset($default);
            unset($opt);
        }
        
        $options['reg_usermeta'] = array();
        if(isset($values['options']['reg_usermeta'])){
            foreach($values['options']['reg_usermeta']['meta_name'] as $meta_key => $meta_value){
                if(!empty($meta_value) and !empty($values['options']['reg_usermeta']['field_id'][$meta_key]))
                    $options['reg_usermeta'][$meta_value] = $values['options']['reg_usermeta']['field_id'][$meta_key];
            }
        }

        unset($defaults);
        
        //Make sure the form includes a User ID field for correct editing
        if ($options['registration']){
            global $frm_field;
            $form_id = $values['id'];
            $user_field = $frm_field->getAll(array('fi.form_id' => $form_id, 'type' => 'user_id'));
            if (!$user_field){
                $new_values = FrmFieldsHelper::setup_new_vars('user_id', $form_id);
                $new_values['name'] = __('User ID', 'formidable');
                $frm_field->create($new_values);
                unset($new_values);
            }
            unset($user_field);
        }
        
        return $options;
    }
    
    function hidden_form_fields($form){
        if(isset($form->options['registration']) and $form->options['registration']){
            if(isset($form->options['reg_username']) and $form->options['reg_username'])
                echo '<input type="hidden" name="frm_register[username]" value="'. $form->options['reg_username'] .'"/>'."\n";
            
            echo '<input type="hidden" name="frm_register[email]" value="'. $form->options['reg_email'] .'"/>'."\n";
        }
    }
    
    
    function validate($errors, $field, $value){
        if(isset($_POST) and isset($_POST['frm_register']) and !empty($value)){
            global $user_ID;
            if($user_ID) return $errors; //don't check if user is logged in because a new user won't be created anyway
            
            if(isset($_POST['frm_register']['username']) and (int)$field->id == $_POST['frm_register']['username']){
                
                //if there is a username field in the form
                $username = $_POST['item_meta'][$_POST['frm_register']['username']];  
                if(FrmRegAppHelper::username_exists($username))
                    $errors['field'.$field->id] = __('This username is already registered.');

            }
            
            if(isset($_POST['frm_register']['email']) and ((int)$field->id == $_POST['frm_register']['email'])){
                if(!function_exists('email_exists'))
                    require_once(ABSPATH . WPINC . '/registration.php');
                
                //check if email has already been used
                if(email_exists($value) )
            		$errors['field'.$field->id] = __('This email address is already registered.' );
            }
        }
        return $errors;
    }
    
    function create_user($entry_id, $form_id){ //TODO: add wp_noonce
        if (isset($_POST) and isset($_POST['frm_register'])){
            global $user_ID;
            if($user_ID){
                $required_role = apply_filters('frmreg_required_role', 'administrator');
                if(is_admin() or current_user_can($required_role)){
                    //don't require the user to edit their own record
                }else{
                    //if user is already logged-in, then update the user
                    $this->update_user($entry_id, $form_id);
                    return;
                }
                unset($required_role);
            } 
            
            $form = FrmForm::getOne($form_id);
            $form->options = maybe_unserialize($form->options);

            if(!isset($form->options['registration']) or 
                !$form->options['registration'] or 
                !isset($form->options['reg_email']) or 
                !isset($_POST['item_meta'][$form->options['reg_email']]))
                return;
  
            
            $user_meta = $this->_get_usermeta($form->options);

            if(!isset($user_meta['user_pass']) or empty($user_meta['user_pass']))
                $user_meta['user_pass'] = wp_generate_password();
            
            if(empty($form->options['reg_username'])){
                //if the username will be generated from the email
                $parts = explode("@", $user_meta['user_email']);
                $user_meta['user_login'] = $parts[0];
            }else if($form->options['reg_username'] == '-1'){    
                //if the username will be generated from the full email
                $user_meta['user_login'] = $user_meta['user_email'];
            }else{
                $user_meta['user_login'] = $_POST['item_meta'][$form->options['reg_username']];
            }
            $user_meta['user_login'] = FrmRegAppHelper::generate_unique_username($user_meta['user_login']);
            
            if(isset($form->options['reg_display_name']) and !empty($form->options['reg_display_name']))
                $user_meta['display_name'] = $this->_generate_display_name($form->options);

            $user_meta['role'] = (isset($form->options['reg_role'])) ? $form->options['reg_role'] : 'subscriber';
            
            if(!function_exists('username_exists'))
                require_once(ABSPATH . WPINC . '/registration.php');
            
            $user_id = wp_insert_user($user_meta);
            if(is_wp_error($user_id)){
                wp_die($user_id->get_error_message());
                return;
            }
            
            $user_id = (int)$user_id;
            if($user_id){
                global $frmdb, $wpdb, $frm_entry_meta;
                // set user id field
                $wpdb->update( $frmdb->entries, array('user_id' => $user_id, 'updated_by' => $user_id), array('id' => $entry_id) );
                wp_cache_delete($entry_id, 'frm_entry');
                
                $_POST['frm_user_id'] = $user_id;
                $user_field = $frmdb->get_var($frmdb->fields, array('type' => 'user_id', 'form_id' => $form_id));
                $frm_entry_meta->delete_entry_meta($entry_id, $user_field);
                $frm_entry_meta->add_entry_meta($entry_id, $user_field, '', $user_id);
                
                //remove password from database
                if(isset($form->options['reg_password']) and !empty($form->options['reg_password']))
                    $frm_entry_meta->delete_entry_meta($entry_id, (int)$form->options['reg_password']);
                    
                //Update usermeta
                $this->update_usermeta($form->options, $user_id);
                
                // send new user notifications
                wp_new_user_notification($user_id, ''); // sending a blank password only sends notification to admin
                $this->new_user_notification($user_id, $user_meta['user_pass'], $form, $entry_id);
                
                //log user in
                if(!is_user_logged_in() and (!isset($form->options['login']) or $form->options['login']))
                    $this->auto_login($user_meta['user_login'], $user_meta['user_pass']);
            }
            
        }
    }
    
    function new_user_notification( $user_id, $plaintext_pass, $form, $entry_id ){			
		$user = new WP_User( $user_id );

		$user_login = stripslashes( $user->user_login );
		$user_email = stripslashes( $user->user_email );
        $form->options = maybe_unserialize($form->options);

        //Add a filter so the email notification can be stopped
		if( apply_filters( 'frm_send_new_user_notification', true, $form, $entry_id ) ){ 
		    if( function_exists( 'is_multisite' ) && is_multisite() ){
    			$blogname = $GLOBALS['current_site']->site_name;
    		}else{
    			// The blogname option is escaped with esc_html on the way into the database in sanitize_option
    			// we want to reverse this for the plain text arena of emails.
    			$blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    		}
		    
		    if(!isset($form->options['reg_email_msg']) or empty($form->options['reg_email_msg'])){
		        $message  = sprintf(__('Username: %s', 'formidable'), $user_login) . "\r\n";
        	    $message .= sprintf(__('Password: %s', 'formidable'), $plaintext_pass) . "\r\n";
        	    $message .= wp_login_url() . "\r\n";
    	    }else{
    	        $message = str_replace('[password]', $plaintext_pass, $form->options['reg_email_msg'] );
    	        $message = str_replace('[username]', $user_login, $message );
    	        $message = str_replace('[sitename]', $blogname, $message );
    	        $message = apply_filters('frm_content', $message, $form, $entry_id);
    	    }
            $message = apply_filters( 'frm_new_user_notification_message', $message, $plaintext_pass, $user_id );
            
            if(!isset($form->options['reg_email_subject']) or empty($form->options['reg_email_subject'])){
        	    $title = sprintf( __( '[%s] Your username and password', 'formidable' ), $blogname);
        	}else{
        	    $title = str_replace('[sitename]', $blogname, $form->options['reg_email_subject'] );
        	    $title = apply_filters('frm_content', $title, $form, $entry_id);
        	}
			$title = apply_filters( 'frm_new_user_notification_title', $title, $user_id );

			wp_mail( $user_email, $title, $message );
		}
	}
    
    function auto_login($log, $pwd, $id=false){
        global $frm_version;
        
        if(version_compare( $frm_version, '1.06.03', '>')){
            $_POST['log'] = $log;
            $_POST['pwd'] = $pwd;
            if($id)
                $_POST['frm_id'] = $id;
                
            $this->signon();
            return;
        }
        
        if(isset($_POST['frm_skip_cookie']))
            return;
?>
<script type="text/javascript">
jQuery(document).ready(function($){
jQuery.ajax({type:"POST",url:"<?php echo FRM_SCRIPT_URL; ?>",
data:"controller=registration&action=signon&log=<?php echo $log ?>&pwd=<?php echo $pwd ?><?php if($id) echo '&frm_id='. $id ?>"
});
});    
</script>
<?php
    }
    
    function signon(){ 
        if(isset($_POST['frm_id'])){
            wp_clear_auth_cookie();
			wp_set_auth_cookie($_POST['frm_id']);
        }else{
            $log = wp_signon();  
        }                
        //wp_set_current_user($user_id, $user_meta['user_login']);
    }
    
    function update_user($entry_id, $form_id){
        if(!(int)$form_id) return;
        
        $form = FrmForm::getOne($form_id);
        $form->options = maybe_unserialize($form->options);
        
        if(!isset($form->options['registration']) or !$form->options['registration'])
            return;
            
        $user_meta = array();
        $user_meta = $this->_get_usermeta($form->options);
        
        global $user_ID;
        $posted_id = isset($_POST['frm_user_id']) ? $_POST['frm_user_id'] : $user_ID;
        if($posted_id){
            $required_role = apply_filters('frmreg_required_role', 'administrator');
            if($user_ID and !is_admin() and !current_user_can($required_role) and ($posted_id != $user_ID)) 
                return; //make sure this record is updated by the owner or from the admin
            $user_meta['ID'] = $posted_id;
            unset($required_role);
        }
        
        if(empty($form->options['reg_username'])){
            $user_data = get_userdata($posted_id);
            $user_meta['user_login'] = $user_data->user_login;
        }else if($form->options['reg_username'] == '-1' and isset($user_meta['user_email'])){
            $user_meta['user_login'] = $user_meta['user_email'];
        }else{
            $user_meta['user_login'] = $_POST['item_meta'][$form->options['reg_username']];
        }
        
        if(isset($form->options['reg_display_name']) and !empty($form->options['reg_display_name']))
            $user_meta['display_name'] = $this->_generate_display_name($form->options);
        
        if(isset($user_meta['ID']) and isset($user_meta['user_pass']))
            $user_meta['user_pass'] = wp_hash_password($user_meta['user_pass']);
           
        if(!function_exists('username_exists'))
            require_once(ABSPATH . WPINC . '/registration.php');
            
        $user_id = wp_insert_user($user_meta);
        
        if($user_id and is_numeric($user_id)){
            $this->update_usermeta($form->options, $user_id);
            
            //remove password from database
            if(isset($user_meta['user_pass']) and isset($form->options['reg_password']) and !empty($form->options['reg_password'])){
                global $frm_entry_meta;
                $frm_entry_meta->delete_entry_meta($entry_id, (int)$form->options['reg_password']);
                
                $current_user = wp_get_current_user();
                if(isset($user_meta['user_pass']) and $current_user->ID == $user_id){
                    if(!isset($user_meta['user_login']))
                        $user_meta['user_login'] = $current_user->user_login;
                    $this->auto_login($user_meta['user_login'], $user_meta['user_pass'], $current_user->ID);
                }
            }
        }
    }
    
    function check_updated_user_meta($values, $field, $entry_id=false){
        global $frm_form, $frm_entry, $user_ID;
        
        if(in_array($field->type, array('data', 'checkbox')))
            return $values;
            
        $form = $frm_form->getOne($field->form_id);
        $form->options = maybe_unserialize($form->options);

        if(!isset($form->options['reg_usermeta']) or empty($form->options['reg_usermeta']))
            return $values;
            
        $form->options['reg_usermeta']['username'] = $form->options['reg_username'];
        $form->options['reg_usermeta']['user_email'] = $form->options['reg_email'];
        if($form->options['reg_username'] == '-1')
            $form->options['reg_username'] = $form->options['reg_email'];
            
        $form->options['reg_usermeta']['first_name'] = $form->options['reg_first_name'];
        $form->options['reg_usermeta']['last_name'] = $form->options['reg_last_name'];
        
        if(isset($form->options['reg_display_name']) and is_numeric($form->options['reg_display_name']))
            $form->options['reg_usermeta']['display_name'] = $form->options['reg_display_name'];
        
        $user_meta = array_search($field->id, $form->options['reg_usermeta']);
        if($user_meta){
            $entry = $frm_entry->getOne($entry_id);
            if(!$entry or !$entry->user_id)
                return $values;
                
            $user_data = get_userdata($entry->user_id);
            if(isset($user_data->{$user_meta}))
                $values['value'] = $user_data->{$user_meta};
            else
                $values['value'] = FrmRegAppHelper::get_user_meta($user_ID, $user_meta);
        }
        
        return $values;
    }
    
    function show_usermeta(){
        global $profileuser, $frmdb;

        $meta_keys = array();
        $form_options = $frmdb->get_col($frmdb->forms, array(), 'options');
        foreach($form_options as $opts){
            $opts = maybe_unserialize($opts);
            if(!isset($opts['reg_usermeta']) or empty($opts['reg_usermeta']))
                continue;
            
            foreach($opts['reg_usermeta'] as $meta_key => $field_id){
                if($meta_key != 'user_url')
                    $meta_keys[$field_id] = $meta_key;
            }
        }
        
        if(!empty($meta_keys))
            include(FRM_REG_PATH .'/views/show_usermeta.php');
    }
    
    function update_usermeta($form_options, $user_ID){
        if(isset($form_options['reg_usermeta']) and !empty($form_options['reg_usermeta'])){
            foreach($form_options['reg_usermeta'] as $meta_key => $field_id){
                $meta_val = (isset($_POST['item_meta'][$field_id])) ? $_POST['item_meta'][$field_id] : '';
                if(function_exists('update_user_meta'))
                    update_user_meta($user_ID, $meta_key, $meta_val);
                else
                    update_usermeta($user_ID, $meta_key, $meta_val);
            }
        }
    }
    
    function _get_usermeta($form_options){
        $user_meta = array();
        
        if(isset($form_options['reg_email']) and isset($_POST['item_meta'][$form_options['reg_email']]))
            $user_meta['user_email'] = sanitize_text_field( $_POST['item_meta'][$form_options['reg_email']] );

        if(is_numeric($form_options['reg_password']) and !empty($_POST['item_meta'][$form_options['reg_password']]))
            $user_meta['user_pass'] = $_POST['item_meta'][$form_options['reg_password']];
           
        foreach(array('first_name', 'last_name') as $user_field){
            if(is_numeric($form_options['reg_'. $user_field]) and !empty($_POST['item_meta'][$form_options['reg_'. $user_field]]))
                $user_meta[$user_field] = $_POST['item_meta'][$form_options['reg_'. $user_field]];
        }
        
        /*
        * 'user_url' - A string containing the user's URL for the user's web site.
        * 'display_name' - A string that will be shown on the site.
        * 'description' - A string containing content about the user.
        */
        
        return $user_meta;
    }
    
    private function _generate_display_name($opts){
        if(isset($opts['reg_display_name']) and !empty($opts['reg_display_name'])){
            if(is_numeric($opts['reg_display_name']))
                $display_name = $_POST['item_meta'][$opts['reg_display_name']];
            else if($opts['reg_display_name'] == 'display_firstlast' and is_numeric($opts['reg_first_name']) and is_numeric($opts['reg_last_name']))
                $display_name = $_POST['item_meta'][$opts['reg_first_name']] . ' '. is_numeric($opts['reg_last_name']);
            else if($opts['reg_display_name'] == 'display_lastfirst' and is_numeric($opts['reg_first_name']) and is_numeric($opts['reg_last_name']))
                $display_name = $_POST['item_meta'][$opts['reg_last_name']] . ' '. is_numeric($opts['reg_first_name']);
        }
        
        return $display_name;
    }
    
    function login_form($atts){
        if(is_user_logged_in()) //don't show the login form if user is already logged in
            return '<a href="'. wp_logout_url( get_permalink() ) .'" class="frm_logout_link" >Logout</a>';
            
        $defaults = array(
            'form_id' => 'loginform', 'label_username' => __( 'Username' ),
            'label_password' => __( 'Password' ), 'label_remember' => __( 'Remember Me' ),
            'label_log_in' => __( 'Login' ), 'id_username' => 'user_login',
            'id_password' => 'user_pass', 'id_remember' => 'rememberme',
            'id_submit' => 'wp-submit', 'remember' => true,
            'value_username' => NULL, 'value_remember' => false,
            'slide' => false, 'style' => true, 'layout' => 'v'
        );
        
        if(isset($atts['slide']) and $atts['slide']){
            $defaults['form_id'] = 'frm-loginform';
            $defaults['label_username'] = $defaults['label_password'] = '';
            $defaults['remember'] = false;
            $defaults['layout'] = 'h';
        }
        
        $atts = shortcode_atts($defaults, $atts);
        
        global $frm_reg_login_ids;
        if(!$frm_reg_login_ids)
            $frm_reg_login_ids = array();
        
        if(in_array($atts['form_id'], $frm_reg_login_ids))
            $atts['form_id'] .= '1';
        $frm_reg_login_ids[] = $atts['form_id'];
        
        $content = '';

        if($atts['slide'] and $atts['style']){
            $content .= '<style type="text/css">';
            if($atts['layout'] == 'h'){
                $content .= '#'. $atts['form_id'].' p{float:left;margin:1px 1px 0;padding:0;}.frm-open-login{float:left;margin-right:15px;}#'. $atts['form_id'].' input[type="text"], #'. $atts['form_id'].' input[type="password"]{width:120px;}';
            }else{
                $content .= '#'. $atts['form_id'].' input[type="text"], #'. $atts['form_id'].' input[type="password"]{width:auto;}';
            }
            $content .= '#'. $atts['form_id'].'{display:none;}#'. $atts['form_id'].' input{padding:1px 5px 2px;vertical-align:top;font-size:13px;} .frm-open-login a{text-decoration:none;font-size:12px;}
</style>'."\n";
        }else if($atts['style']){
            $content .= '<style type="text/css">#'. $atts['form_id'].' input[type="text"], #'. $atts['form_id'].' input[type="password"]';
            if($atts['layout'] == 'h'){
                $content .= '{width:120px;}#'. $atts['form_id'].' p{float:left;margin:1px 1px 0;padding:0;}';
            }else{
                $content .= '{width:auto;}';
            }
            $content .= '</style>'."\n";
        }
        
        if($atts['slide'])
            $content .= '<span class="frm-open-login"><a href="#">'. $atts['label_log_in'] .' &rarr;</a></span>';
            
        $atts['echo'] = false;
        if($atts['style'])
            $content .= '<div class="with_frm_style"><div class="frm_form_fields submit">'."\n";
        
        $content .= wp_login_form( $atts );
        
        if($atts['style'])
            $content .= '</div></div>';
        
        if($atts['slide']){
            $content .= '<div style="clear:both"></div>'."\n";
            $content .= '<script type="text/javascript">
            jQuery(document).ready(function($){ $(".frm-open-login a").click(function(){$("#'. $atts['form_id'] .'").toggle(600);return false;});});
            </script>'."\n";
        }
        return $content;
    }
    
    // Routes for standalone / ajax requests
    function standalone_route($controller, $action){
        if($controller != 'registration')
            return;
            
        if($action == 'signon')
            return $this->signon();
    }

}

?>
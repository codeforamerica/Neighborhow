<?php
class FrmSettings{
    // Page Setup Variables
    var $menu;
    var $mu_menu;
    var $preview_page_id;
    var $preview_page_id_str;
    var $lock_keys;
    var $track;
    
    var $pubkey;
    var $privkey;
    var $re_theme;
    var $re_lang;
    var $re_msg;
    
    var $use_html;
    var $custom_style;
    var $load_style;
    var $custom_stylesheet;
    var $jquery_css;
    var $accordion_js;
    
    var $success_msg;
    var $failed_msg;
    var $invalid_msg;
    var $submit_value;
    var $login_msg;
    var $admin_permission;
    var $email_to;
    
    var $frm_view_forms;
    var $frm_edit_forms;
    var $frm_delete_forms;
    var $frm_change_settings;
    var $frm_view_entries;
    var $frm_create_entries;
    var $frm_edit_entries;
    var $frm_delete_entries;
    var $frm_view_reports;
    var $frm_edit_displays;


    function FrmSettings(){
        $this->set_default_options();
    }

    function set_default_options(){
        if(!isset($this->menu))
            $this->menu = 'Formidable';
        
        if(!isset($this->mu_menu))
            $this->mu_menu = 0;
        
        if(IS_WPMU and is_admin()){
            $mu_menu = get_site_option('frm_admin_menu_name');
            if($mu_menu and !empty($mu_menu)){
                $this->menu = $mu_menu;
                $this->mu_menu = 1;
            }
        }
          
        if(!isset($this->preview_page_id))
          $this->preview_page_id = 0;
          
        $this->preview_page_id_str = 'frm-preview-page-id';
        
        if(!isset($this->lock_keys))
            $this->lock_keys = false;
        
        if(!isset($this->track))
            $this->track = false;
          
        if(!isset($this->pubkey)){
            if(IS_WPMU)
               $recaptcha_opt = get_site_option('recaptcha'); // get the options from the database
            else
               $recaptcha_opt = get_option('recaptcha');

            $this->pubkey = (isset($recaptcha_opt['pubkey'])) ? $recaptcha_opt['pubkey'] : ''; 
        } 
        
        if(!isset($this->privkey))
            $this->privkey = (isset($recaptcha_opt) and isset($recaptcha_opt['privkey'])) ? $recaptcha_opt['privkey'] : '';        

        if(!isset($this->re_theme))
            $this->re_theme = (isset($recaptcha_opt) and isset($recaptcha_opt['re_theme'])) ? $recaptcha_opt['re_theme'] : 'red';
            
        if(!isset($this->re_lang))
            $this->re_lang = (isset($recaptcha_opt) and isset($recaptcha_opt['re_lang'])) ? $recaptcha_opt['re_lang'] : 'en';
         
        if(!isset($this->re_msg) or empty($this->re_msg))
            $this->re_msg = __('The reCAPTCHA was not entered correctly', 'formidable');

        
        if(!isset($this->use_html))
            $this->use_html = true;
            
        if(!isset($this->load_style)){
            if(!isset($this->custom_style))
                $this->custom_style = true;
            if(!isset($this->custom_stylesheet))
                $this->custom_stylesheet = false;
                
            $this->load_style = ($this->custom_stylesheet) ? 'none' : 'all';
        }
            
        if(!isset($this->jquery_css))
            $this->jquery_css = false;
        if(!isset($this->accordion_js))
            $this->accordion_js = false;
            
        if(!isset($this->success_msg))
            $this->success_msg = __('Your responses were successfully submitted. Thank you!', 'formidable');
        $this->success_msg = stripslashes($this->success_msg);
        
        if(!isset($this->invalid_msg))
            $this->invalid_msg = __('There was a problem with your submission. Errors are marked below.', 'formidable');
        $this->invalid_msg = stripslashes($this->invalid_msg);
        
        if(!isset($this->failed_msg))
            $this->failed_msg = __('We\'re sorry. It looks like you\'ve  already submitted that.', 'formidable');
        $this->failed_msg = stripslashes($this->failed_msg);
        
        if(!isset($this->submit_value))
            $this->submit_value = __('Submit', 'formidable');
        
        if(!isset($this->login_msg))    
            $this->login_msg = __('You do not have permission to view this form.', 'formidable');
        $this->login_msg = stripslashes($this->login_msg);
        
        if(!isset($this->admin_permission))
            $this->admin_permission = __("You do not have permission to do that", 'formidable');
        $this->admin_permission = stripslashes($this->admin_permission);
        
        if(!isset($this->email_to))
            $this->email_to = '[admin_email]';
        
        $frm_roles = FrmAppHelper::frm_capabilities();
        foreach($frm_roles as $frm_role => $frm_role_description){
            if(!isset($this->$frm_role))
                $this->$frm_role = 'administrator';
        }
    }

    function validate($params,$errors){   
        //if($params[ $this->preview_page_id_str ] == 0)
        //  $errors[] = "The Preview Page Must Not Be Blank.";
        $errors = apply_filters( 'frm_validate_settings', $errors, $params );
        return $errors;
    }

    function update($params){
        global $wp_roles;
        $this->menu = $params['frm_menu'];
        $this->mu_menu = isset($params['frm_mu_menu']) ? $params['frm_mu_menu'] : 0;
        if($this->mu_menu)
            update_site_option('frm_admin_menu_name', $this->menu);
        else if(FrmAppHelper::is_super_admin())
            update_site_option('frm_admin_menu_name', false);
        
        $this->preview_page_id = (int)$params[ $this->preview_page_id_str ];
        $this->lock_keys = isset($params['frm_lock_keys']) ? $params['frm_lock_keys'] : 0;
        $this->track = isset($params['frm_track']) ? $params['frm_track'] : 0;
        
        $this->pubkey = $params['frm_pubkey'];
        $this->privkey = $params['frm_privkey'];
        $this->re_theme = $params['frm_re_theme'];
        $this->re_lang = $params['frm_re_lang'];
        
        $this->use_html = isset($params['frm_use_html']) ? $params['frm_use_html'] : 0;
        $this->load_style = $params['frm_load_style'];
        //$this->custom_style = isset($params['frm_custom_style']) ? $params['frm_custom_style'] : 0;
        //$this->custom_stylesheet = isset($params['frm_custom_stylesheet']) ? $params['frm_custom_stylesheet'] : 0;
        $this->jquery_css = isset($params['frm_jquery_css']) ? $params['frm_jquery_css'] : 0;
        $this->accordion_js = isset($params['frm_accordion_js']) ? $params['frm_accordion_js'] : 0;
        
        $this->success_msg = $params['frm_success_msg'];
        $this->invalid_msg = $params['frm_invalid_msg'];
        $this->failed_msg = $params['frm_failed_msg'];
        $this->submit_value = $params['frm_submit_value'];
        $this->login_msg = $params['frm_login_msg'];
        
        //update roles
        $frm_roles = FrmAppHelper::frm_capabilities();
        $roles = get_editable_roles();
        foreach($frm_roles as $frm_role => $frm_role_description){
            $this->$frm_role = isset($params[$frm_role]) ? $params[$frm_role] : 'administrator';
            
            foreach ($roles as $role => $details){
                if($this->$frm_role == $role or ($this->$frm_role == 'editor' and $role == 'administrator') or ($this->$frm_role == 'author' and in_array($role, array('administrator', 'editor'))) or ($this->$frm_role == 'contributor' and in_array($role, array('administrator', 'editor', 'author'))) or $this->$frm_role == 'subscriber')
    			    $wp_roles->add_cap( $role, $frm_role );	
    			else
    			    $wp_roles->remove_cap( $role, $frm_role );
    		}	
		}
        
        do_action( 'frm_update_settings', $params );
    }

    function store(){
        // Save the posted value in the database

        update_option('frm_options', $this);
        
        delete_transient('frm_options');
        set_transient('frm_options', $this);

        do_action( 'frm_store_settings' );
    }
  
}
?>
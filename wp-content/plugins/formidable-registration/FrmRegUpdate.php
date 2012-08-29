<?php

class FrmRegUpdate{
    var $plugin_nicename;
    var $plugin_name;
    var $plugin_slug;
    var $plugin_url;
    var $pro_mothership;
    var $pro_last_checked_store;
    var $pro_mothership_xmlrpc_url;

    function FrmRegUpdate(){
        global $frm_update;
        // Where all the vitals are defined for this plugin
        $this->plugin_nicename      = 'formidable-registration';
        $this->plugin_name          = 'formidable-registration/formidable-registration.php';
        $this->plugin_slug          = 'formidable-registration';
        $this->plugin_url           = 'http://formidablepro.com/knowledgebase/formidable-registration/';
        $this->pro_mothership       = 'http://formidablepro.com';

        $this->pro_last_checked_store = 'frm_reg_last_checked_update';

        // Don't modify these variables
        $this->pro_mothership_xmlrpc_url = $this->pro_mothership . '/xmlrpc.php';

        if($frm_update->pro_username and $frm_update->pro_password){
            // Plugin Update Actions -- gotta make sure the right url is used with pro ... don't want any downgrades of course
            add_action('update_option_update_plugins', array(&$this, 'check_for_update_now')); // for WordPress 2.7
            add_action('update_option__transient_update_plugins', array(&$this, 'check_for_update_now')); // for WordPress 2.8
            add_action('admin_init', array(&$this, 'periodically_check_for_update'));
        }
    }

    function authorize_user($username, $password){
        global $frm_update;
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client($this->pro_mothership_xmlrpc_url, false, 80, $frm_update->timeout );

        if ( !$client->query( 'proplug.is_user_authorized', $username, $password ) )
          return false;

        return $client->getResponse();
    }

    function user_allowed_to_download(){
        global $frm_update;
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client( $this->pro_mothership_xmlrpc_url, false, 80, $frm_update->timeout );

        if ( !$client->query( 'proplug.is_user_allowed_to_download', $frm_update->pro_username, $frm_update->pro_password, get_option('siteurl'), $this->plugin_nicename ) )
          return false;

        return $client->getResponse();
    }


    function get_download_url($version){
        global $frm_update;
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client( $this->pro_mothership_xmlrpc_url, false, 80, $frm_update->timeout );

        if( !$client->query( 'proplug.get_encoded_download_url', $frm_update->pro_username, $frm_update->pro_password, $version, get_option('siteurl'), $this->plugin_nicename ) )
            return false;

        return base64_decode($client->getResponse());
    }

    function get_current_version(){
        global $frm_update;
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client( $this->pro_mothership_xmlrpc_url, false, 80, $frm_update->timeout );

        if( !$client->query( 'proplug.get_current_version', $this->plugin_nicename ) )
          return false;

        return $client->getResponse();
    }

    function queue_update($force=false){
        static $already_set_option, $already_set_transient;

        // Make sure this method doesn't check back with the mothership too often
        if(!is_admin() or $already_set_option or $already_set_transient)
            return;
        
        global $frm_update;
        if($frm_update->pro_is_authorized()){
            $plugin_updates = (function_exists('get_site_transient')) ? get_site_transient('update_plugins') : get_transient('update_plugins'); 
            if(!$plugin_updates and function_exists('get_transient'))
                $plugin_updates = get_transient('update_plugins');

            $curr_version = $this->get_current_version();
            if(!$curr_version)
                return;
                
            $installed_version = $plugin_updates->checked[$this->plugin_name];

            if( $force or version_compare( $curr_version, $installed_version, '>')){
                $download_url = $this->get_download_url($curr_version);

                if(!empty($download_url) and $download_url and $this->user_allowed_to_download()){  
                    if(isset($plugin_updates->response[$this->plugin_name]))
                        unset($plugin_updates->response[$this->plugin_name]);

                    $plugin_updates->response[$this->plugin_name]              = new stdClass();
                    $plugin_updates->response[$this->plugin_name]->id          = '0';
                    $plugin_updates->response[$this->plugin_name]->slug        = $this->plugin_slug;
                    $plugin_updates->response[$this->plugin_name]->new_version = $curr_version;
                    $plugin_updates->response[$this->plugin_name]->url         = $this->plugin_url;
                    $plugin_updates->response[$this->plugin_name]->package     = $download_url;
                }
            }else{
                if(isset($plugin_updates->response[$this->plugin_name]))
                    unset($plugin_updates->response[$this->plugin_name]);
            }

            if ( function_exists('set_site_transient') and !$already_set_transient ){
                $already_set_transient = true;
                set_site_transient("update_plugins", $plugin_updates); // for WordPress 2.9+
            }

            if( function_exists('set_transient') and !$already_set_option ){
                $already_set_option = true;
                set_transient("update_plugins", $plugin_updates); // for WordPress 2.8
            }
        }
    }

    function check_for_update_now(){
        $this->queue_update();
        update_option($this->pro_last_checked_store, time());
    }

    function periodically_check_for_update(){
        global $frm_update;
        $last_checked = get_option($this->pro_last_checked_store);

        if(!$last_checked or ((time() - $last_checked) >= $frm_update->pro_check_interval)){
          $this->queue_update();
          update_option($this->pro_last_checked_store, time());
        }
    }
}
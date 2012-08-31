<?php

/** Okay, this class is not a pure model -- it contains all the functions
  * necessary to successfully provide an update mechanism for FormidablePro!
  */
class FrmUpdate{
    var $plugin_nicename;
    var $plugin_name;
    var $plugin_slug;
    var $plugin_url;
    var $pro_script;
    var $pro_mothership;

    var $pro_cred_store;
    var $pro_auth_store;
    var $pro_wpmu_store;

    var $pro_username_label;
    var $pro_password_label;

    var $pro_username_str;
    var $pro_password_str;
    var $pro_wpmu_str;

    var $pro_error_message_str;

    var $pro_check_interval;
    var $pro_last_checked_store;

    var $pro_username;
    var $pro_password;
    var $pro_mothership_xmlrpc_url;
    var $timeout;

    function FrmUpdate(){
        // Where all the vitals are defined for this plugin
        $this->plugin_nicename      = 'formidable';
        $this->plugin_name          = 'formidable/formidable.php';
        $this->plugin_slug          = 'formidable';
        $this->plugin_url           = 'http://formidablepro.com/formidable-wordpress-plugin';
        $this->pro_script           = FRM_PATH . '/pro/formidable-pro.php';
        $this->pro_mothership       = 'http://formidablepro.com';
        $this->pro_cred_store       = 'frmpro-credentials';
        $this->pro_auth_store       = 'frmpro-authorized';
        $this->pro_wpmu_store       = 'frmpro-wpmu-sitewide';
        $this->pro_last_checked_store = 'frmpro_last_checked_update';
        $this->pro_username_label    = __('Formidable Pro Username', 'formidable');
        $this->pro_password_label    = __('Formidable Pro Password', 'formidable');
        $this->pro_error_message_str = __('Your Formidable Pro Username or Password was Invalid', 'formidable');

        // Don't modify these variables
        $this->pro_check_interval = 60*60*12; // Checking every 12 hours
        $this->pro_username_str = 'proplug-username';
        $this->pro_password_str = 'proplug-password';
        $this->pro_wpmu_str = 'proplug-wpmu';
        $this->pro_mothership_xmlrpc_url = $this->pro_mothership . '/xmlrpc.php';
        $this->timeout = 10;

        // Retrieve Pro Credentials
        $this->pro_wpmu = false;
        if (IS_WPMU and get_site_option($this->pro_wpmu_store)){
            $creds = get_site_option($this->pro_cred_store);
            $this->pro_wpmu = true;
        }else
            $creds = get_option($this->pro_cred_store);

        if($creds and is_array($creds)){
          extract($creds);
          $this->pro_username = ((isset($username) and !empty($username))?$username:'');
          $this->pro_password = ((isset($password) and !empty($password))?$password:'');

          // Plugin Update Actions -- gotta make sure the right url is used with pro ... don't want any downgrades of course
          add_action('update_option__transient_update_plugins', array(&$this, 'check_for_update_now')); // for WordPress 2.8
          add_action('admin_init', array(&$this, 'periodically_check_for_update'));
          add_filter('pre_set_transient_update_plugins', array(&$this, 'force_pro_version') );
        }
    }

    function pro_is_installed(){
        return file_exists($this->pro_script);
    }

    function pro_is_authorized($force_check=false){
        if( !empty($this->pro_username) and !empty($this->pro_password) ){
            if (IS_WPMU and $this->pro_wpmu)
                $authorized = get_site_option($this->pro_auth_store);
            else
                $authorized = get_option($this->pro_auth_store);
            
            if(!$force_check and isset($authorized)){
                return $authorized;
            }else{
                $new_auth = $this->authorize_user($this->pro_username,$this->pro_password);
                if (IS_WPMU and $this->pro_wpmu)
                    update_site_option($this->pro_auth_store, $new_auth);
                else
                    update_option($this->pro_auth_store, $new_auth);
                return $new_auth;
            }
        }

        return false;
    }

    function pro_is_installed_and_authorized(){
        return ($this->pro_is_installed() and $this->pro_is_authorized());
    }

    function authorize_user($username, $password){
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client($this->pro_mothership_xmlrpc_url, false, 80, $this->timeout );

        if ( !$client->query( 'proplug.is_user_authorized', $username, $password ) )
          return false;

        return $client->getResponse();
    }

    function user_allowed_to_download(){
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client( $this->pro_mothership_xmlrpc_url, false, 80, $this->timeout );

        if ( !$client->query( 'proplug.is_user_allowed_to_download', $this->pro_username, $this->pro_password, get_option('siteurl'), $this->plugin_nicename ) )
          return false;

        return $client->getResponse();
    }

    function pro_cred_form(){ 
        global $frmpro_is_installed, $frm_ajax_url; 
        if(isset($_POST) and isset($_POST['process_cred_form']) and $_POST['process_cred_form'] == 'Y'){
            if($this->process_pro_cred_form()){ ?>
<div id="message" class="updated fade"><strong>
<?php
            if(!$this->pro_is_installed()){
                $inst_install_url = wp_nonce_url('update.php?action=upgrade-plugin&plugin=' . $this->plugin_name, 'upgrade-plugin_' . $this->plugin_name);
                printf(__('Your Username & Password were accepted<br/>Now you can %1$sUpgrade Automatically!%2$s', 'formidable'), "<a href='{$inst_install_url}'>","</a>"); 
            }else{ 
                $frmpro_is_installed = $this->pro_is_installed_and_authorized();
                _e('Your Pro installation is now active. Enjoy!', 'formidable');
            } ?>
</strong></div>
<?php       }else{ ?>
<div class="error">
    <ul>
        <li><strong><?php _e('ERROR', 'formidable'); ?></strong>: <?php echo $this->pro_error_message_str; ?></li>
    </ul>
</div>
<?php
            }
        } 
?>
<div style="float:left;width:55%">
    <?php $this->display_pro_cred_form(); ?>
</div>

<?php if($frmpro_is_installed){ ?>
<div class="frm_pro_installed">
<p><strong class="alignleft" style="margin-right:10px;">Formidable Pro is Installed</strong>
    <a href="javascript:frm_show_auth_form()" class="button-secondary alignleft"><?php _e('Enter new credentials', 'formidable') ?></a>
    <a href="javascript:frm_deauthorize()" onclick="return confirm('<?php echo esc_attr(__('Are you sure you want to deactivate Formidable Pro on this site?', 'formidable')) ?>')" id="frm_deauthorize_link" class="button-secondary alignright"><?php _e('Deauthorize this site', 'formidable') ?></a>
</p>
<div class="clear"></div>
</div>
<p><strong><?php _e('Edit/Update Your Profile', 'formidable') ?>:</strong><br/>
    <span class="howto"><?php _e('Use your account username and password to log in to your Account and Affiliate Control Panel', 'formidable') ?></span></p>
<p><a href="http://formidablepro.com/payment/member.php" target="_blank"><?php _e('Account', 'formidable') ?></a> |
    <a href="http://formidablepro.com/payment/aff_member.php" target="_blank"><?php _e('Affiliate Control Panel', 'formidable') ?></a>
</p>

<script type="text/javascript">
function frm_show_auth_form(){
jQuery('#pro_cred_form,.frm_pro_installed').toggle();
}
function frm_deauthorize(){
jQuery('#frm_deauthorize_link').replaceWith('<img src="<?php echo FRM_IMAGES_URL; ?>/wpspin_light.gif" alt="<?php _e('Loading...', 'formidable'); ?>" id="frm_deauthorize_link" />');
jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_deauthorize",
success:function(msg){jQuery("#frm_deauthorize_link").fadeOut("slow"); frm_show_auth_form();}
});
};
</script>
<?php   }else{ ?>   

<div style="float:right;width:40%">       
    <p><?php _e('Ready to take your forms to the next level?<br/>Formidable Pro will help you style forms, manage data, and get reports.', 'formidable') ?></p>

    <a href="http://formidablepro.com"><?php _e('Learn More', 'formidable') ?> &#187;</a>
</div>
<?php   } ?>

<div class="clear"></div>

<?php    
    }

    function display_pro_cred_form(){
        global $frmpro_is_installed;
        
        // Yah, this is the view for the credentials form -- this class isn't a true model
        extract($this->get_pro_cred_form_vals());
        ?>
<div id="pro_cred_form" <?php echo ($frmpro_is_installed) ? 'style="display:none;"' : ''; ?>>
    <form name="cred_form" method="post" autocomplete="off">
    <input type="hidden" name="process_cred_form" value="Y" />
    <?php wp_nonce_field('cred_form'); ?>

    <table class="form-table">
        <tr class="form-field">
            <td valign="top" width="150px"><?php echo $this->pro_username_label; ?></td>
            <td><input type="text" name="<?php echo $this->pro_username_str; ?>" value=""/></td>
        </tr>
        <tr class="form-field">
            <td valign="top"><?php echo $this->pro_password_label; ?></td>
            <td><input type="password" name="<?php echo $this->pro_password_str; ?>" value=""/></td>
        </tr>
        <?php if (IS_WPMU){ ?>
        <tr>
            <td valign="top"><?php _e('WordPress MU', 'formidable'); ?></td>
            <td valign="top">
                <input type="checkbox" value="1" name="<?php echo $this->pro_wpmu_str; ?>" <?php checked($wpmu, 1) ?> />
                <?php _e('Use this username and password to enable Formidable Pro site-wide', 'formidable'); ?>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="2">
                <input class="button-secondary" type="submit" value="<?php _e('Save', 'formidable'); ?>" />
                <?php if($frmpro_is_installed){ 
                    _e('or', 'formidable'); 
                ?>
                <a href="javascript:frm_show_auth_form()" class="button-secondary"><?php _e('Cancel', 'formidable'); ?></a>
                <?php } ?>
            </td>
        </tr>
      </table>
    </form>
</div>
<?php
    }

    function process_pro_cred_form(){
        $creds = $this->get_pro_cred_form_vals();
        $user_authorized = $this->authorize_user($creds['username'], $creds['password']);

        if(!empty($user_authorized) and $user_authorized){
            if (IS_WPMU)
                update_site_option($this->pro_wpmu_store, $creds['wpmu']);

            if ($creds['wpmu']){
                update_site_option($this->pro_cred_store, $creds);
                update_site_option($this->pro_auth_store, $user_authorized);
            }else{
                update_option($this->pro_cred_store, $creds);
                update_option($this->pro_auth_store, $user_authorized);
            }

            extract($creds);
            $this->pro_username = (isset($username) and !empty($username)) ? $username : '';
            $this->pro_password = (isset($password) and !empty($password)) ? $password : '';

            if(!$this->pro_is_installed())
                $this->queue_update(true);
        }

        return $user_authorized;
    }

    function get_pro_cred_form_vals(){
        $username = ((isset($_POST[$this->pro_username_str]))?$_POST[$this->pro_username_str]:$this->pro_username);
        $password = ((isset($_POST[$this->pro_password_str]))?$_POST[$this->pro_password_str]:$this->pro_password);
        $wpmu = (isset($_POST[$this->pro_wpmu_str])) ? true : $this->pro_wpmu;

        return compact('username', 'password', 'wpmu');
    }

    function get_download_url($version){
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client( $this->pro_mothership_xmlrpc_url, false, 80, $this->timeout );

        if( !$client->query( 'proplug.get_encoded_download_url', $this->pro_username, $this->pro_password, $version, get_option('siteurl'), $this->plugin_nicename ) )
            return false;

        return base64_decode($client->getResponse());
    }

    function get_current_version(){
        include_once( ABSPATH . 'wp-includes/class-IXR.php' );

        $client = new IXR_Client( $this->pro_mothership_xmlrpc_url, false, 80, $this->timeout );

        if( !$client->query( 'proplug.get_current_version', $this->plugin_nicename ) )
            return false;

        return $client->getResponse();
    }

    function queue_update($force=false){
        static $already_set_option, $already_set_transient;
        global $frm_version;

        if(!is_admin())
            return;

        // Make sure this method doesn't check back with the mothership too often
        if($already_set_option or $already_set_transient)
            return;

        if(!$this->pro_is_authorized())
            return;
            
        // If pro is authorized but not installed then we need to force an upgrade
        if(!$this->pro_is_installed())
            $force = true;

        $plugin_updates = (function_exists('get_site_transient')) ? get_site_transient('update_plugins') : get_transient('update_plugins'); 
        if(!$plugin_updates and function_exists('get_transient'))
            $plugin_updates = get_transient('update_plugins');

        $curr_version = $this->get_current_version();
        if(!$curr_version)
            return;
            
        if(!isset($plugin_updates->checked))
            $plugin_updates->checked = array();
        $installed_version = isset($plugin_updates->checked[$this->plugin_name]) ? $plugin_updates->checked[$this->plugin_name] : $frm_version;

        if( $force or version_compare( $curr_version, $installed_version, '>') ){
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
            set_site_transient('update_plugins', $plugin_updates); // for WordPress 2.9+
        }

        if( function_exists('set_transient') and !$already_set_option ){
            $already_set_option = true;
            set_transient('update_plugins', $plugin_updates); // for WordPress 2.8
        }
        
    }

    function check_for_update_now(){
        $this->queue_update();
    }

    function periodically_check_for_update(){
        $last_checked = get_option($this->pro_last_checked_store);

        if(!$last_checked or ((time() - $last_checked) >= $this->pro_check_interval)){
            $this->queue_update();
            update_option($this->pro_last_checked_store, time());
        }
    }
  
    //Check if free version will be downloaded. If so, switch it to the Pro version
    function force_pro_version($plugin_updates){ 
        if(is_object($plugin_updates) and isset($plugin_updates->response) and 
          isset($plugin_updates->response[$this->plugin_name]) and $this->pro_is_authorized() and 
          ($plugin_updates->response[$this->plugin_name] == 'latest' 
          or $plugin_updates->response[$this->plugin_name]->url == 'http://wordpress.org/extend/plugins/'. $this->plugin_nicename .'/')){ 
          $curr_version = $this->get_current_version();
          if(!$curr_version)
              return;
              
          $installed_version = $plugin_updates->checked[$this->plugin_name];

          if( version_compare( $curr_version, $installed_version, '>') or !$this->pro_is_installed()){
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
          }
      }
      return $plugin_updates;
    }
}
?>
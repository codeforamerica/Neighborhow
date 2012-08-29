<div class="wrap">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2><?php _e('Form Settings', 'formidable'); ?></h2>

    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    
    <div id="poststuff" class="metabox-holder">
    <div id="post-body">
        <div class="meta-box-sortables">
        <div class="categorydiv postbox">
        <h3 class="hndle"><span><?php _e('Settings', 'formidable') ?></span></h3>
        <div class="inside">
        <div class="contextual-help-tabs">
        <ul <?php if(version_compare( $GLOBALS['wp_version'], '3.3.0', '<')) echo 'id="category-tabs" class="category-tabs"'; ?>>
        	<li class="tabs active"><a onclick="frmSettingsTab(jQuery(this),'general');" style="cursor:pointer"><?php _e('General', 'formidable') ?></a></li>
            <li><a onclick="frmSettingsTab(jQuery(this),'styling');" style="cursor:pointer"><?php _e('Form Styling', 'formidable') ?></a></li>
            <?php foreach($sections as $sec_name => $section){ ?>
                <li><a onclick="frmSettingsTab(jQuery(this),'<?php echo $sec_name ?>');"><?php echo ucfirst($sec_name) ?></a></li>
            <?php } ?>
        </ul>
        </div>
        
<?php if (IS_WPMU and !FrmAppHelper::is_super_admin() and get_site_option($frm_update->pro_wpmu_store)){ ?>
<div class="general_settings metabox-holder tabs-panel" style="min-height:0px;border-bottom:none;padding:0;">
<?php }else{ ?>
<div class="general_settings metabox-holder tabs-panel" style="min-height:0px;border-bottom:none;">
    <div class="postbox">
        <h3 class="hndle"><div id="icon-ms-admin" class="icon32 frm_postbox_icon"><br/></div> <?php _e('Formidable Pro Account Information', 'formidable')?></h3>
        <div class="inside">
            <?php $frm_update->pro_cred_form(); ?>
        </div>
    </div>
<?php } ?>
</div>
    
    <form name="frm_settings_form" method="post" class="frm_settings_form">
        <input type="hidden" name="frm_action" value="process-form" />
        <input type="hidden" name="action" value="process-form" />
        <?php wp_nonce_field('update-options'); ?>
            
        <div class="general_settings tabs-panel" style="border-top:none;">
        <table class="form-table">
            <tr><td colspan="2">
                <p class="submit" style="padding:0;">
                <input class="button-primary" type="submit" value="<?php _e('Update Options', 'formidable') ?>" />
                </p>
            </td></tr>
            <tr class="form-field">
                <td valign="top" width="200px"><?php _e('Admin menu label', 'formidable'); ?> </td>
                <td>
                    <input type="text" name="frm_menu" id="frm_menu" value="<?php echo esc_attr($frm_settings->menu) ?>" />
                    <?php if (IS_WPMU and FrmAppHelper::is_super_admin()){ ?>
                    <input type="checkbox" name="frm_mu_menu" id="frm_mu_menu" value="1" <?php checked($frm_settings->mu_menu, 1) ?> /> <?php _e('Use this menu name site-wide', 'formidable'); ?>
                    <?php } ?>
                </td>
            </tr>
            <tr class="form-field">
              <td valign="top"><?php _e('Preview Page', 'formidable'); ?> </td>
              <td>
                <?php FrmAppHelper::wp_pages_dropdown( $frm_settings->preview_page_id_str, $frm_settings->preview_page_id ) ?>
              </td>
            </tr>
            
            <tr>
              <td valign="top" width="200px"><?php _e('Tracking', 'formidable'); ?> </td>
              <td>
                  <p><input type="checkbox" id="frm_track" name="frm_track" value="1" <?php checked($frm_settings->track, 1) ?>> <?php _e('Track referrer information and pages visited', 'formidable') ?> 
                  </p>
              </td>
            </tr>
            
            <tr class="form-field">
                <td valign="top"><?php _e('Stylesheets', 'formidable'); ?> </td>
                <td>
                    
                    <p><?php _e('Load Formidable styling', 'formidable') ?>
                        <select id="frm_load_style" name="frm_load_style">
                        <option value="all" <?php selected($frm_settings->load_style, 'all') ?>><?php _e('on every page of your site', 'formidable') ?></option>
                        <option value="dynamic" <?php selected($frm_settings->load_style, 'dynamic') ?>><?php _e('only on applicable pages', 'formidable') ?></option>
                        <option value="none" <?php selected($frm_settings->load_style, 'none') ?>><?php _e('Don\'t use Formidable styling on any page', 'formidable') ?></option>
                        </select>
                    </p>
                    
                    <p><input type="checkbox" id="frm_use_html" name="frm_use_html" value="1" <?php checked($frm_settings->use_html, 1) ?> style="border:none;"> <?php _e('Use HTML5 in forms', 'formidable') ?> 
                    </p>
                    
                    <?php if($frmpro_is_installed){ ?>
                    <p><input type="checkbox" value="1" id="frm_jquery_css" name="frm_jquery_css" <?php checked($frm_settings->jquery_css, 1) ?> style="border:none;" />
                    <?php _e('Include the jQuery CSS on ALL pages', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The styling for the date field calendar. Some users may be using this css on pages other than just the ones that include a date field.', 'formidable') ?>" /></p>
                    <?php } ?>
                </td>
            </tr>
            
            <tr class="form-field">
                <td valign="top"><?php _e('User Permissions', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Select users that are allowed access to Formidable. Without access to View Forms, users will be unable to see the Formidable menu.', 'formidable') ?>" /></td>
                <td>
                    <?php foreach($frm_roles as $frm_role => $frm_role_description){ ?>
                        <label style="width:200px;float:left;text-align:right;padding-right:10px;"><?php echo $frm_role_description ?>:</label> <?php FrmAppHelper::wp_roles_dropdown( $frm_role, $frm_settings->$frm_role ) ?>
                        <div class="clear"></div>
                    <?php } ?>
                    
                </td>    
            </tr>
            
            <tr class="form-field" valign="top">
                <td><?php _e('reCAPTCHA', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('reCAPTCHA is a free, accessible CAPTCHA service that helps to digitize books while blocking spam on your blog. reCAPTCHA asks commenters to retype two words scanned from a book to prove that they are a human. This verifies that they are not a spambot.', 'formidable') ?>" />
                </td>
            	<td>
        			reCAPTCHA requires an API key, consisting of a "public" and a "private" key. You can sign up for a <a href="https://www.google.com/recaptcha/admin/create">free reCAPTCHA key</a>.
        			<br/>
        			
        				<!-- reCAPTCHA public key -->
        				<label style="width:135px;float:left;text-align:right;padding-right:10px;"><?php _e('Public Key', 'formidable') ?></label>
        				<input type="text" name="frm_pubkey" id="frm_pubkey" size="42" value="<?php echo esc_attr($frm_settings->pubkey) ?>" />
        				<br/>
        				<!-- reCAPTCHA private key -->
        				<label style="width:135px;float:left;text-align:right;padding-right:10px;"><?php _e('Private Key', 'formidable') ?></label>
        				<input type="text" name="frm_privkey" id="frm_privkey" size="42" value="<?php echo esc_attr($frm_settings->privkey) ?>" />
        			
            	</td>
            </tr>

            <tr class="form-field" valign="top">
            	<td></td>
            	<td>
        		    <label style="width:135px;float:left;text-align:right;padding-right:10px;"><?php _e('reCAPTCHA Theme', 'formidable') ?></label>
        			<select name="frm_re_theme" id="frm_re_theme">
        			<?php foreach(array('red' => __('Red', 'formidable'), 'white' => __('White', 'formidable'), 'blackglass' => __('Black Glass', 'formidable'), 'clean' => __('Clean', 'formidable')) as $theme_value => $theme_name){ ?>
        			<option value="<?php echo esc_attr($theme_value) ?>" <?php selected($frm_settings->re_theme, $theme_value) ?>><?php echo $theme_name ?></option>
        			<?php } ?>
        			</select><br/>
            		
    			    <label style="width:135px;float:left;text-align:right;padding-right:10px;"><?php _e('reCAPTCHA Language', 'formidable') ?></label>
    				<select name="frm_re_lang" id="frm_re_lang">
    				    <?php foreach(array('en' => __('English', 'formidable'), 'nl' => __('Dutch', 'formidable'), 'fr' => __('French', 'formidable'), 'de' => __('German', 'formidable'), 'pt' => __('Portuguese', 'formidable'), 'ru' => __('Russian', 'formidable'), 'es' => __('Spanish', 'formidable'), 'tr' => __('Turkish', 'formidable')) as $lang => $lang_name){ ?>
        				<option value="<?php echo esc_attr($lang) ?>" <?php selected($frm_settings->re_lang, $lang) ?>><?php echo $lang_name ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>    
            
            <tr class="form-field">
                <td valign="top"><?php _e('Default Messages', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('You can override the success message and submit button settings on individual forms.', 'formidable') ?>" /></td>
                <td>        
                    <?php _e('Incorrect Field', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The message seen when a field response is either incorrect or missing.', 'formidable') ?>" /><br/>
                    <input type="text" id="frm_invalid_msg" name="frm_invalid_msg" class="frm_long_input" value="<?php echo esc_attr(stripslashes($frm_settings->invalid_msg)) ?>" />
                </td>
            </tr>
            
            <tr class="form-field">
                <td></td>
                <td>
                    <?php _e('Success Message', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The default message seen after a form is submitted.', 'formidable') ?>" /><br/>
                    <input type="text" id="frm_success_msg" name="frm_success_msg" class="frm_long_input" value="<?php echo esc_attr(stripslashes($frm_settings->success_msg)) ?>" />
                </td>
            </tr>
            
            <tr class="form-field">
                <td></td>
                <td>        
                    <?php _e('Failed or Duplicate Entry Message', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The message seen when a form is submitted and passes validation, but something goes wrong.', 'formidable') ?>" /><br/>
                    <input type="text" id="frm_failed_msg" name="frm_failed_msg" class="frm_long_input" value="<?php echo esc_attr(stripslashes($frm_settings->failed_msg)) ?>" />
                </td>
            </tr> 
            
            <tr class="form-field">
                <td></td>
                <td>        
                    <?php _e('Login Message', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The message seen when a user who is not logged-in views a form only logged-in users can submit.', 'formidable') ?>" /><br/>
                    <input type="text" id="frm_login_msg" name="frm_login_msg" class="frm_long_input" value="<?php echo esc_attr(stripslashes($frm_settings->login_msg)) ?>" />
                </td>
            </tr>
            
            <tr class="form-field">
                <td></td>
                <td>    
                    <?php _e('Default Submit Button', 'formidable'); ?><br/>
                    <input type="text" value="<?php echo esc_attr(stripslashes($frm_settings->submit_value)) ?>" id="frm_submit_value" name="frm_submit_value" />
                </td>
            </tr>
            
            <?php if(!$frmpro_is_installed){ ?>
            </table>
            </div>
            <div class="styling_settings tabs-panel" style="display:none;">
            <table class="form-table">
                <tr><td>
                <div class="frm_update_msg">
                This plugin version does not give you access to the visual form styler.<br/>
                <a href="http://formidablepro.com/pricing/" target="_blank">Compare</a> our plans to see about upgrading to Pro. Or enter your account information <a href ="<?php echo admin_url('admin.php') ?>?page=formidable-settings">here</a>.
                </div>
                <img src="http://fp.strategy11.com/wp-content/themes/formidablepro/images/form_style_thumb.png" alt="Style Forms"/>
                </td></tr>
            <?php } ?>
            
            <?php do_action('frm_settings_form', $frm_settings); ?>
            
        </table>
        </div>
           
        <?php foreach($sections as $sec_name => $section){
                if(isset($section['class'])){
                    call_user_func(array($section['class'], $section['function'])); 
                }else{
                    call_user_func((isset($section['function']) ? $section['function'] : $section)); 
                }
        } ?>
        
        <p class="alignright frm_uninstall" style="padding-top:1.25em;"><a href="javascript:frm_uninstall_now()" class="button-secondary"><?php _e('Uninstall Formidable', 'formidable') ?></a></p>
        <p class="submit">
        <input class="button-primary" type="submit" value="<?php _e('Update Options', 'formidable') ?>" />
        </p>

    </form>
    </div>
    </div>
    </div>
</div>

</div>
</div>


<script type="text/javascript">
function frm_uninstall_now(){ 
if(confirm("<?php _e('Are you sure you want to do this? Clicking OK will delete all forms, form data, and all other Formidable data. There is no Undo.', 'formidable') ?>")){
    jQuery('.frm_uninstall a').replaceWith('<img src="<?php echo FRM_IMAGES_URL; ?>/wpspin_light.gif" alt="Loading..." />');
    jQuery.ajax({type:"POST",url:"<?php echo $frm_ajax_url ?>",data:"action=frm_uninstall",
    success:function(msg){jQuery(".frm_uninstall").fadeOut("slow");}
    });
}
};
</script>
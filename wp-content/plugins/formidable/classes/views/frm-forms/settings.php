<div id="form_settings_page" class="wrap">
    <div class="frmicon icon32"><br/></div>
    <h2><?php _e('Edit Form', 'formidable') ?>
        <a href="?page=formidable-new" class="button add-new-h2"><?php _e('Add New', 'formidable'); ?></a>
    </h2>
    <?php require(FRM_VIEWS_PATH.'/shared/errors.php'); ?>
    <?php require(FRM_VIEWS_PATH.'/shared/nav.php'); ?>
    <div class="alignright">
        <div id="postbox-container-1">
            <?php if(!isset($hide_preview) or !$hide_preview){ 
                if (!$values['is_template']){ ?>
            <p class="howto" style="margin-top:0;"><?php _e('Insert into a post, page or text widget', 'formidable') ?>
            <input type="text" style="text-align:center;font-weight:bold;width:100%;" readonly="true" onclick="this.select();" onfocus="this.select();" value="[formidable id=<?php echo $id; ?>]" /></p>
            <?php } ?>

            <p class="frm_orange"><a href="<?php echo FrmFormsHelper::get_direct_link($values['form_key']); ?>" target="_blank"><?php _e('Preview', 'formidable') ?></a>
            <?php global $frm_settings; 
                if ($frm_settings->preview_page_id > 0){ ?>
                <?php _e('or', 'formidable') ?> 
                <a href="<?php echo add_query_arg('form', $values['form_key'], get_permalink($frm_settings->preview_page_id)) ?>" target="_blank"><?php _e('Preview in Current Theme', 'formidable') ?></a>
            <?php } ?>
            </p>
            <?php
            } ?>
        </div>
    </div>
    <div class="alignleft">
    <?php FrmAppController::get_form_nav($id, true); ?>
    </div>
    

<form method="post">     
    <p style="clear:left;">        
        <input type="submit" value="<?php _e('Update', 'formidable') ?>" class="button-primary" />
        <?php _e('or', 'formidable') ?>
        <a class="button-secondary cancel" href="<?php echo esc_url(admin_url('admin.php?page=formidable') . '&frm_action=edit&id='. $id) ?>"><?php _e('Cancel', 'formidable') ?></a>
        <span style="margin-left:8px;">
        <?php FrmFormsHelper::forms_dropdown('frm_switcher', '', __('Switch Form', 'formidable'), false, "frmAddNewForm(this.value,'settings')"); ?>
        </span>
        <?php if($frmpro_is_installed and function_exists('icl_t')){ ?>
        <a href="<?php echo esc_url(admin_url('admin.php?page=formidable') .'&frm_action=translate&id='. $id) ?>" class="button-secondary"><?php _e('Translate Form', 'formidable') ?></a>
        <?php } ?>
    </p>
    
    <div class="clear"></div> 

        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="frm_action" value="update_settings" />
    <div id="poststuff" class="metabox-holder">
    <div id="post-body">
        <div class="meta-box-sortables">
        <div class="categorydiv postbox">
        <h3 class="hndle"><span><?php echo FrmAppHelper::truncate(stripslashes($values['name']), 40) .' '. __('Settings', 'formidable') ?></span></h3>
        <div class="inside">
        <div class="contextual-help-tabs">
        <ul <?php if(version_compare( $GLOBALS['wp_version'], '3.3.0', '<')) echo 'id="category-tabs" class="category-tabs"'; ?>>
        	<li class="tabs active"><a onclick="frmSettingsTab(jQuery(this),'advanced');"><?php _e('General', 'formidable') ?></a></li>
        	<li><a onclick="frmSettingsTab(jQuery(this),'notification');"><?php _e('Emails', 'formidable') ?></a></li>
            <li><a onclick="frmSettingsTab(jQuery(this),'html');"><?php _e('Customize HTML', 'formidable') ?></a></li>
            <li><a onclick="frmSettingsTab(jQuery(this),'post');"><?php _e('Create Posts', 'formidable') ?></a></li>
            <?php foreach($sections as $sec_name => $section){ ?>
                <li><a onclick="frmSettingsTab(jQuery(this),'<?php echo $sec_name ?>');"><?php echo ucfirst($sec_name) ?></a></li>
            <?php } ?>
        </ul>
        </div>
        <div style="display:block;" class="advanced_settings tabs-panel">
        	<table class="form-table">
                <tr>
                    <td width="200px"><label><?php _e('Form ShortCodes', 'formidable') ?></label> <a href="http://formidablepro.com/knowledgebase/publish-your-forms/" target="_blank"><img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Key and id are generally synonymous. For more information on using this shortcode, click now.', 'formidable') ?>" /></a></td>
                    <td><input type="text" style="width:250px; border:none; background:transparent;" readonly="true" onclick="this.select();" onfocus="this.select();" value="[formidable id=<?php echo $id; ?> title=true description=true]" /> <?php _e('or', 'formidable') ?>
                        <input type="text" style="width:200px; border:none; background:transparent;" readonly="true" onclick="this.select();" onfocus="this.select();" value="[formidable key=<?php echo $values['form_key']; ?>]" />
                    </td>
                </tr>

                <tr>
                    <td><label><?php _e('Form Key', 'formidable') ?></label></td>
                    <td><input type="text" name="form_key" value="<?php echo esc_attr($values['form_key']); ?>" /></td>
                </tr>

                <tr><td><label><?php _e('Styling', 'formidable') ?></label></td>
                    <td><input type="checkbox" name="options[custom_style]" id="custom_style" <?php echo ($values['custom_style']) ? ' checked="checked"' : ''; ?> value="1" />
                    <label for="custom_style"><?php _e('Use Formidable styling for this form', 'formidable') ?></label></td>
                </tr> 

                <tr><td><label><?php _e('Submit Button Text', 'formidable') ?></label></td>
                    <td><input type="text" name="options[submit_value]" value="<?php echo esc_attr($values['submit_value']); ?>" /></td>
                </tr>
                
                <tr><td valign="top"><label><?php _e('Action After Form Submission', 'formidable') ?></label>
                    <?php if(!$frmpro_is_installed){ ?>
                    <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('You must upgrade to Formidable Pro to get access to the second two options.', 'formidable') ?>" />
                    <?php } ?>
                    </td>
                    <td>
                        <input type="radio" name="options[success_action]" id="success_action_message" value="message" <?php checked($values['success_action'], 'message') ?> /> <label for="success_action_message"><?php _e('Display a Message', 'formidable') ?></label>
                        <input type="radio" name="options[success_action]" id="success_action_page" value="page" <?php checked($values['success_action'], 'page') ?> <?php if(!$frmpro_is_installed) echo 'disabled="disabled" '; ?>/> <label for="success_action_page" <?php echo $pro_feature ?>><?php _e('Display content from another page', 'formidable') ?></label>
                        <input type="radio" name="options[success_action]" id="success_action_redirect" value="redirect" <?php checked($values['success_action'], 'redirect') ?> <?php if(!$frmpro_is_installed) echo 'disabled="disabled" '; ?>/> <label for="success_action_redirect" <?php echo $pro_feature ?>><?php _e('Redirect to URL', 'formidable') ?></label>
                    </td>
                </tr>
                
                <tr class="success_action_redirect_box success_action_box" <?php echo ($values['success_action'] == 'redirect') ? '' : 'style="display:none;"'; ?>><td valign="top"><label><?php _e('Redirect to URL', 'formidable') ?></label></td>
                    <td><?php if(isset($values['id']) and $frmpro_is_installed) FrmProFieldsHelper::get_shortcode_select($values['id'], 'success_url'); ?><br/>
                        <input type="text" name="options[success_url]" id="success_url" value="<?php echo esc_attr($values['success_url']); ?>" size="55"></td>
                </tr>
                
                <tr class="success_action_message_box success_action_box" <?php echo ($values['success_action'] == 'message') ? '' : 'style="display:none;"'; ?>><td valign="top"><label><?php _e('Confirmation Message', 'formidable') ?></label></td>
                    <td><?php if($frmpro_is_installed and isset($values['id'])){ FrmProFieldsHelper::get_shortcode_select($values['id'], 'success_msg'); echo '<br/>'; } ?>
                        <textarea id="success_msg" name="options[success_msg]" cols="50" rows="4" class="frm_long_input"><?php echo FrmAppHelper::esc_textarea($values['success_msg']); ?></textarea> <br/>
                    <div class="frm_show_form_opt">
                    <input type="checkbox" name="options[show_form]" id="show_form" value="1" <?php checked($values['show_form'], 1) ?> /> <label for="show_form"><?php _e('Show the form with the success message.', 'formidable')?></label>
                    </div>
                    <td>
                </tr>

                <?php do_action('frm_additional_form_options', $values); ?> 
                
                <tr><td colspan="2"><input type="checkbox" name="options[no_save]" id="no_save" value="1" <?php checked($values['no_save'], 1); ?> /> <?php _e('Do not store any entries submitted from this form.', 'formidable') ?> <span class="howto"><?php _e('Warning: There is no way retrieve unsaved entries.', 'formidable') ?></span></td></tr>
                
                <?php if (function_exists( 'akismet_http_post' )){ ?>
                <tr><td colspan="2"><?php _e('Use Akismet to check entries for spam for', 'formidable') ?>
                        <select name="options[akismet]">
                            <option value=""><?php _e('no one', 'formidable') ?></option>
                            <option value="1" <?php selected($values['akismet'], 1)?>><?php _e('everyone', 'formidable') ?></option>
                            <option value="logged" <?php selected($values['akismet'], 'logged')?>><?php _e('visitors who are not logged in', 'formidable') ?></option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </div>

        <div class="notification_settings tabs-panel" style="display:none;">
        	<table class="form-table">                 
                 
                 <tr valign="top">
                     <td width="200px"><label><?php _e('From/Reply to', 'formidable') ?></label> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Usually the name and email of the person filling out the form. Select from Text, Email, User ID, or hidden fields for the name. &lt;br/&gt;Defaults to your site name and admin email found on the WordPress General Settings page.', 'formidable') ?>" /></td>
                     <td><span class="howto"><?php _e('Name', 'formidable') ?></span> 
                         <select name="options[reply_to_name]">
                         <option value=""><?php echo FrmAppHelper::truncate(get_option('blogname'), 80); ?></option>
                         <?php 
                         if(!empty($values['fields'])){
                         $field_select = array('text', 'email', 'user_id', 'hidden');
                         foreach($values['fields'] as $val_key => $fo){
                             if(in_array($fo['type'], $field_select)){ ?>
                                 <option value="<?php echo $fo['id'] ?>" <?php selected($values['reply_to_name'], $fo['id']); ?>><?php echo FrmAppHelper::truncate($fo['name'], 40) ?></option>
                     <?php }else if($fo['type'] == 'data' and $fo['data_type'] != 'show'){
                             if(isset($values['fields'][$val_key]['linked'])){
                                 foreach($values['fields'][$val_key]['linked'] as $linked_field){ 
                                 if(!in_array($linked_field->type, $field_select)) continue; ?>
                                 <option value="<?php echo $fo['id'] ?>|<?php echo $linked_field->id ?>" <?php selected($values['reply_to_name'], $fo['id'] .'|'. $linked_field->id); ?>><?php echo $fo['name'] .': '. FrmAppHelper::truncate($linked_field->name, 40) ?></option>
                             <?php } 
                             }
                             }
                         }
                         } ?>
                     </select>

                     <span class="howto" style="margin-left:10px;"><?php _e('Email', 'formidable') ?></span> 
                     &lt;<select name="options[reply_to]">
                         <option value=""><?php echo get_option('admin_email') ?></option>
                         <?php 
                         if(!empty($values['fields'])){
                         foreach($values['fields'] as $val_key => $fo){
                             if(in_array($fo['type'], $field_select)){ ?>
                                 <option value="<?php echo $fo['id'] ?>" <?php selected($values['reply_to'], $fo['id']); ?>><?php echo FrmAppHelper::truncate($fo['name'], 40) ?></option>
                         <?php }else if($fo['type'] == 'data' and $fo['data_type'] != 'show'){
                                 if(isset($values['fields'][$val_key]['linked'])){ ?>
                                 <?php foreach($values['fields'][$val_key]['linked'] as $linked_field){ 
                                     if(!in_array($linked_field->type, $field_select)) continue; ?>
                                     <option value="<?php echo $fo['id'] ?>|<?php echo $linked_field->id ?>" <?php selected($values['reply_to'], $fo['id'] .'|'. $linked_field->id); ?>><?php echo $fo['name'] .': '. FrmAppHelper::truncate($linked_field->name, 40) ?></option>
                                 <?php } 
                                 }
                             }
                         }
                         } ?>
                     </select>&gt;</td>
                 </tr>
                 
                  <tr>
                      <td><label><?php _e('Email Recipients', 'formidable') ?></label> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('To send to multiple addresses, separate each address with a comma. You can use [admin_email] to dynamically use the address on your WordPress General Settings page. &lt;br/&gt;PRO only: Leave blank if you do not want email notifications for this form.', 'formidable') ?>" /></td>
                      <td><input type="text" name="options[email_to]" value="<?php echo esc_attr($values['email_to']); ?>" class="frm_long_input" /></td>
                 </tr>
                 <?php if(!$frmpro_is_installed){ ?>
                 <tr><td colspan="2">
                     <?php FrmAppController::update_message('customize your email notifications and send auto responders'); ?>
                </td></tr>
                 <?php } ?>
                 <?php do_action('frm_additional_form_notification_options', $values); ?> 
             </table>
        </div>
        
        <div class="html_settings tabs-panel has-right-sidebar columns-2" style="display:none;">
            <?php 
                if(version_compare( $GLOBALS['wp_version'], '3.3.3', '<'))
                    require(FRM_VIEWS_PATH .'/frm-forms/sidebar-html.php'); 
            ?>
            
            <div id="post-body-content" class="frm_top_container" style="margin-right:260px;">
                <p><label class="frm_primary_label"><?php _e('Before Fields', 'formidable') ?></label>
                <textarea name="options[before_html]" rows="4" class="frm_long_input"><?php echo FrmAppHelper::esc_textarea($values['before_html']) ?></textarea></p>

                <div id="add_html_fields">
                    <?php 
                    if (isset($values['fields'])){
                        foreach($values['fields'] as $field){
                            if (apply_filters('frm_show_custom_html', true, $field['type'])){ ?>
                                <p><label class="frm_primary_label"><?php echo $field['name'] ?></label>
                                <textarea name="field_options[custom_html_<?php echo $field['id'] ?>]" rows="7" class="frm_long_input"><?php echo FrmAppHelper::esc_textarea($field['custom_html']) ?></textarea></p>
                            <?php }
                            unset($field);
                        }
                    } ?>
                </div>

                <p><label class="frm_primary_label"><?php _e('After Fields', 'formidable') ?></label>
                <textarea name="options[after_html]" rows="3" class="frm_long_input"><?php echo FrmAppHelper::esc_textarea($values['after_html']) ?></textarea></p> 
            </div>
            <?php 
                if(version_compare( $GLOBALS['wp_version'], '3.3.2', '>'))
                    require(FRM_VIEWS_PATH .'/frm-forms/sidebar-html.php'); 
            ?>
        </div>
        <div id="post_settings" class="post_settings tabs-panel" style="display:none;">
            <?php if($frmpro_is_installed)
                FrmProFormsController::post_options($values);
            else
                 FrmAppController::update_message('create and edit posts, pages, and custom post types through your forms');
            ?>
        </div>
        
        <?php foreach($sections as $sec_name => $section){
            if(isset($section['class'])){
                call_user_func(array($section['class'], $section['function']), $values); 
            }else{
                call_user_func((isset($section['function']) ? $section['function'] : $section), $values); 
            }
        } ?>
    
        <?php do_action('frm_add_form_option_section', $values); ?>
        <div class="clear"></div>
        </div>
        </div>
        </div>
</div>

</div>

    <p>        
        <input type="submit" value="<?php _e('Update', 'formidable') ?>" class="button-primary" />
        <?php _e('or', 'formidable') ?>
        <a class="button-secondary cancel" href="<?php echo admin_url('admin.php?page=formidable') ?>&amp;frm_action=edit&amp;id=<?php echo $id ?>"><?php _e('Cancel', 'formidable') ?></a>
    </p>
    </form>
</div>
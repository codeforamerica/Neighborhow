<div class="registration_settings tabs-panel" style="display:none;">
    <table class="form-table">
        <tr>
            <td width="200px"><label><?php _e('Registration', 'formidable') ?></label></td>
            <td><input type="checkbox" name="options[registration]" value="1" <?php checked($values['registration'], 1); ?> onclick="frm_show_div('hide_registration',this.checked,1,'.')"/> <?php _e('Register users who submit this form', 'formidable') ?></td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration = ($values['registration']) ? '' : 'style="display:none;"' ?>>
            <td><label><?php _e('Automatically Log In', 'formidable') ?></label></td>
            <td><input type="checkbox" name="options[login]" value="1" <?php checked($values['login'], 1); ?> /> <?php _e('Automatically log in users who submit this form', 'formidable') ?></td>
        </tr>
         
        <tr class="hide_registration" <?php echo $hide_registration = ($values['registration']) ? '' : 'style="display:none;"' ?>>
            <td><label><?php _e('User Email', 'formidable') ?></label></td>
            <td><select name="options[reg_email]">
                <?php 
                if(isset($fields) and is_array($fields)){
                    foreach($fields as $field){ 
                        if($field->type == 'email'){ ?>
                    <option value="<?php echo $field->id ?>" <?php selected($values['reg_email'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
                    unset($field); 
                    ?></option>
                    <?php 
                        }
                    }
                }
                ?>
                </select>    
            </td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td><label><?php _e('Username', 'formidable') ?></label></td>
            <td><select name="options[reg_username]">
                <option value="">- <?php _e('Automatically Generate from Email', 'formidable') ?> -</option>
                <option value="-1" <?php selected($values['reg_username'], '-1') ?>>- <?php _e('Use Full Email Address', 'formidable') ?> -</option>
                <?php 
                if(isset($fields) and is_array($fields)){
                    foreach($fields as $field){ 
                        if($field->type == 'text'){ ?>
                    <option value="<?php echo $field->id ?>" <?php selected($values['reg_username'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
                    unset($field); 
                    ?></option>
                    <?php 
                        }
                    }
                }
                ?>
                </select>    
            </td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td><label><?php _e('Password', 'formidable') ?></label></td>
            <td><select name="options[reg_password]">
                <option value="">- <?php _e('Automatically Generate', 'formidable') ?> -</option>
                <?php 
                if(isset($fields) and is_array($fields)){
                    foreach($fields as $field){ 
                        if(in_array($field->type, array('text', 'password'))){ ?>
                    <option value="<?php echo $field->id ?>" <?php selected($values['reg_password'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
                    unset($field); 
                    ?></option>
                    <?php 
                        }
                    }
                }
                ?>
                </select>    
            </td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td><label><?php _e('Name', 'formidable') ?></label></td>
            <td><span class="howto"><?php _e('First Name', 'formidable') ?></span> 
                <select name="options[reg_first_name]">
                <option value="">- <?php echo _e('None', 'formidable') ?> -</option>
                <?php 
                if(isset($fields) and is_array($fields)){
                    foreach($fields as $field){ 
                        if($field->type == 'text'){ ?>
                    <option value="<?php echo $field->id ?>" <?php selected($values['reg_first_name'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
                    unset($field); 
                    ?></option>
                    <?php 
                        }
                    }
                }
                ?>
                </select>
                
                <span class="howto"><?php _e('Last Name', 'formidable') ?></span> 
                <select name="options[reg_last_name]">
                    <option value="">- <?php echo _e('None', 'formidable') ?> -</option>
                    <?php 
                    if(isset($fields) and is_array($fields)){
                        foreach($fields as $field){ 
                            if($field->type == 'text'){ ?>
                        <option value="<?php echo $field->id ?>" <?php selected($values['reg_last_name'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
                        unset($field); 
                        ?></option>
                        <?php 
                            }
                        }
                    }
                    ?>
                </select> 
            </td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td><label><?php _e('Display Name', 'formidable') ?></label></td>
            <td>
                <select name="options[reg_display_name]">
                <option value="">- <?php echo _e('Same as Username', 'formidable') ?> -</option>
                <option value="display_firstlast" <?php selected($values['reg_display_name'], 'display_firstlast') ?>><?php _e('First Last (as selected above)', 'formidable') ?></option>
                <option value="display_lastfirst" <?php selected($values['reg_display_name'], 'display_lastfirst') ?>><?php _e('Last First (as selected above)', 'formidable') ?></option>
                <?php 
                if(isset($fields) and is_array($fields)){
                    foreach($fields as $field){ 
                        if($field->type == 'text'){ ?>
                    <option value="<?php echo $field->id ?>" <?php selected($values['reg_display_name'], $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
                    unset($field); 
                    ?></option>
                    <?php 
                        }
                    }
                }
                ?>
                </select>
            </td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td><label><?php _e('User Role', 'formidable') ?></label></td>
            <td><?php FrmAppHelper::wp_roles_dropdown('options[reg_role]', $values['reg_role']) ?></td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td valign="top"><label><?php _e('User Meta', 'formidable') ?></label></td>
            <td>
                <div id="frm_usermeta_rows" class="tagchecklist" style="padding-bottom:8px;">
                <?php foreach($values['reg_usermeta'] as $meta_name => $field_id){
                    include(FRM_REG_PATH .'/views/_usermeta_row.php');
                } ?>
                </div>
                <p><a href="javascript:frm_add_usermeta_row();" class="button">+ Add</a></p>
            </td>
        </tr>
        
        <?php if(false){ //if(class_exists('FrmPaymentsController')){ ?>
            <tr class="hide_registration" <?php echo $hide_registration ?>>
                <td><label><?php _e('Delay Email', 'formidable') ?></label></td>
                <td><input type="checkbox" name="options[reg_delay_email]" value="1" <?php checked($values['reg_delay_email'], 1); ?> /> <?php _e('Do not send the registration email until payment is completed', 'formidable') ?></td>
            </tr>
        <?php } ?>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td valign="top"><label><?php _e('Registration Email Subject', 'formidable') ?></label></td>
            <td><?php if(isset($values['id'])) FrmProFieldsHelper::get_shortcode_select($values['id'], 'reg_email_subject'); ?><br/>
        <input type="text" name="options[reg_email_subject]" id="reg_email_subject" value="<?php echo esc_attr($values['reg_email_subject']); ?>" class="frm_long_input" /></td>
        </tr>
        
        <tr class="hide_registration" <?php echo $hide_registration ?>>
            <td valign="top"><label><?php _e('Registration Email Message', 'formidable') ?></label><br/>
                <p class="howto">You can also use [username] and [password]</p>
            </td>
            <td>
                <?php if(isset($values['id'])) FrmProFieldsHelper::get_shortcode_select($values['id'], 'reg_email_msg', 'email'); ?><br/>             
                <textarea name="options[reg_email_msg]" id="reg_email_msg" class="frm_long_input" rows="5"><?php echo esc_html($values['reg_email_msg']); ?></textarea>
            </td>
        </tr>
        
    </table>
</div>


<script type="text/javascript">
function frm_remove_tag(html_tag){jQuery(html_tag).remove();}
function frm_add_usermeta_row(){
jQuery.ajax({
    type:"POST",url:"<?php echo $frm_ajax_url ?>",
    data:"action=frm_add_usermeta_row&form_id=<?php echo $values['id'] ?>&meta_name="+jQuery('#frm_usermeta_rows > div').size(),
    success:function(html){jQuery('#frm_usermeta_rows').append(html);}
});
}
</script>
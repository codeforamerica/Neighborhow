<tr class="success_action_page_box success_action_box" <?php echo ($values['success_action'] == 'page') ? '' : 'style="display:none;"'; ?>><td><label><?php _e('Use Content from Page', 'formidable') ?></label></td>
    <td>
        <?php FrmAppHelper::wp_pages_dropdown( 'options[success_page_id]', $values['success_page_id'] ) ?>
    </td>
</tr>

<!--
<tr><td colspan="2"><input type="checkbox" name="options[ajax_submit]" id="ajax_submit" value="1"<?php echo ($values['ajax_submit'])?(' checked="checked"'):(''); ?> /> <label for="ajax_submit"><?php _e('Submit this Form with AJAX', 'formidable') ?></label></td></tr>
-->

<tr><td colspan="2"><input type="checkbox" name="logged_in" id="logged_in" value="1"<?php echo ($values['logged_in']) ? ' checked="checked"' : ''; ?> /> <label for="logged_in"><?php _e('Only', 'formidable') ?></label> 
    <select name="options[logged_in_role]" id="logged_in_role">
        <option value=""><?php _e('Logged-in Users', 'formidable') ?></option>
        <?php foreach($editable_roles as $role => $details){ 
            $role_name = translate_user_role($details['name'] ); ?>
            <option value="<?php echo esc_attr($role) ?>" <?php echo (($values['logged_in_role'] == $role) ?' selected="selected"':''); ?>><?php echo $role_name ?> </option>
        <?php } ?> 
    </select>
<label for="logged_in"><?php _e('Can See and Submit this Form', 'formidable') ?></label></td></tr>

<tr><td colspan="2"><input type="checkbox" name="editable" id="editable" value="1"<?php echo ($values['editable']) ? ' checked="checked"' : ''; ?> /> <label for="editable"><?php _e('Allow','formidable') ?></label>
    <select name="options[editable_role]" id="editable_role">
        <option value=""><?php _e('Logged-in Users', 'formidable') ?></option>
        <?php foreach($editable_roles as $role => $details){ 
            $role_name = translate_user_role($details['name'] ); ?>
            <option value="<?php echo esc_attr($role) ?>" <?php echo (($values['editable_role'] == $role) ?' selected="selected"':''); ?>><?php echo $role_name ?> </option>
        <?php } ?> 
    </select>
    <label><?php _e('to Edit Their Own Previous Responses', 'formidable') ?></label></td></tr>

<tr class="hide_editable">
    <td colspan="2" style="padding-left:45px;"><input type="checkbox" name="options[open_editable]" id="open_editable" value="1"<?php echo ($values['open_editable'])?(' checked="checked"'):(''); ?> /> <label for="open_editable"><?php _e('Also Allow', 'formidable') ?></label>
    <select name="options[open_editable_role]" id="open_editable_role">
        <option value=""><?php _e('Logged-in Users', 'formidable') ?></option>
        <?php foreach($editable_roles as $role => $details){ 
            $role_name = translate_user_role($details['name'] ); ?>
            <option value="<?php echo esc_attr($role) ?>" <?php echo (($values['open_editable_role'] == $role) ?' selected="selected"':''); ?>><?php echo $role_name ?> </option>
        <?php } ?> 
    </select> 
<label for="open_editable"><?php _e('to Edit Responses Submitted by Anyone', 'formidable') ?></label></td>
</tr>

<tr class="hide_editable">
    <td style="padding-left:45px;"><label><?php _e('Update Submit Button Text', 'formidable') ?></label></td>
    <td><input type="text" name="options[edit_value]" value="<?php echo esc_attr($values['edit_value']); ?>" /></td>
</tr>

<tr class="hide_editable">
    <td style="padding-left:45px;" valign="top"><label><?php _e('Update Confirmation Message', 'formidable') ?></label></td>
    <td><textarea name="options[edit_msg]" cols="50" rows="4"><?php echo FrmAppHelper::esc_textarea($values['edit_msg']); ?></textarea></td>
</tr>

<tr><td><input type="checkbox" name="options[single_entry]" id="single_entry" value="1"<?php echo ($values['single_entry'])?(' checked="checked"'):(''); ?> /> <label for="single_entry"><?php _e('Allow Only One Entry for Each', 'formidable') ?></label></td>
    <td>
    <select name="options[single_entry_type]" id="frm_single_entry_type">
        <option value="user" <?php selected($values['single_entry_type'], 'user') ?>>- <?php _e('Logged-in User', 'formidable') ?> -</option>
        <option value="ip" <?php selected($values['single_entry_type'], 'ip') ?>>- <?php _e('IP Address', 'formidable') ?> -</option>
        <option value="cookie" <?php selected($values['single_entry_type'], 'cookie') ?>>- <?php _e('Saved Cookie', 'formidable') ?> -</option>
    <?php if(isset($values['fields']) and !empty($values['fields'])){
            foreach($values['fields'] as $field){ 
                if(in_array($field['type'], array('user_id', 'html', 'break', 'section', 'captcha', 'file'))) continue; 
    ?>
    <option value="<?php echo $field['id'] ?>" <?php selected($values['single_entry_type'], $field['id']) ?>><?php echo FrmAppHelper::truncate($field['name'], 200) ?></option>
    <?php 
            }
        } 
    ?>
    </select>
    </td>
</tr>

<tr id="frm_cookie_expiration" <?php echo ($values['single_entry_type'] == 'cookie') ? '' : 'style="display:none;"' ?>>
    <td><label><?php _e('Cookie Expiration', 'formidable') ?></label></td>
    <td><input type="text" name="options[cookie_expiration]" value="<?php echo esc_attr($values['cookie_expiration']) ?>"/> <span class="howto"><?php _e('hours', 'formidable') ?></span>
    </td>
</tr>

<?php if (IS_WPMU){ ?>
    <?php if (FrmAppHelper::is_super_admin()){ ?>
        <tr><td colspan="2">
        <input type="checkbox" name="options[copy]" id="copy" value="1" <?php echo ($values['copy'])? ' checked="checked"' : ''; ?> /> <?php _e('Copy this form to other blogs when Formidable Pro is activated', 'formidable') ?></td></tr>
    <?php }else if ($values['copy']){ ?>
        <input type="hidden" name="options[copy]" id="copy" value="1" />
    <?php } ?>
<?php } ?>
<script type="text/javascript">

function frm_add_postmeta_row(){
var meta_name=frmGetMetaValue('frm_postmeta_', jQuery('#frm_postmeta_rows > div').size());
jQuery.ajax({
    type:"POST",url:"<?php echo $frm_ajax_url ?>",
    data:"action=frm_add_postmeta_row&form_id=<?php echo $values['id'] ?>&meta_name="+meta_name,
    success:function(html){jQuery('#frm_postmeta_rows').append(html);}
});
}

function frm_add_posttax_row(){
var post_type=jQuery('select[name="options[post_type]"]').val();
var meta_name=frmGetMetaValue('frm_posttax_', jQuery('#frm_posttax_rows > div').size());
jQuery.ajax({
    type:"POST",url:"<?php echo $frm_ajax_url ?>",
    data:"action=frm_add_posttax_row&form_id=<?php echo $values['id'] ?>&post_type="+post_type+"&meta_name="+meta_name,
    success:function(html){jQuery('#frm_posttax_rows').append(html);}
});
}
</script>
<?php if (in_array($field['type'], array('website', 'phone', 'tag', 'date', 'email', 'url', 'number', 'password'))){ ?>
    <input type="text" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['default_value']); ?>" <?php do_action('frm_field_input_html', $field) ?>/>
<?php }else if ($field['type'] == 'hidden'){ ?>
    <input type="text" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['default_value']); ?>"/> 
    <p class="howto clear"><?php _e('Note: This field will not show in the form. Enter the value to be hidden.', 'formidable') ?></p>
<?php }else if($field['type'] == 'time'){ ?>
<select name="<?php echo $field_name ?>" id="field_<?php echo $field['field_key'] ?>" <?php do_action('frm_field_input_html', $field) ?>>
    <option value=""><?php echo $field['start_time'] ?></option>
    <option value="">...</option>
    <option value=""><?php echo $field['end_time'] ?></option>
</select>
<?php }else if ($field['type'] == 'user_id'){ ?>
    <p class="howto clear"><?php _e('Note: This field will not show in the form, but will link the user id to it as long as the user is logged in at the time of form submission.', 'formidable') ?></p>
<?php }else if ($field['type'] == 'image'){ ?>
    <input type="url" name="<?php echo $field_name ?>" value="<?php echo $field['default_value'] ?>" <?php do_action('frm_field_input_html', $field) ?> />
    <?php if ($field['default_value']){ ?><img src="<?php echo $field['default_value'] ?>" height="50px"><?php } ?>
<?php }else if ($field['type'] == '10radio' or $field['type'] == 'scale'){
        require(FRMPRO_VIEWS_PATH .'/frmpro-fields/10radio.php');

      }else if ($field['type'] == 'rte'){ ?>
          <!--
        <div id="<?php //echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
        	<?php //the_editor($field['default_value'], $field_name, 'title', false); ?>
        </div>
        -->
    <div class="frm_rte">
        <p class="howto"><?php _e('These buttons are for illustrative purposes only. They will be functional in your form.', 'formidable')?></p>
        <textarea name="<?php echo $field_name ?>" rows="<?php echo $field['max']; ?>"><?php echo FrmAppHelper::esc_textarea($field['default_value']); ?></textarea> 
    </div>
<?php }else if ($field['type'] == 'html'){ 
global $frmpro_settings; ?>
<div style="width:<?php echo $frmpro_settings->field_width ?>;margin-bottom:10px;text-align:center;">
<div class="howto button-secondary frm_html_field"><?php _e('This is a placeholder for your custom HTML.', 'formidable') ?><br/><?php _e('You can edit this content in the field options.', 'formidable') ?></div>   
</div>
<?php }else if ($field['type'] == 'data'){ ?>
    <div class="clear"></div>
    <?php if (!isset($field['data_type']) || $field['data_type'] == 'data'){ ?>
        <?php _e('This data is dynamic on change', 'formidable') ?>
    <?php }else if ($field['data_type'] == 'select'){ ?>        
        <select name='<?php echo $field_name ?>' id='<?php echo $field_name ?>'>
            <option value=""></option>
            <?php
                if ($field['options']){ 
                foreach ($field['options'] as $opt_key => $opt){ 
                    $selected = ($field['default_value'] == $opt_key)?(' selected="selected"'):(''); ?>
                    <option value="<?php echo $opt_key ?>"<?php echo $selected ?>><?php echo $opt ?></option>
            <?php }
                }else{ ?>
                <option value="">- <?php _e('This data is dynamic on change', 'formidable') ?> -</option>
            <?php } ?>
        </select>
    <?php }else if ($field['data_type'] == 'data' && is_numeric($field['hide_opt'])){ 
        echo FrmEntryMeta::get_entry_meta_by_field($field['hide_opt'], $field['form_select']);
        
        }else if ($field['data_type'] == 'checkbox'){
        $checked_values = $field['default_value'];
            
        if ($field['options']){
            foreach (stripslashes_deep($field['options']) as $opt_key => $opt){
                $checked = (FrmAppHelper::check_selected($checked_values, $opt_key)) ? ' checked="checked"' : '';
            ?>
                <input type="checkbox" name="<?php echo $field_name ?>[]" id="<?php echo $field_name ?>" value="<?php echo esc_attr($opt_key) ?>" <?php echo $checked ?>> <?php echo $opt ?><br/>
            <?php }
        }else echo 'There are no options'; ?>
    <?php }else if ($field['data_type'] == 'radio'){
        if ($field['options']){
            foreach (stripslashes_deep($field['options']) as $opt_key => $opt){ 
                $checked = ($field['default_value'] == $opt_key ) ? ' checked="true"':''; ?>
                <input type="radio" name="<?php echo $field_name ?>" id="<?php echo $field_name ?>" value="<?php echo esc_attr($opt_key) ?>" <?php echo $checked ?>> <?php echo $opt ?><br/>
            <?php }
        }else echo 'There are no options'; ?>
    <?php }else{ 
    _e('This data is dynamic on change', 'formidable');
        } 
    }else if ($field['type'] == 'file'){ ?>
    <input type="file" name="<?php echo $field_name ?>" <?php echo (isset($field['size']) and $field['size']) ? 'style="width:auto" size="'.$field['size'] .'"' : ''; ?> />
<?php }else if($field['type'] == 'form'){
    echo "FORM";
} ?>

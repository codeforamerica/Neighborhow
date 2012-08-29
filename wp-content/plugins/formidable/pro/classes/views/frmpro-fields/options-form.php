<?php if($display['type'] == 'radio' or $display['type'] == 'checkbox'){ ?>
<tr><td><label><?php _e('Alignment', 'formidable') ?></label></td>
    <td>
        <select name="field_options[align_<?php echo $field['id'] ?>]">
            <option value="block" <?php selected($field['align'], 'block') ?>><?php _e('Multiple Rows', 'formidable'); ?></option>
            <option value="inline" <?php selected($field['align'], 'inline') ?>><?php _e('Single Row', 'formidable'); ?></option>
        </select>
    </td>
</tr>
<?php } 

if(in_array($display['type'], array('radio', 'checkbox', 'select'))){ ?>
<tr><td><?php _e('Use separate values', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Add a separate value to use for calculations, email routing, saving to the database, and many other uses. The option values are saved while the option labels are shown in the form.', 'formidable') ?>" /></td>
    <td><input type="checkbox" name="field_options[separate_value_<?php echo $field['id'] ?>]" id="separate_value_<?php echo $field['id'] ?>" value="1" <?php checked($field['separate_value'], 1) ?> onclick="frmSeparateValue(<?php echo $field['id'] ?>)" /> <label for="separate_value_<?php echo $field['id'] ?>"><?php _e('Use separate values', 'formidable'); ?></label></td>
</tr>
<?php 
}

if(in_array($field['type'], array('radio', 'checkbox', 'select', 'scale', 'user_id', 'data'))){ ?>
<tr><td><?php _e('Default Dynamic Value', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('If your radio, checkbox, dropdown, or user ID field needs a dynamic default value like [get param=whatever], insert it in the field options. If using a GET or POST value, it must match one of the options in the field in order for that option to be selected. Data from entries fields require the ID of the linked entry.', 'formidable') ?>" /></td>
    <td><input type="text" name="field_options[dyn_default_value_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['dyn_default_value']) ?>" /></td>
</tr>
<?php 
}

if ($display['type'] == 'divider'){ ?>
<tr><td colspan="2"><input type="checkbox" name="field_options[slide_<?php echo $field['id'] ?>]" value='1'<?php echo ($field['slide'])?' checked="checked"':''; ?> /> <?php _e('Make this section collapsable', 'formidable') ?></td>
</tr>
<?php }else if ($field['type'] == 'data'){
        global $frm_form, $frm_field;
        $form_list = $frm_form->getAll("(status is NULL OR status = '' OR status = 'published') and is_template=0", ' ORDER BY name');
        $selected_field = '';
        $current_field_id = $field['id'];
        if (isset($field['form_select']) && is_numeric($field['form_select'])){
            $selected_field = $frm_field->getOne($field['form_select']);
            $fields = $frm_field->getAll(array('fi.form_id' => $selected_field->form_id));
        }else if(isset($field['form_select'])){
            $selected_field = $field['form_select'];
        }
        require(FRMPRO_VIEWS_PATH .'/frmpro-fields/dynamic-options.php');
        unset($current_field_id);
?>
<tr><td valign="top"><label><?php _e('Display as', 'formidable') ?></label></td>
    <td><select name="field_options[data_type_<?php echo $field['id'] ?>]">
        <option value="data"><?php _e('Just show it', 'formidable') ?></option>
        <?php foreach(array('select', 'checkbox', 'radio') as $display_opt){ 
            $selected = (isset($field['data_type']) && $field['data_type'] == $display_opt)? ' selected="selected"':''; ?>
        <option value="<?php echo $display_opt ?>"<?php echo $selected; ?>><?php echo $frm_field_selection[$display_opt] ?></option>
        <?php } ?>
        </select>
    </td>
</tr>
<?php }else if($field['type'] == 'date'){ ?>
    <tr><td><label><?php _e('Calendar Localization', 'formidable') ?></label></td>
    <td>    
    <select name="field_options[locale_<?php echo $field['id'] ?>]">
        <?php foreach($locales as $locale_key => $locale){
            $selected = (isset($field['locale']) && $field['locale'] == $locale_key)? ' selected="selected"':''; ?>
            <option value="<?php echo $locale_key ?>"<?php echo $selected; ?>><?php echo $locale ?></option>
        <?php } ?>
    </select>
    </td>
    </tr>
<tr><td><label><?php _e('Year Range', 'formidable') ?></label></td>   
    <td>
    <span><?php _e('Start Year', 'formidable') ?></span>
    <input type="text" name="field_options[start_year_<?php echo $field['id'] ?>]" value="<?php echo isset($field['start_year']) ? $field['start_year'] : ''; ?>" size="4"/>
    
    <span><?php _e('End Year', 'formidable') ?></span> 
    <input type="text" name="field_options[end_year_<?php echo $field['id'] ?>]" value="<?php echo isset($field['end_year']) ? $field['end_year'] : ''; ?>" size="4"/>
    </td>
</tr>
<?php }else if($field['type'] == 'time'){ ?>
<tr><td><label><?php _e('Clock Settings', 'formidable') ?></label></td>
    <td>
        <select name="field_options[clock_<?php echo $field['id'] ?>]">
            <option value="12" <?php selected($field['clock'], 12) ?>>12</option>
            <option value="24" <?php selected($field['clock'], 24) ?>>24</option>
        </select> <span class="howto" style="padding-right:10px;"><?php _e('hour clock', 'formidable') ?></span>

        <input type="text" name="field_options[step_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['step']); ?>" size="3" />
        <span class="howto" style="padding-right:10px;"><?php _e('minute step', 'formidable') ?></span> 
        
        <input type="text" name="field_options[start_time_<?php echo $field['id'] ?>]" id="start_time_<?php echo $field['id'] ?>" value="<?php echo esc_attr($field['start_time']) ?>" size="5"/>
        <span class="howto" style="padding-right:10px;"><?php _e('start time', 'formidable') ?></span> 
        
        <input type="text" name="field_options[end_time_<?php echo $field['id'] ?>]" id="end_time_<?php echo $field['id'] ?>" value="<?php echo esc_attr($field['end_time']) ?>" size="5"/>
        <span class="howto"><?php _e('end time', 'formidable') ?></span> 
<?php global $frm_timepicker_loaded;
    $frm_timepicker_loaded['end_time_'. $field['id']] = $frm_timepicker_loaded['start_time_'. $field['id']] = array('clock' => true, 'step' => $field['step']);
?>
    </td>
</tr>
<?php }else if($field['type'] == 'file'){ ?>
    <tr><td><label for="field_options[attach_<?php echo $field['id'] ?>]"><?php _e('Email Attachment', 'formidable') ?></label></td>
        <td><input type="checkbox" name="field_options[attach_<?php echo $field['id'] ?>]" value="1" <?php echo (isset($field['attach']) and $field['attach'])? 'checked="checked"':''; ?> /> <span class="howto"><?php _e('attach this file to the email notification', 'formidable') ?></span></td>
    </tr>
    <?php if($mimes){ ?>
    <tr><td valign="top"><label for="field_options[ftypes_<?php echo $field['id'] ?>]"><?php _e('Allowed file types', 'formidable') ?></label></td>
        <td>
            <input type="radio" name="field_options[restrict_<?php echo $field['id'] ?>]" id="restrict_<?php echo $field['id'] ?>_0" value="0" <?php FrmAppHelper::checked($field['restrict'], 0); ?> onclick="frm_show_div('restrict_box_<?php echo $field['id'] ?>',this.value,1,'.')" /> <label for="restrict_<?php echo $field['id'] ?>_0"><?php _e('All types', 'formidable') ?></label>
            <input type="radio" name="field_options[restrict_<?php echo $field['id'] ?>]" id="restrict_<?php echo $field['id'] ?>_1" value="1" <?php FrmAppHelper::checked($field['restrict'], 1); ?> onclick="frm_show_div('restrict_box_<?php echo $field['id'] ?>',this.value,1,'.')" /> <label for="restrict_<?php echo $field['id'] ?>_1"><?php _e('Specify allowed types', 'formidable') ?></label>
            <span class="restrict_box_<?php echo $field['id'] ?>" <?php echo ($field['restrict'] == 1) ? '' : 'style="display:none"'; ?>>
            <label for="check_all_ftypes_<?php echo $field['id'] ?>"><input type="checkbox" id="check_all_ftypes_<?php echo $field['id'] ?>" onclick="frmCheckAll(this.checked,'field_options[ftypes_<?php echo $field['id'] ?>]')" /> <span class="howto" style="float:none;"><?php _e('Check All', 'formidable') ?></span></label>
            </span>
            <div class="restrict_box_<?php echo $field['id'] ?>" <?php echo ($field['restrict'] == 1) ? '' : 'style="display:none"'; ?>>
            <div class="frm_field_opts_list" style="width:100%;">
                <div class="alignleft" style="width:33% !important">
                    <?php 
                    $mcount = count($mimes);
                    $third = ceil($mcount/3);
                    $c = 0;
                    foreach($mimes as $ext_preg => $mime){ 
                        if($c == $third or (($c/2) == $third)){ ?>
                    </div>
                    <div class="alignleft" style="width:33% !important">
                    <?php } ?>
                    <input type="checkbox" name="field_options[ftypes_<?php echo $field['id'] ?>][<?php echo $ext_preg ?>]" value="<?php echo $mime ?>" <?php if(isset($field['ftypes']) and !empty($field['ftypes'])) FrmAppHelper::checked($field['ftypes'], $mime); ?> /> <span class="howto"><?php echo str_replace('|', ', ', $ext_preg); ?></span><br/>
                    <?php 
                        $c++;
                        unset($ext_preg);
                        unset($mime);
                    } 
                    unset($c);
                    unset($mcount);
                    unset($third);
                    ?>
                </div>
            </div>
            </div>
        </td>
    </tr>
    <?php } ?>
<?php }else if($field['type'] == 'number' and $frm_settings->use_html){ ?>
    <tr><td width="150px"><label><?php _e('Number Range', 'formidable') ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Browsers that support the HTML5 number field require a number range to determine the numbers seen when clicking the arrows next to the field.', 'formidable') ?>" /></label></td>
        <td><input type="text" name="field_options[minnum_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['minnum']); ?>" size="5" /> <span class="howto"><?php echo _e('minimum', 'formidable') ?></span>
        <input type="text" name="field_options[maxnum_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['maxnum']); ?>" size="5" /> <span class="howto"><?php _e('maximum', 'formidable') ?></span>
        <input type="text" name="field_options[step_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['step']); ?>" size="5" /> <span class="howto"><?php _e('step', 'formidable') ?></span></td>
    </tr>
<?php }else if($field['type'] == '10radio' or $field['type'] == 'scale'){ ?>
    <tr><td><label><?php _e('Range', 'formidable') ?></label></td>
        <td>
            <select name="field_options[minnum_<?php echo $field['id'] ?>]">
                <?php for( $i=1; $i<10; $i++ ){ 
                    $selected = (isset($field['minnum']) && $field['minnum'] == $i)? ' selected="selected"':''; ?>
                <option value="<?php echo $i ?>"<?php echo $selected; ?>><?php echo $i ?></option>
                <?php } ?>
            </select> <?php _e('to', 'formidable') ?>
            <select name="field_options[maxnum_<?php echo $field['id'] ?>]">
                <?php for( $i=1; $i<=20; $i++ ){ 
                    $selected = (isset($field['maxnum']) && $field['maxnum'] == $i)? ' selected="selected"':''; ?>
                <option value="<?php echo $i ?>"<?php echo $selected; ?>><?php echo $i ?></option>
                <?php } ?>
            </select>
        </td>
    </tr>
    <tr><td><label><?php _e('Stars', 'formidable') ?></label></td>
        <td><input type="checkbox" value="1" name="field_options[star_<?php echo $field['id'] ?>]" <?php checked((isset($field['star']) ? $field['star'] : 0), 1) ?> />
            <?php _e('Show options as stars', 'formidable') ?>
        </td>
    </tr>
<?php }else if($field['type'] == 'rte' and function_exists('wp_editor')){ ?>
<tr><td><?php _e('Rich Text Editor', 'formidable') ?></td>
<td>
    <select name="field_options[rte_<?php echo $field['id'] ?>]">
        <option value="nicedit" <?php selected($field['rte'], 'nicedit') ?>>NicEdit</option>
        <option value="mce" <?php selected($field['rte'], 'mce') ?>>Tiny MCE</option>
    </select>
</td>
</tr>
<?php }else if($field['type'] == 'html'){ ?>
<tr><td colspan="2"><?php _e('Content', 'formidable') ?><br/>
<textarea name="field_options[description_<?php echo $field['id'] ?>]" style="width:98%;" rows="8"><?php echo FrmAppHelper::esc_textarea($field['description']) ?></textarea>
</td>
</tr>
<?php }else if($field['type'] == 'form'){ ?>
<tr><td><?php _e('Insert Form', 'formidable') ?></td>    
<td><?php FrmFormsHelper::forms_dropdown('field_options[form_select_'. $field['id'] .']', $field['form_select'], true); ?></td>

<tr><td><?php _e('Maximum Duplication', 'formidable') ?></td>    
<td><input type="text" name="field_options[duplication_<?php $field['id'] ?>]" value="<?php echo esc_attr($field['duplication']) ?>" size="3"/> <span class="howto"><?php _e('The number of times the end user is allowed to duplicate this section of fields in one entry', 'formidable') ?></span></td>
</tr>
<?php }

if(!in_array($field['type'], array('html', 'form'))){ ?>
<tr><td width="150px">
<?php if (!empty($field_types)){ ?>
<label><?php _e('Field Type', 'formidable') ?></label></td>
    <td><select name="field_options[type_<?php echo $field['id'] ?>]">
    <?php foreach ($field_types as $fkey => $ftype){ 
            $selected = ($fkey == $field['type'])?' selected="selected"':''; ?>
            <option value="<?php echo $fkey ?>" <?php echo $selected ?>><?php echo $ftype ?></option>
    <?php } ?>
    </select>
<?php }else{ ?>
<label><?php _e('Field Type', 'formidable') ?></label></td>
<td>
<?php }

if(!in_array($field['type'], array('break', 'hidden', 'user_id', 'divider', 'html', 'form'))){ ?>
<input type="checkbox" name="field_options[unique_<?php echo $field['id'] ?>]" value="1" <?php echo $field['unique'] ? ' checked="checked"' : ''; ?>/> <?php _e('Unique', 'formidable') ?> 
<img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Unique: Do not allow the same response multiple times. For example, if one user enters \'Joe\' then no one else will be allowed to enter the same name.', 'formidable') ?>" />
<?php if(!in_array($field['type'], array('scale', 'radio', 'checkbox', 'data'))){ ?>
<input type="checkbox" name="field_options[read_only_<?php echo $field['id'] ?>]" value="1" <?php echo $field['read_only'] ? ' checked="checked"' : ''; ?>/> <?php _e('Read Only', 'formidable') ?>
<img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Read Only: Show this field but do not allow the field value to be edited from the front-end.', 'formidable') ?>" />
<?php } ?>
<input type="checkbox" name="field_options[admin_only_<?php echo $field['id'] ?>]" value="1" <?php echo $field['admin_only'] ? ' checked="checked"' : ''; ?>/> <?php _e('Admin Only', 'formidable') ?>
<img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Admin Only: Hide this field from any users who are not Administrators.', 'formidable') ?>" />
<?php } ?>
</td>
</tr>
<?php 
}

if(in_array($field['type'], array('text', 'number', 'textarea', 'hidden'))){ ?>
<tr><td valign="top"><?php _e('Calculations', 'formidable') ?></td>
    <td><input type="checkbox" value="1" name="field_options[use_calc_<?php echo $field['id'] ?>]" <?php checked($field['use_calc'], 1) ?> onchange="frm_show_div('frm_calc_opts<?php echo $field['id'] ?>',this.checked,true,'#')" /> 
        <?php _e('Calculate the default value for this field', 'formidable') ?> 
        <div id="frm_calc_opts<?php echo $field['id'] ?>" <?php if(!$field['use_calc']) echo 'style="display:none"'; ?>>
            <?php FrmProFieldsHelper::get_shortcode_select($field['form_id'], 'frm_calc_'. $field['id'], 'field_opt'); ?><br/>
            <input type="text" value="<?php echo esc_attr($field['calc']) ?>" id="frm_calc_<?php echo $field['id'] ?>" name="field_options[calc_<?php echo $field['id'] ?>]" class="frm_long_input"/>
        </div>
    </td>
</tr>

<?php }

if ($form_fields and !in_array($field['type'], array('hidden', 'user_id', 'break'))){ ?>
<tr valign="top"><td><?php _e('Conditional Logic', 'formidable'); ?></td>
    <td>
    <a href="javascript:frmToggleLogic('logic_<?php echo $field['id'] ?>')" class="button-secondary" id="logic_<?php echo $field['id'] ?>" <?php echo (!empty($field['hide_field'])) ? ' style="display:none"' : ''; ?>><?php _e('Use Conditional Logic', 'formidable') ?></a>
    <div class="frm_logic_rows tagchecklist" <?php echo (!empty($field['hide_field'])) ? '' : ' style="display:none"'; ?>>
        <div id="frm_logic_row_<?php echo $field['id'] ?>">
        <select name="field_options[show_hide_<?php echo $field['id'] ?>]">
            <option value="show" <?php selected($field['show_hide'], 'show') ?>><?php _e('Show', 'formidable') ?></option>
            <option value="hide" <?php selected($field['show_hide'], 'hide') ?>><?php _e('Hide', 'formidable') ?></option>
        </select>
        <?php _e('this field if', 'formidable'); ?>
        <select name="field_options[any_all_<?php echo $field['id'] ?>]">
            <option value="any" <?php selected($field['any_all'], 'any') ?>><?php _e('any', 'formidable') ?></option>
            <option value="all" <?php selected($field['any_all'], 'all') ?>><?php _e('all', 'formidable') ?></option>
        </select>
        <?php _e('of the following match', 'formidable') ?>:
            
        <?php 
            if(!empty($field['hide_field'])){ 
                foreach($field['hide_field'] as $meta_name => $hide_field){
                    include(FRMPRO_VIEWS_PATH .'/frmpro-fields/_logic_row.php');
                }
            }
        ?>
        </div>
        <p><a class="button" href="javascript:frm_add_logic_row(<?php echo $field['id'] ?>,'<?php echo $frm_ajax_url ?>',<?php echo $field['form_id'] ?>);">+ <?php _e('Add', 'formidable') ?></a></p>
    </div>
    
    
    </td>
</tr>
<?php }

if (!$frm_settings->lock_keys){ ?>
<tr><td width="150px"><?php _e('Field Key', 'formidable') ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The field key can be used as an alternative to the field ID in many cases.', 'formidable') ?>" /></td>
    <td><input type="text" name="field_options[field_key_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['field_key']); ?>" size="20" /></td>
</tr>
<?php } ?>   
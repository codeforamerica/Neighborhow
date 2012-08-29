<?php $display = apply_filters('frm_display_field_options', array(
    'type' => $field['type'], 'field_data' => $field, 'required' => true, 
    'description' => true, 'options' => true, 'label_position' => true, 
    'invalid' => false, 'size' => false, 'clear_on_focus' => false, 
    'default_blank' => true, 'css' => true
)); ?>

<li id="frm_field_id_<?php echo $field['id']; ?>" class="form-field edit_form_item frm_field_box ui-state-default frm_hide_options<?php echo $display['options'] ?> edit_field_type_<?php echo $display['type'] ?> frm_top_container" onmouseover="frm_field_hover(1,<?php echo $field['id']; ?>)" onmouseout="frm_field_hover(0,<?php echo $field['id']; ?>)">
    <a href="javascript:void(0);" class="alignright frm-show-hover frm-move frm-hover-icon" title="Move Field"><img src="<?php echo FRM_IMAGES_URL ?>/move.png" alt="Move" /></a>
    <a href="javascript:frm_delete_field(<?php echo $field['id']; ?>)" class="alignright frm-show-hover frm-hover-icon" id="frm_delete_field<?php echo $field['id']; ?>" title="Delete Field"><img src="<?php echo FRM_IMAGES_URL ?>/trash.png" alt="Delete" /></a>
    <a href="javascript:frm_duplicate_field(<?php echo $field['id']; ?>)" class="alignright frm-show-hover frm-hover-icon" title="<?php _e('Duplicate Field', 'formidable') ?>"><img src="<?php echo FRM_IMAGES_URL ?>/duplicate.png" alt="<?php _e('Duplicate', 'formidable') ?>" /></a>
    <?php do_action('frm_extra_field_actions', $field['id']); ?>
    
    <?php if ($display['required']){ ?>
    <span id="require_field_<?php echo $field['id']; ?>">
        <a href="javascript:frm_mark_required(<?php echo $field['id']; ?>,<?php echo $field_required = ($field['required'] == '0')? '0' : '1'; ?>)" class="frm_action_icon frm_required_icon alignleft frm_required<?php echo $field_required ?>" id="req_field_<?php echo $field['id']; ?>" title="Click to Mark as <?php echo ($field['required'] == '0') ? '' : 'not '; ?>Required"></a>
    </span>
    <?php } ?>
    <label class="frm_ipe_field_label frm_primary_label" id="field_<?php echo $field['id']; ?>"><?php echo $field['name'] ?></label>

<div class="frm_form_fields">    
<?php if ($display['type'] == 'text'){ ?>
    <input type="text" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['default_value']); ?>" <?php echo (isset($field['size']) && $field['size']) ? 'style="width:auto" size="'.$field['size'] .'"' : ''; ?> /> 
<?php }else if ($field['type'] == 'textarea'){ ?>
    <textarea name="<?php echo $field_name ?>"<?php if ($field['size']) echo ' style="width:auto" cols="'.$field['size'].'"' ?> rows="<?php echo $field['max']; ?>"><?php echo FrmAppHelper::esc_textarea($field['default_value']); ?></textarea> 
  
<?php 

}else if ($field['type'] == 'radio' or $field['type'] == 'checkbox'){
    $field['default_value'] = maybe_unserialize($field['default_value']); 
    if(isset($field['post_field']) and $field['post_field'] == 'post_category'){
        do_action('frm_after_checkbox', array('field' => $field, 'field_name' => $field_name, 'type' => $field['type']));
    }else{ ?>
        <div id="frm_field_<?php echo $field['id'] ?>_opts" class="clear<?php echo (count($field['options']) > 10) ? ' frm_field_opts_list' : ''; ?>">
        <?php do_action('frm_add_multiple_opts_labels', $field); ?>
        <?php require(FRM_VIEWS_PATH.'/frm-fields/radio.php'); ?>
        </div>
    <?php
    }
        
?>
    <div id="frm_add_field_<?php echo $field['id']; ?>" class="frm-show-click">
        <a href="javascript:frm_add_field_option(<?php echo $field['id']; ?>)" class="frm_orange frm_add_opt">+ <?php _e('Add an Option', 'formidable') ?></a>
        
        <?php if (!isset($field['post_field']) or $field['post_field'] != 'post_category'){ ?>
        <?php _e('or', 'formidable'); ?>
        <a title="<?php echo FrmAppHelper::truncate(esc_attr(strip_tags(str_replace('"', '&quot;', $field['name']))), 20) . ' '. __('Field Choices', 'formidable'); ?>" href="<?php echo esc_url(FRM_SCRIPT_URL .'&controller=fields&frm_action=import_choices&field_id='. $field['id'] .'&TB_iframe=1') ?>" class="thickbox frm_orange"><?php _e('Bulk Edit Field Choices', 'formidable') ?></a>
        <?php } ?>
    </div>
<?php

}else if ($field['type'] == 'select'){ 
    if(isset($field['post_field']) and $field['post_field'] == 'post_category'){
        echo FrmFieldsHelper::dropdown_categories(array('name' => $field_name, 'field' => $field) );
    }else{ ?>
    <select name="<?php echo $field_name ?>" <?php echo (isset($field['size']) && $field['size']) ? 'style="width:auto"' : ''; ?>>
        <?php foreach ($field['options'] as $opt_key => $opt){ 
            $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $field);
            $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $field);
            $selected = ($field['default_value'] == $field_val)?(' selected="selected"'):(''); ?>
            <option value="<?php echo $field_val ?>"<?php echo $selected ?>><?php echo $opt ?></option>
        <?php } ?>
    </select>
<?php } 
    if ($display['default_blank']){ ?>
        <span id="frm_clear_on_focus_<?php echo $field['id'] ?>" class="frm-show-click">
        <?php FrmFieldsHelper::show_default_blank_js($field['id'], $field['default_blank']); ?>
        </span>
    <?php } ?>
    <div class="clear"></div>
    <br/>
    <div class="frm-show-click">
    <?php if(isset($field['post_field']) and $field['post_field'] == 'post_category'){ ?>
        <p class="howto"><?php _e('Please add options from the WordPress "Categories" page', 'formidable') ?></p>
    <?php }else if(!isset($field['post_field']) or $field['post_field'] != 'post_status'){ ?>
        <div id="frm_field_<?php echo $field['id'] ?>_opts"<?php echo (count($field['options']) > 10) ? ' class="frm_field_opts_list"' : ''; ?>>
        <?php do_action('frm_add_multiple_opts_labels', $field); ?>
        <?php foreach ($field['options'] as $opt_key => $opt){ 
                $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $field);
                $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $field);
                require(FRM_VIEWS_PATH.'/frm-fields/single-option.php');
            }
        ?>
        </div>
        <div id="frm_add_field_<?php echo $field['id']; ?>">
            <a href="javascript:frm_add_field_option(<?php echo $field['id']; ?>)" class="frm_orange frm_add_opt">+ <?php _e('Add an Option', 'formidable') ?></a>
            
            <?php if (!isset($field['post_field']) or $field['post_field'] != 'post_category'){ ?>
            <?php _e('or', 'formidable'); ?>
            <a title="<?php echo FrmAppHelper::truncate(esc_attr(strip_tags(str_replace('"', '&quot;', $field['name']))), 20) . ' '. __('Field Choices', 'formidable'); ?>" href="<?php echo esc_url(FRM_SCRIPT_URL . '&controller=fields&frm_action=import_choices&field_id='. $field['id'] .'&TB_iframe=1') ?>" class="thickbox frm_orange"><?php _e('Bulk Edit Field Choices', 'formidable') ?></a>
            <?php } ?>
        </div>
<?php } ?>
    </div>
<?php
}else if ($field['type'] == 'captcha'){ 
    global $frm_settings; ?>
    <img src="<?php echo FRM_URL ?>/images/<?php echo $frm_settings->re_theme ?>-captcha.png" alt="captcha" />
    <span class="howto"><?php printf(__('Hint: Change colors in the %1$sFormidable settings', 'formidable'), '<a href="?page=formidable-settings">') ?></a></span>
    <input type="hidden" name="<?php echo $field_name ?>" value="1"/>
<?php 
}else{
    do_action('frm_display_added_fields',$field);
} 

if ($display['clear_on_focus']){ ?>
    <span id="frm_clear_on_focus_<?php echo $field['id'] ?>" class="frm-show-click">
<?php
    FrmFieldsHelper::show_onfocus_js($field['id'], $field['clear_on_focus']);

    if ($display['default_blank'])
        FrmFieldsHelper::show_default_blank_js($field['id'], $field['default_blank']);
    
?>
    </span>
<?php        
    
    do_action('frm_extra_field_display_options', $field);
} 
?>
<div class="clear"></div>
</div>
<?php
if ($display['description']){ ?> 
    <div class="frm_ipe_field_desc description frm-show-click" id="field_<?php echo $field['id']; ?>"><?php echo $field['description']; ?></div> 
<?php
}

if ($display['options']){ ?>
    <div class="widget">
        <div class="widget-top">
    	    <div class="widget-title-action"><a class="widget-action"></a></div>
    		<div class="widget-title"><h4><?php _e('Field Options', 'formidable') ?> (ID <?php echo $field['id'] ?>)</h4></div>
        </div>
    	<div class="widget-inside">
            <table class="form-table">
                <?php if ($display['required']){ ?>
                    <tr>
                        <td><label><?php _e('Required Field', 'formidable') ?></label></td>
                        <td><input type="checkbox" id="frm_req_field_<?php echo $field['id'] ?>" name="field_options[required_<?php echo $field['id'] ?>]" value="1" <?php echo ($field['required']) ? 'checked="checked"': ''; ?> onclick="frm_mark_required(<?php echo $field['id'] ?>,<?php echo $field_required ?>)" /> <span><?php _e('Required', 'formidable') ?></span>
                        <span class="frm_required_details<?php echo $field['id'] ?>" <?php if(!$field['required']) echo 'style="display:none;"'?>>&mdash; <span class="howto"><?php _e('Indicate required field with', 'formidable') ?></span>
                            <input type="text" name="field_options[required_indicator_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['required_indicator']); ?>" />
                        </span>
                        </td>
                    </tr>
                    <tr class="frm_required_details<?php echo $field['id'] ?>"<?php if(!$field['required']) echo 'style="display:none;"'?>><td><label><?php _e('Error message for blank required field', 'formidable') ?></label></td>  
                        <td><input type="text" name="field_options[blank_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['blank']); ?>" class="frm_long_input" /></td>
                    </tr>
                <?php } ?>
                <?php if ($display['invalid']){ ?>
                    <tr><td><label><?php _e('Error message for invalid submission', 'formidable') ?></label></td>  
                        <td><input type="text" name="field_options[invalid_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['invalid']); ?>" class="frm_long_input" /></td>
                    </tr>
                <?php } ?>
                <?php if ($display['size']){ ?>
                    <tr><td width="150px"><label><?php _e('Field Size', 'formidable') ?></label></td>
                        <td>
                        <?php if($field['type'] == 'select' or $field['type'] == 'time'){ ?>
                            <?php if(!isset($values['custom_style']) or $values['custom_style']){ ?>
                                <input type="checkbox" name="field_options[size_<?php echo $field['id'] ?>]" value="1" <?php echo (isset($field['size']) and $field['size'])? 'checked="checked"':''; ?> /> <span class="howto"><?php _e('automatic width', 'formidable') ?></span>
                            <?php }
                            }else{ ?>
                                <input type="text" name="field_options[size_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['size']); ?>" size="5" /> <span class="howto"><?php echo ($field['type'] == 'textarea' || $field['type'] == 'rte')? __('columns wide', 'formidable') : __('characters wide', 'formidable') ?></span>

                                <input type="text" name="field_options[max_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['max']); ?>" size="5" /> <span class="howto"><?php echo ($field['type'] == 'textarea' || $field['type'] == 'rte')? __('rows high', 'formidable') : __('characters maximum', 'formidable') ?></span>
                        <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if ($display['label_position']){ ?>
                    <tr><td width="150px"><label><?php _e('Label Position', 'formidable') ?></label></td>
                        <td><select name="field_options[label_<?php echo $field['id'] ?>]">
                            <option value=""<?php selected($field['label'], ''); ?>><?php _e('Default', 'formidable') ?></option>
                            <option value="top"<?php selected($field['label'], 'top'); ?>><?php _e('Top', 'formidable') ?></option>
                            <option value="left"<?php selected($field['label'], 'left'); ?>><?php _e('Left', 'formidable') ?></option>
                            <option value="right"<?php selected($field['label'], 'right'); ?>><?php _e('Right', 'formidable') ?></option>
                            <option value="inline"<?php selected($field['label'], 'inline'); ?>><?php _e('Inline (left without a set width)', 'formidable') ?></option>
                            <option value="none"<?php selected($field['label'], 'none'); ?>><?php _e('None', 'formidable') ?></option>
                            <option value="hidden"<?php selected($field['label'], 'hidden'); ?>><?php _e('Hidden (but leave the space)', 'formidable') ?></option>
                        </select>
                        </td>  
                    </tr>
                <?php } ?>
                <?php if ($display['css']){ ?>
                <tr><td><label><?php _e('CSS layout classes', 'formidable') ?></label> 
                    <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Add a CSS class to the field container. Use our predefined classes to align multiple fields in single row.', 'formidable') ?>" /></td>
                    <td><input type="text" name="field_options[classes_<?php echo $field['id'] ?>]" value="<?php echo esc_attr($field['classes']) ?>" class="frm_long_input" />
                    </td>  
                </tr>
                <?php } ?>
                <?php do_action('frm_field_options_form', $field, $display, $values); ?>
            </table>
        </div>
    </div>
<?php } ?>         
</li>
<?php unset($display); ?>
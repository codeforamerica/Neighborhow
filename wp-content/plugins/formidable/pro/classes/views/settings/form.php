<tr class="form-field">
    <td></td>
    <td>        
        <?php _e('Previously Submitted Message', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('The message seen when a user attempts to submit a form for a second time if submissions are limited.', 'formidable') ?>" /><br/>
        <input type="text" id="frm_already_submitted" name="frm_already_submitted" class="frm_long_input" value="<?php echo esc_attr(stripslashes($frmpro_settings->already_submitted)) ?>" />
    </td>
</tr>

<tr>
    <td><?php _e('Include these scripts', 'formidable') ?></td>
    <td>    
        <input type="checkbox" value="1" id="frm_accordion_js" name="frm_accordion_js" <?php checked($frm_settings->accordion_js, 1) ?> />
        <?php _e('Include accordion javascript', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('If you have manually created an accordion form, be sure to include the javascript for it.', 'formidable') ?>" />
    </td>
</tr>

<tr>
    <td valign="top"><?php _e('Keys', 'formidable'); ?> </td>
    <td valign="top">
        <input type="checkbox" value="1" id="frm_lock_keys" name="frm_lock_keys" <?php checked($frm_settings->lock_keys, 1) ?> />
        <?php _e("Hide field and entry keys to prevent them from being edited. Uncheck this box to edit the saved keys for use in your template.", 'formidable'); ?>
    </td>
</tr>
<!--
<tr>
    <td valign="top"><?php _e('Visual Text Editor', 'formidable'); ?> </td>
    <td valign="top">
        <input type="checkbox" value="1" id="frm_rte_off" name="frm_rte_off" <?php checked($frmpro_settings->rte_off, 1) ?> />
        <?php _e('Turn off the visual editor when building custom displays.', 'formidable'); ?>
    </td>
</tr>
-->
<tr class="form-field">
    <td valign="top"><?php _e('Path to Extra Templates', 'formidable') ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php printf(__('If you would like to use any extra templates that are not included in Formidable, define the absolute path here. For example, the absolute path to the Formidable template folder is %1$s.', 'formidable'), FRM_TEMPLATES_PATH) ?>" /></td>
    <td valign="top">
        <input type="text" value="<?php echo stripslashes($frmpro_settings->template_path) ?>" id="frm_template_path" name="frm_template_path" size="70" />
        <?php if($frmpro_settings->template_path != ''){ 
            if(!path_is_absolute($frmpro_settings->template_path)){
                _e('The format of that path is incorrect. Please try again.', 'formidable');
            }else{ ?>
        <a href="javascript:frm_import_templates('frm_import_now')" id="frm_import_now" title="<?php _e('Update Imported Templates Now', 'formidable') ?>"><?php _e('Update Imported Templates Now', 'formidable') ?></a>
        <?php } 
            }
        ?>
        <p class="description"><?php _e('Example', 'formidable') ?>: <?php echo FRM_TEMPLATES_PATH ?></p>
    </td>
</tr>

<tr class="form-field">
    <td valign="top"><?php _e('Date Format', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('Change the format of the date used in the date field.', 'formidable') ?>" /></td>
    <td valign="top">
        <?php $formats = array('m/d/Y', 'd/m/Y', 'd.m.Y', 'j-m-Y', 'j/m/y', 'Y/m/d', 'Y-m-d'); ?>
        <select name="frm_date_format">
            <?php foreach($formats as $f){ ?>
            <option value="<?php echo esc_attr($f) ?>" <?php selected($frmpro_settings->date_format, $f); ?>><?php echo date($f); ?></option>
            <?php } ?>
        </select>

    </td>
</tr>

<tr class="form-field">
    <td valign="top"><?php _e('CSV Export Format', 'formidable'); ?> <img src="<?php echo FRM_IMAGES_URL ?>/tooltip.png" alt="?" class="frm_help" title="<?php _e('If your CSV special characters are not working correctly, try a different formatting option.', 'formidable') ?>" /></td>
    <td valign="top">
        <select name="frm_csv_format">
            <option value="UTF-8" <?php selected($frmpro_settings->csv_format, 'UTF-8') ?>>UTF-8</option>
            <option value="ISO-8859-1" <?php selected($frmpro_settings->csv_format, 'ISO-8859-1'); ?>>ISO-8859-1</option>
            <option value="windows-1256" <?php selected($frmpro_settings->csv_format, 'windows-1256'); ?>>windows-1256</option>
            <option value="windows-1251" <?php selected($frmpro_settings->csv_format, 'windows-1251'); ?>>windows-1251</option>
            <option value="macintosh" <?php selected($frmpro_settings->csv_format, 'macintosh'); ?>><?php _e('Macintosh', 'formidable') ?></option>
        </select>

    </td>
</tr>

<!--
<tr>
    <td valign="top"><?php _e('Pretty Permalinks', 'formidable'); ?></td>
    <td valign="top">
        <input type="checkbox" value="1" id="frm_permalinks" name="frm_permalinks" <?php //checked($frmpro_settings->permalinks, 1) ?>>
        <?php _e('Use pretty permalinks for entry detail links', 'formidable'); ?>
        <p class="description">If displaying your data on your site, would you like your permalinks to be pretty? <small>NOTE: This will not work if you are using the WordPress default permalinks.</small></p>
    </td>
</tr> -->
<input type="hidden" id="frm_permalinks" name="frm_permalinks" value="0" />
</table>
</div>

<div class="styling_settings tabs-panel" style="display:none;">
<table class="form-table">
    <tr class="form-field">
        <td width="250px" valign="top">
            <?php include(FRMPRO_VIEWS_PATH .'/settings/formroller.php'); ?>
        </td>
        <td class="frm_sample_form">
            <div class="frm_forms with_frm_style">
            <div class="frm_form_fields">
            <fieldset>
              
            <div class="frm_error_style"> 
                <img src="<?php echo FRMPRO_ICONS_URL .'/'. $frmpro_settings->error_icon; ?>" alt="" />
                <?php echo __('SAMPLE:', 'formidable') .' '. stripslashes($frm_settings->invalid_msg) ?>
            </div>
            
            <div id="message" class="frm_message"><?php echo __('SAMPLE:', 'formidable') .' '. stripslashes($frm_settings->success_msg) ?></div>
            <?php $pos_class = ($frmpro_settings->position == 'none') ? 'frm_top_container' : 'frm_'. $frmpro_settings->position .'_container' ?>
            <div class="frm_form_field frm_left_half form-field <?php echo $pos_class ?>">
            <label class="frm_primary_label"><?php _e('Text field', 'formidable') ?> <span class="frm_required">*</span></label>   
            <input type="text" value="<?php _e('This is sample text', 'formidable') ?>"/>
            <div class="frm_description"><?php _e('A field with a description', 'formidable') ?></div>
            </div>
            
            <div class="frm_form_field frm_right_half form-field frm_focus_field <?php echo $pos_class ?>">
            <label class="frm_primary_label"><?php _e('Text field in active state', 'formidable') ?> <span class="frm_required">*</span></label>   
            <input type="text" value="<?php _e('The active state will be seen when the field is clicked', 'formidable') ?>" />
            </div>
            
            <div class="frm_form_field form-field frm_blank_field <?php echo $pos_class ?>">
            <label class="frm_primary_label"><?php _e('Text field with error', 'formidable') ?> <span class="frm_required">*</span></label>   
            <input type="text" value="<?php _e('This is sample text', 'formidable') ?>"/>
            <div class="frm_error"><?php _e('This field cannot be blank', 'formidable') ?></div>
            </div>
            
            <div class="frm_form_field form-field <?php echo $pos_class ?>">
            <label class="frm_primary_label"><?php _e('File Upload', 'formidable') ?></label>   
            <input type="file"/>
            </div>

            <div class="frm_form_field form-field <?php echo $pos_class ?>">
            <label class="frm_primary_label"><?php _e('Drop-down Select', 'formidable') ?></label>   
            <select>
                <option value=""></option>
                <option value=""><?php _e('An Option', 'formidable') ?></option>
            </select>
            </div>
            
            <div class="frm_form_field frm_left_half <?php echo $pos_class ?>">
                <label class="frm_primary_label"><?php _e('Radio Buttons', 'formidable') ?></label>
                <div class="frm_radio"><input type="radio" /><label><?php _e('Option 1', 'formidable') ?></label></div>
                <div class="frm_radio"><input type="radio" /><label><?php _e('Option 2', 'formidable') ?></label></div>
            </div>
            
            <div class="frm_form_field frm_right_half <?php echo $pos_class ?>">
                <label class="frm_primary_label"><?php _e('Check Boxes', 'formidable') ?></label>
                <div class="frm_checkbox"><input type="checkbox" /><label><?php _e('Option 1', 'formidable') ?></label></div>
                <div class="frm_checkbox"><input type="checkbox" /><label><?php _e('Option 2', 'formidable') ?></label></div>
            </div>
            
            <div class="frm_form_field form-field <?php echo $pos_class ?>">
                <label class="frm_primary_label"><?php _e('Text Area', 'formidable') ?></label>   
                <textarea></textarea>
                <div class="frm_description"><?php _e('Another field with a description', 'formidable') ?></div>
            </div>
            
            <div class="frm_form_field form-field <?php echo $pos_class ?>">
                <label class="frm_primary_label"><?php _e('Rich Text Area', 'formidable') ?></label>   
                <textarea name="rte" id="rte"></textarea>
                <?php $wp_scripts->do_items( 'nicedit' ); ?>
                <script type="text/javascript">bkLib.onDomLoaded(function(){new nicEditor({fullPanel:true,iconsPath:'<?php echo FRMPRO_IMAGES_URL ?>/nicEditIcons.gif'}).panelInstance("rte",{hasPanel:true});});</script>
            </div>
            
            <div id="datepicker_sample" style="margin-bottom:<?php echo $frmpro_settings->field_margin ?>;"></div>
            
            </fieldset>
            </div>
            
            <p class="submit">
            <input type="submit" disabled="disabled" value="Submit" />
            </p>
            </div>
        </td>    
    </tr> 
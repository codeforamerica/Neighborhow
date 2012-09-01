<div id="frm_postmeta_<?php echo $custom_data['meta_name'] ?>" class="frm_postmeta_row">
    <span><a href="javascript:frm_remove_tag('#frm_postmeta_<?php echo $custom_data['meta_name'] ?>');"> X </a></span>
    &nbsp;
    <?php _e('Add Custom Field', 'formidable') ?>
    <input type="text" value="<?php echo ($echo) ? esc_attr($custom_data['meta_name']) : '' ?>" name="options[post_custom_fields][<?php echo $custom_data['meta_name'] ?>][meta_name]"/>
    <?php _e('from', 'formidable') ?>
    <select name="options[post_custom_fields][<?php echo $custom_data['meta_name'] ?>][field_id]">
        <option value="">- <?php echo _e('Select Field', 'formidable') ?> -</option>
        <?php 
        if(!empty($values['fields'])){
            if(!isset($custom_data['field_id']))
                $custom_data['field_id'] = '';
                
        foreach($values['fields'] as $fo){
            $fo = (array)$fo;
            if(!in_array($fo['type'], array('divider', 'html', 'break', 'captcha'))){ ?>
        <option value="<?php echo $fo['id'] ?>" <?php selected($custom_data['field_id'], $fo['id']) ?>><?php echo FrmAppHelper::truncate($fo['name'], 80) ?></option>
        <?php
            }
            unset($fo);
        }
        } ?>
    </select>    
</div>
<div id="frm_usermeta_<?php echo $meta_name ?>" class="frm_usermeta_row">
    <span><a href="javascript:frm_remove_tag('#frm_usermeta_<?php echo $meta_name ?>');"> X </a></span>
    &nbsp;
    <?php _e('Add User Meta', 'formidable') ?>:
    <input type="text" value="<?php echo ($echo) ? esc_attr($meta_name) : '' ?>" name="options[reg_usermeta][meta_name][<?php echo $meta_name ?>]"/>
    <?php _e('from', 'formidable') ?>
    <select name="options[reg_usermeta][field_id][<?php echo $meta_name ?>]">
        <option value="">- <?php echo _e('Select Field', 'formidable') ?> -</option>
        <?php 
        if(isset($fields) and is_array($fields)){
            foreach($fields as $field){ ?>
            <option value="<?php echo $field->id ?>" <?php selected($field_id, $field->id) ?>><?php echo substr(esc_attr(stripslashes($field->name)), 0, 80);
            unset($field); 
            ?></option>
            <?php
            }
        }
        ?>
    </select> 
    
</div>
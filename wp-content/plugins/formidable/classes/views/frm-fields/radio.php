<?php
if (is_array($field['options'])){
    foreach($field['options'] as $opt_key => $opt){
        $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $field);
        $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $field);
        $checked = (isset($field['value']) and ((!is_array($field['value']) && $field['value'] == $field_val ) || (is_array($field['value']) && in_array($field_val, $field['value'])))) ? ' checked="true"':'';
        require(FRM_VIEWS_PATH .'/frm-fields/single-option.php');
        
        unset($checked);
    }  
}
?>
<?php
if (in_array($field['type'], array('tag', 'date'))){ ?>
<input type="text" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['value']) ?>" <?php do_action('frm_field_input_html', $field) ?>/>
<?php 
if ($field['type'] == 'date' and (!isset($field['read_only']) or !$field['read_only'])){ 
global $frm_datepicker_loaded;
if(!is_array($frm_datepicker_loaded)) $frm_datepicker_loaded = array();
$frm_datepicker_loaded['field_'. $field['field_key']] = array(
    'start_year' => $field['start_year'], 'end_year' => $field['end_year'], 
    'locale' => $field['locale'], 'unique' => $field['unique'], 'entry_id' => $entry_id,
    'field_id' => $field['id']
);
}
}else if($field['type'] == 'time'){ ?>
<select name="<?php echo $field_name ?>" id="field_<?php echo $field['field_key'] ?>" <?php do_action('frm_field_input_html', $field) ?>>
    <option value=""></option>
    <?php if(!empty($field['value'])){ ?>
    <option value="<?php echo esc_attr($field['value']) ?>" selected="selected"><?php echo esc_attr($field['value']) ?></option>
    <?php } ?>
</select>
<?php    
global $frm_timepicker_loaded;
$frm_timepicker_loaded['field_'. $field['field_key']] = array(
    'clock' => ((isset($field['clock']) and $field['clock'] == 24) ? true : false),  //show24hours
    'step' => $field['step'], 'start_time' => $field['start_time'], 'end_time' => $field['end_time'],
    'unique' => $field['unique'], 'entry_id' => $entry_id
);
}else if(in_array($field['type'], array('email', 'url', 'number', 'password', 'phone'))){ 
    $field['type'] = ($field['type'] == 'phone') ? 'tel' : $field['type']; ?>
<input type="<?php echo ($frm_settings->use_html or $field['type'] == 'password') ? $field['type'] : 'text'; ?>" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['value']) ?>" <?php do_action('frm_field_input_html', $field) ?>/>
<?php
$field['type'] = ($field['type'] == 'tel') ? 'phone' : $field['type'];
}else if ($field['type'] == 'image'){?>
<input type="url" id="field_<?php echo $field['field_key'] ?>" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['value']) ?>" <?php do_action('frm_field_input_html', $field) ?>/>
<?php if ($field['value']){ ?><img src="<?php echo $field['value'] ?>" height="50px" /><?php }
    
}else if ($field['type'] == '10radio' or $field['type'] == 'scale'){
    require(FRMPRO_VIEWS_PATH .'/frmpro-fields/10radio.php');
    if(isset($field['star']) and $field['star']){
        global $frm_star_loaded;
        if(!is_array($frm_star_loaded))
            $frm_star_loaded = array(true);
    }
}else if ($field['type'] == 'rte' && is_admin()){ ?>
<div id="<?php echo (user_can_richedit()) ? 'postdivrich' : 'postdiv'; ?>" class="postarea frm_full_rte">
<?php 
if(function_exists('wp_editor'))
    wp_editor(str_replace('&quot;', '"', $field['value']), $field_name, array('dfw' => true) );
else
    the_editor(str_replace('&quot;', '"', $field['value']), $field_name, 'title', false); ?>
</div>      
<?php

}else if ($field['type'] == 'rte'){ 
    global $frm_ajax_edit;
    
    if(function_exists('wp_editor') and !$frm_ajax_edit and isset($field['rte']) and $field['rte'] == 'mce'){
        $e_args = array('media_buttons' => false, 'textarea_name' => $field_name);
        if($field['max'])
            $e_args['textarea_rows'] = $field['max'];
        if($field['size']){ ?>
<style type="text/css">#wp-field_<?php echo $field['field_key'] ?>-wrap{width:<?php echo (int)((int)$field['size'] * 8.6) ?>px;}</style><?php
        }
        
        wp_editor(str_replace('&quot;', '"', $field['value']), 'field_'. $field['field_key'] . ($frm_ajax_edit ? $frm_ajax_edit : '' ),  $e_args);
        unset($e_args);
    }else{ ?>
<textarea name="<?php echo $field_name ?>" id="field_<?php echo $field['field_key'] ?>" <?php if ($field['size']){ ?>cols="<?php echo $field['size'] ?>"<?php } ?> style="height:<?php echo ($field['max']) ? ((int)$field['max'] * 17) : 125 ?>px;<?php if (!$field['size']){ ?>width:<?php echo $frmpro_settings->field_width; } ?>" <?php do_action('frm_field_input_html', $field) ?>><?php echo FrmAppHelper::esc_textarea($field['value']) ?></textarea>
<?php global $frm_mobile; 
if($frm_mobile or FrmProFieldsHelper::mobile_check()){ }else{ 
    global $frm_rte_loaded;
    $frm_rte_loaded[] = 'field_'. $field['field_key'];
}
    }
}else if ($field['type'] == 'file'){ ?>
<input type="file" name="file<?php echo $field['id'] ?>" id="field_<?php echo $field['field_key'] ?>" <?php do_action('frm_field_input_html', $field) ?>/><br/>
<input type="hidden" name="<?php echo $field_name ?>" value="<?php echo esc_attr($field['value']) ?>" />
<?php echo FrmProFieldsHelper::get_file_icon($field['value']);
include_once(FRMPRO_VIEWS_PATH .'/frmpro-entries/loading.php');

}else if ($field['type'] == 'data'){ ?>
<div id="frm_data_field_<?php echo $field['id'] ?>_container">
<?php require(FRMPRO_VIEWS_PATH .'/frmpro-fields/data-options.php'); ?>
</div>
<?php

}else if($field['type'] == 'form'){
    echo 'FRONT FORM';
} ?>
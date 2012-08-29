<?php

class FrmFormsHelper{
    function get_direct_link($key, $prli_link_id=false){
        global $frm_siteurl;
        $target_url = esc_url($frm_siteurl . '/index.php?plugin=formidable&controller=forms&frm_action=preview&form='.$key);
        if ($prli_link_id && class_exists('PrliLink')){
            $prli = prli_get_pretty_link_url($prli_link_id);
            if ($prli) $target_url = $prli;
        }
        return $target_url;
    }
    
    function get_template_dropdown($templates){ ?>
        <select id="select_form" name="select_form" onChange="frmAddNewForm(this.value,'duplicate')">
            <option value="">- <?php _e('Create Form from Template', 'formidable') ?> -</option>
            <?php foreach ($templates as $temp){ ?>
                <option value="<?php echo $temp->id ?>"><?php echo FrmAppHelper::truncate($temp->name, 40) ?></option>
            <?php }?>
        </select> 
    <?php    
    }
    
    function forms_dropdown( $field_name, $field_value='', $blank=true, $field_id=false, $onchange=false ){
        global $frm_form;
        if (!$field_id)
            $field_id = $field_name;
        
        $where = apply_filters('frm_forms_dropdown', "is_template=0 AND (status is NULL OR status = '' OR status = 'published')", $field_name);
        $forms = $frm_form->getAll($where, ' ORDER BY name');
        ?>
        <select name="<?php echo $field_name; ?>" id="<?php echo $field_id ?>" class="frm-dropdown" <?php if ($onchange) echo 'onchange="'.$onchange.'"'; ?>>
            <?php if ($blank){ ?>
            <option value=""><?php echo ($blank == 1) ? '' : '- '. $blank .' -'; ?></option>
            <?php } ?>
            <?php foreach($forms as $form){ ?>
                <option value="<?php echo $form->id; ?>" <?php selected($field_value, $form->id); ?>><?php echo FrmAppHelper::truncate($form->name, 33); ?></option>
            <?php } ?>
        </select>
        <?php
    }
    
    function get_sortable_classes($col, $sort_col, $sort_dir){
        echo ($sort_col == $col) ? 'sorted' : 'sortable'; 
        echo ($sort_col == $col and $sort_dir == 'desc') ? ' asc' : ' desc';
    }
    
    function setup_new_vars(){
        global $frmdb, $frm_settings;
        $values = array();
        foreach (array('name' => __('Untitled Form', 'formidable'), 'description' => '') as $var => $default)
            $values[$var] = stripslashes(FrmAppHelper::get_param($var, $default));
        
        if(apply_filters('frm_use_wpautop', true))
            $values['description'] = wpautop($values['description']);
        
        foreach (array('form_id' => '', 'logged_in' => '', 'editable' => '', 'default_template' => 0, 'is_template' => 0) as $var => $default)
            $values[$var] = stripslashes(FrmAppHelper::get_param($var, $default));
            
        $values['form_key'] = ($_POST and isset($_POST['form_key'])) ? $_POST['form_key'] : (FrmAppHelper::get_unique_key('', $frmdb->forms, 'form_key'));
        
        $defaults = FrmFormsHelper::get_default_opts();
        foreach ($defaults as $var => $default)
            $values[$var] = ($_POST and isset($_POST['options'][$var])) ? $_POST['options'][$var] : $default;
            
        $values['custom_style'] = ($_POST and isset($_POST['options']['custom_style'])) ? $_POST['options']['custom_style'] : ($frm_settings->load_style != 'none');
        $values['before_html'] = FrmFormsHelper::get_default_html('before');
        $values['after_html'] = FrmFormsHelper::get_default_html('after');
        
        return apply_filters('frm_setup_new_form_vars', $values);
    }
    
    function setup_edit_vars($values, $record){
        global $frm_form;

        $values['form_key'] = FrmAppHelper::get_param('form_key', $record->form_key);
        $values['default_template'] = FrmAppHelper::get_param('default_template', $record->default_template);
        $values['is_template'] = FrmAppHelper::get_param('is_template', $record->is_template);

        return apply_filters('frm_setup_edit_form_vars', $values);
    }
    
    function get_default_opts(){
        global $frm_settings;
        
        return array(
            'email_to' => $frm_settings->email_to, 'reply_to' => '', 'reply_to_name' => '',
            'submit_value' => $frm_settings->submit_value, 'success_action' => 'message',
            'success_msg' => $frm_settings->success_msg, 'show_form' => 0, 'akismet' => '',
            'no_save' => 0
        );
    }
    
    function get_default_html($loc){
        if ($loc == 'before'){
            $default_html = <<<BEFORE_HTML
[if form_name]<h3>[form_name]</h3>[/if form_name]
[if form_description]<div class="frm_description">[form_description]</div>[/if form_description]
BEFORE_HTML;
        }else{
            $default_html = '';
        }
        return $default_html;
    }
    
    function replace_shortcodes($html, $form, $title=false, $description=false){
        foreach (array('form_name' => $title, 'form_description' => $description, 'entry_key' => true) as $code => $show){
            if ($code == 'form_name'){
                $replace_with = stripslashes($form->name);
            }else if ($code == 'form_description'){
                if(apply_filters('frm_use_wpautop', true))
                    $replace_with = wpautop(stripslashes($form->description));
                else
                    $replace_with = stripslashes($form->description);
            }else if($code == 'entry_key' and isset($_GET) and isset($_GET['entry'])){
                $replace_with = $_GET['entry'];
            }
                
            if (($show == true || $show == 'true') && $replace_with != '' ){
                $html = str_replace('[if '.$code.']', '', $html); 
        	    $html = str_replace('[/if '.$code.']', '', $html);
            }else{
                $html = preg_replace('/(\[if\s+'.$code.'\])(.*?)(\[\/if\s+'.$code.'\])/mis', '', $html);
            }
            $html = str_replace('['.$code.']', $replace_with, $html);   
        }   
        
        //replace [form_key]
        $html = str_replace('[form_key]', $form->form_key, $html);
        
        if(class_exists('FrmProEntriesController'))
            $html = str_replace('[deletelink]', FrmProEntriesController::entry_delete_link(array()), $html);
        
        return apply_filters('frm_form_replace_shortcodes', stripslashes($html), $form);
    }

}
?>
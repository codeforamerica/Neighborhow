<?php

class FrmProDisplaysHelper{
    function FrmProDisplaysHelper(){}
    
    function setup_new_vars(){
        $values = array();
        $defaults = FrmProDisplaysHelper::get_default_opts();
        foreach ($defaults as $var => $default)
            $values[$var] = stripslashes_deep(FrmAppHelper::get_param($var, $default));
        
        return $values;
    }
    
    function setup_edit_vars($record){
        if(!$record) return false;
        
        $values = array();
        $values['id'] = $record->id;
        
        foreach (array('name', 'description', 'display_key', 'form_id', 'entry_id', 'post_id', 'content', 'dyncontent', 'param', 'type', 'show_count', 'insert_loc') as $var)
              $values[$var] = stripslashes(FrmAppHelper::get_param($var, $record->$var));
        
        $options = maybe_unserialize($record->options);
        foreach (FrmProDisplaysHelper::get_default_opts() as $var => $default){
            if(!isset($values[$var]))
                $values[$var] = stripslashes_deep(FrmAppHelper::get_post_param('options['.$var.']', (isset($options[$var])) ? $options[$var] : $default));
        }
        
        return $values;
    }
    
    function get_default_opts(){
        
        return array(
            'name' => '', 'description' => '', 'display_key' => '', 
            'form_id' => '', 'date_field_id' => '', 'edate_field_id' => '', 'entry_id' => '', 
            'post_id' => '', 'before_content' => '', 'content' => '', 
            'after_content' => '', 'dyncontent' => '', 'param' => '', 
            'type' => '', 'show_count' => 'all', 'insert_loc' => 'none', 
            'insert_pos' => 1,
            'order_by' => '', 'order' => '', 'limit' => '', 'page_size' => '', 
            'empty_msg' => __('No Entries Found', 'formidable'), 'copy' => 0, 
            'where' => array(), 'where_is' => array(), 'where_val' => array()
        );
    }

    function get_shortcodes($content, $form_id) {
        global $frm_field;
        
        $form_ids = array($form_id);
        //get linked form ids
        /*$data_fields = $frm_field->getAll(array('fi.type' => 'data', 'fi.form_id' => $form_id));
        if($data_fields){
            foreach($data_fields as $data_field){
                $data_field->field_options = maybe_unserialize($data_field->field_options);
                $linked_field = $frm_field->getOne($data_field->field_options['form_select']);
                if($linked_field)
                    $form_ids[] = $linked_field->form_id;
                unset($data_field);
                unset($linked_field);
            }
        }*/
        
        $fields = $frm_field->getAll("fi.type not in ('divider','captcha','break','html') and fi.form_id in (".implode(',',$form_ids) .')');
        
        $tagregexp = 'editlink|deletelink|detaillink|id|post-id|post_id|key|ip|created-at|updated-at|evenodd|get|siteurl|sitename';
        foreach ($fields as $field)
            $tagregexp .= '|'. $field->id . '|'. $field->field_key;

        preg_match_all("/\[(if )?($tagregexp)\b(.*?)(?:(\/))?\](?:(.+?)\[\/\2\])?/s", $content, $matches, PREG_PATTERN_ORDER);

        return $matches;
    }
}
?>
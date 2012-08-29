<?php

header('Content-Description: File Transfer');
header("Content-Disposition: attachment; filename=\"$filename\"");
header('Content-Type: text/csv; charset=' . $charset, true);
header('Expires: '. gmdate("D, d M Y H:i:s", mktime(date('H')+2, date('i'), date('s'), date('m'), date('d'), date('Y'))) .' GMT');
header('Last-Modified: '. gmdate('D, d M Y H:i:s') .' GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
   
foreach ($form_cols as $col)
    echo '"'. FrmProEntriesHelper::encode_value(strip_tags($col->name), $charset, $to_encoding) .'",';
        
echo '"'. __('Timestamp', 'formidable') .'","IP","ID","Key"'."\n";
    
foreach($entries as $entry){
    foreach ($form_cols as $col){
        $field_value = isset($entry->metas[$col->id]) ? $entry->metas[$col->id] : false;
        
        if(!$field_value and $entry->post_id){
            $col->field_options = maybe_unserialize($col->field_options);
            if(isset($col->field_options['post_field']) and $col->field_options['post_field']){
                $field_value = FrmProEntryMetaHelper::get_post_value($entry->post_id, $col->field_options['post_field'], $col->field_options['custom_field'], 
                array(
                    'truncate' => (($col->field_options['post_field'] == 'post_category') ? true : false), 
                    'form_id' => $entry->form_id, 'field' => $col, 'type' => $col->type, 
                    'exclude_cat' => (isset($col->field_options['exclude_cat']) ? $col->field_options['exclude_cat'] : 0)
                ));
            }
        }
          
        if ($col->type == 'user_id'){
            $field_value = FrmProFieldsHelper::get_display_name($field_value);
        }else if ($col->type == 'file'){
            $field_value = FrmProFieldsHelper::get_file_name($field_value);
        }else if ($col->type == 'date'){
            $field_value = FrmProFieldsHelper::get_date($field_value, $wp_date_format);
        }else if ($col->type == 'data' && is_numeric($field_value)){
            $field_value = FrmProFieldsHelper::get_data_value($field_value, $col); //replace entry id with specified field
        }else{
            $checked_values = maybe_unserialize($field_value);
            $checked_values = apply_filters('frm_csv_value', $checked_values, array('field' => $col));
            
            if (is_array($checked_values)){
                if ($col->type == 'data'){
                    $field_value = '';
                    foreach($checked_values as $checked_value){
                        if(!empty($field_value))
                            $field_value .= ', ';
                        $field_value .= FrmProFieldsHelper::get_data_value($checked_value, $col);
                    }
                }else{
                    $field_value = implode(', ', $checked_values);
                }
            }else{
                $field_value = $checked_values;
            }
            
            $field_value = FrmProEntriesHelper::encode_value($field_value, $charset, $to_encoding);
            $field_value = str_replace('"', '""', stripslashes($field_value)); //escape for CSV files. 
        }
        
        $field_value = str_replace(array("\r\n", "\r", "\n"), ' <br/>', $field_value);
        
        echo "\"$field_value\",";
        unset($col);
        unset($field_value);
    }
    $formatted_date = date($wp_date_format, strtotime($entry->created_at));
    echo "\"{$formatted_date}\",";
    echo "\"{$entry->ip}\",";
    echo "\"{$entry->id}\",";
    echo "\"{$entry->item_key}\"\n";
    unset($entry);
    
}

?>
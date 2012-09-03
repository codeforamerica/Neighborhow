<?php

class FrmPlusEntryMetaHelper{
    function FrmPlusEntryMetaHelper(){
        add_filter('frm_display_value_custom', array($this, 'frmplus_display_value_custom'), 10, 3);
        add_filter('frm_email_value', array($this, 'email_value'), 10, 3);
		add_filter('frm_hidden_value',array(&$this,'previous_fields_value'),10,2);
		add_filter('frm_csv_value',array(&$this,'frmplus_csv_value'),10,2);
    }

	function frmplus_display_value_custom($value,$field,$atts){
		switch ($field->type){
		case 'table':
			$value = self::sanitize($value,false); // false means we skip stripping slashes (it's already been done)
			$display_only = true;
			$field->options = maybe_unserialize($field->options);
			$field = (array) $field;
			$field['value'] = $value;
			ob_start();
			require(FRMPLUS_VIEWS_PATH.'/frmplus-fields/table.php');
			$value = ob_get_clean();
			break;
		}		
		return $value;
	}
	
	function email_value($value, $meta, $entry){
		switch($meta->field_type){
		case 'table':
			$value = self::sanitize($value,true); // true means we'll stripslashes
			$display_only = true;
			$field = $meta;
			$field->options = maybe_unserialize($meta->fi_options);
			$field = (array) $field;
			$field['value'] = $value;
			ob_start();
			require(FRMPLUS_VIEWS_PATH.'/frmplus-fields/table.php');
			$value = ob_get_clean();
			break;
		}
		return $value;
	}

	function previous_fields_value($value,$field){
		if ($field['type'] == 'table'){
			$value = stripslashes_deep($value);
			if (is_array($value)){
				$value = serialize($value);	
			}
			$value = esc_attr($value); // turns " into &quot;
		}
		return $value;
	}
	
	function sanitize($value,$strip = false){
		if (is_array($value) and array_key_exists(0,$value) and is_string($value[0])){
			if ($strip){
				$value = stripslashes_deep($value);
			}
			$value = array_map('html_entity_decode',$value);
			$value = array_map('maybe_unserialize',$value);
		}
		return $value;
	}
	
	function fetch($field_id,$entry_id){
		// Because of the way the metas get written to the database
		// I was having big troubles retaining proper " (double quote) ' (apostrophe)
		// and \ (slash).  The regular Formidable way of getting Entry meta
		// performed stripslashes at a place that broke the serialization of 
		// some (admittedly edge case) scenarios where the table contained
		// those characters. This was only a problem when displaying the form
		// for updating.  I call this function from views/frmplus-fields/form-fields.php
		global $frm_entry_meta;
		$value = $frm_entry_meta->get_entry_meta_by_field($entry_id,$field_id,false); // the false skips the stripslashes
		// Backward compatibility pre F+ version 1.1.7 and pre FPro 1.06.03
		if (is_array($value)){
			// The old way
			$value = array_map('maybe_unserialize',$value); // let's unserialize the sucker
			$value = stripslashes_deep($value); // here's where to strip slashes
			if( is_array($value)){
				foreach ($value as $num => $row){
					$value[$num] = array_map('maybe_unserialize',$row);  // now, unserialize all of the rows
				}
				$value = array_shift($value); // I don't know why I have to do this, but the thing I want is actually the first element of the array
			}
		}
		else{
			// The new way
			static $entries;
			if (!isset($entries)){
				$entries = array();
			}
			if (!array_key_exists($entry_id,$entries)){
				global $frm_entry;
				$entries[$entry_id] = $frm_entry->getOne($entry_id,true);
			}
			$entry = $entries[$entry_id];
			$value = maybe_unserialize($entry->metas[$field_id]);
			$value = stripslashes_deep($value); // here's where to strip slashes
			if( is_array($value)){
				foreach ($value as $num => $row){
					$value[$num] = maybe_unserialize($row);  // now, unserialize all of the rows
				}
			}
			return $value;
		}
		return $value;
	}
	
	function frmplus_csv_value($field_value,$args){
		// @TODO
		return $field_value;
	}
}
    
?>
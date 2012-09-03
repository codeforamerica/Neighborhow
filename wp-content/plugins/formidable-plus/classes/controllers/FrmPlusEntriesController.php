<?php

class FrmPlusEntriesController{
    
    function FrmPlusEntriesController(){
        //Shortcodes
		add_filter('frm_validate_field_entry',array($this, 'frmplus_validate_field_entry'),10,3);
    }

   	/** 
	 * Validate entry
	 * - Checks if a table is empty and required 
	 */
	function frmplus_validate_field_entry($errors,$posted_field,$value){
		if ($posted_field->type == 'table' and $posted_field->required == '1'){
			// Check for blank table entry
			$blank = true;
			$value = FrmPlusEntryMetaHelper::sanitize($value,true);
			if (is_array($value)){
				foreach ($value as $row){
					foreach ($row as $column){
						if (!empty($column)){ $blank = false; break; }
					}
					if (!$blank){ break; }
				}
			}
			if ($blank){
	            $field_options = maybe_unserialize($posted_field->field_options);
	            $errors['field'.$posted_field->id] = (!isset($field_options['blank']) or $field_options['blank'] == __('Untitled cannot be blank', FRM_PLUGIN_NAME) or $field_options['blank'] == '') ? ($posted_field->name . ' '. __('can\'t be blank', FRM_PLUGIN_NAME)) : $field_options['blank'];  
			}
		}
		return $errors;
	}
	
}
?>
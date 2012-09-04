<?php

class FrmPlusFieldsHelper{
	
	function FrmPlusFieldsHelper(){
		add_filter('frm_pro_available_fields',array(&$this,'add_plus_fields'));
		add_shortcode('frm_table_display',array(&$this,'frm_table_display')); // Deprecated - only useful in special case of inserting the custom display into a page. 
		add_filter('frmpro_fields_replace_shortcodes',array(&$this,'frmplus_replace_shortcodes'),10,4);
		add_action('frm_entry_form', array(&$this,'render_massage_bookings'),10,3);
		add_action('init', array(&$this,'perform_massage'));
        //add_filter('frm_get_default_value', array($this, 'get_default_value')); // TODO make this work with current version.
	}
	
	/** 
	 * Adds the Table field type to the list of available fields
	 */
	function add_plus_fields($fields){
		$fields['table'] = 'Table';
		return $fields;
	}
    
	/** 
	 * Default values for a new table added to a form
	 */
    function setup_new_vars($field_values){
        if ($field_values['type'] == 'table')
            $field_values['options'] = serialize(array('col_1' => 'Column 1', 'col_2' => 'Column 2', 'row_1' => 'Row 1', 'row_2' => 'Row 2'));
        
        return $field_values;
    }

	/** 
	 * Converts the field_options into an array of Columns and Rows
	 */
	function get_table_options($field_options){
		$columns = array();
		$rows = array();
		if (is_array($field_options)){
			foreach ($field_options as $opt_key => $opt){
				switch(substr($opt_key,0,3)){
				case 'col':
					$columns[$opt_key] = $opt;
					break;
				case 'row':
					$rows[$opt_key] = $opt;
					break;
				}
			}
		}
		return array($columns,$rows);
	}
	
	/** 
	 * Converts Columns and Rows into field_options
	 */
	function set_table_options($field_options,$columns,$rows){
		if (is_array($field_options)){
			foreach ($field_options as $opt_key => $opt){
				if (substr($opt_key,0,3) == 'col' or substr($opt_key,0,3) == 'row'){
					unset($field_options[$opt_key]);
				}
			}
		}
		else{
			$field_options = array();
		}
		foreach ($columns as $opt_key => $opt){
			$field_options[$opt_key] = $opt;
		}
		foreach ($rows as $opt_key => $opt){
			$field_options[$opt_key] = $opt;
		}
		return $field_options;
		
	}
    
	/** 
	 * This will allow a dynamic default value to draw data from another form or even a specific entry for a form
	 * 
	 * USAGE: 
	 * - dynamic default values are entered into the admin area when building the form.  [email] or [date] are common examples
	 * - now, you can enter [form_key.field_key] (example: [my_form.my_field_key])
	 * - you can also user is as [form_key{entry_name}.field_key]
	 * 
	 * Note: this will only work when the user who is submitting the form is logged-in.  
	 */
    function get_default_value($value){
		global $frm_form,$frm_field,$frm_entry_meta,$user_ID;
		if (!is_array($value) and preg_match("/\[([^\.]+)\.([^\]]+)\]/",$value,$matches)){
			// This checks for something of the form [form_key.field_key]
			// It will also allow [form_key{entry_name}.field_key] to get the field_key value from a specific entry
			$_form_key = $matches[1];
			$_field_key = $matches[2];
			if (preg_match("/([^\{]*)\{([^\}]*)\}/",$_form_key,$matches)){
				$_form_key = $matches[1];
				$_entry_name = $matches[2];
			}
			// In case a form uses dynamic values from another form multiple times, we'll do some caching
			if (!is_array($this->cached_forms)){
				$this->cached_forms = array();
				$this->cached_entries = array();
				$this->cached_fields = array();
			}
			if (($form_id = $this->cached_forms[$_form_key]) or is_numeric($form_id = $_form_key) or ($form_id = $frm_form->getIdByKey($_form_key))){
				$this->cached_forms[$_form_key] = $form_id;
				if (is_numeric($_field_key)){
					$field_id = $_field_key;
				}
				else{
					if (!isset($this->cached_fields[$form_id])){
						$this->cached_fields[$form_id] = array();
						$fields = $frm_field->getAll('fr.id = '.$form_id);
						foreach ($fields as $field){
							$this->cached_fields[$form_id][$field->field_key] = $field->id;
						}
					}
					$field_id = $this->cached_fields[$form_id][$_field_key];
				}
				
				if ($field_id){
					// We found the form, now let's see if the current user has filled one out.
					if(!($entry = $this->cached_entries[$form_id])){
						$entries = frmplus_entries_helper::get_entries_with_user_id($user_ID,$form_id);
						if (is_array($entries) and count($entries)){
							// Find the Entry Name 
							if ($_entry_name != ""){
								foreach ($entries as $_entry){
									if ($_entry->name == $_entry_name or (is_numeric($_entry_name) and $_entry->id == $_entry_name)){
										$entry = $_entry;
										break;
									}
								}
							}
							else{
								$entry = current($entries);
							}
						}
					}
					
					if ($entry){
						$entry->values = $frm_entry_meta->get_entry_meta_info($entry->id);
						$this->cached_entries[$form_id] = $entry;
						foreach ($entry->values as $entry_meta){
							if ($entry_meta->meta_key == $_field_key){
								return stripslashes($entry_meta->meta_value);
							}
						}
					}
					// Form was found, but user hasn't filled it out yet.  Just return blank
					return '';
				}
			}
		}
		return $value;
	}
	
	// Kept in for legacy, but really, this is handled by frmplus_replace_shortcodes below
	function frm_table_display($atts){
		global $post,$frmpro_display,$frm_entry,$frm_entry_meta,$frm_field;
		if (!isset($atts['id']) and !isset($atts['key'])){
			$replace_with = "Proper usage: [frm_table_display id=N] (replace N with the field ID) or [frm_table_display key=K] (replace K with the field KEY)";
		}
		else{
	        $display = $frmpro_display->getAll("insert_loc != 'none' and post_id=".$post->ID, '', ' LIMIT 1');
	        if (is_numeric($display->entry_id) && $display->entry_id > 0 and !$entry_id)
	            $entry_id = $display->entry_id;

	        $get_param = (isset($_GET[$display->param])) ? $_GET[$display->param] : ((isset($_GET['entry'])) ? $_GET['entry'] : $entry_id);
	        if ($get_param){
	            $where_entry = (is_numeric($get_param)) ? "it.id" : "it.item_key";
	            $where_entry .= "='{$get_param}' and it.form_id=". $display->form_id;
	            $entry = $frm_entry->getAll($where_entry, '', ' LIMIT 1');
	            if($entry)
	                $entry = $entry[0];
	        }
	        if ($entry and $entry->form_id == $display->form_id){
	            $field = $frm_field->getOne( (isset($atts['id']) ? $atts['id'] : $atts['key'] ));
				if ($field){
		            $value = maybe_unserialize($frm_entry_meta->get_entry_meta_by_field($entry->id, $field->id, true));
				}
				if (is_array($value)){
					$replace_with = FrmPlusEntryMetaHelper::frmplus_display_value_custom($value,$field,array());
				}
			}
		}
		return $replace_with;
	}
	
	function frmplus_replace_shortcodes($replace_with,$tag,$atts,$field){
		if ($field and $field->type == 'table'){
			$replace_with = FrmPlusEntryMetaHelper::frmplus_display_value_custom($replace_with,$field,array());
		}
		return $replace_with;
	}
	
	function parse_option($opt,$return = ''){
		if ($opt === null){
			if ($return == ''){
				return array(null,null,null);
			}
			else{
				return null;
			}
		}
		list($valid_types,$types_with_options) = FrmPlusFieldsHelper::get_types();
		
		if (preg_match('/^('.implode('|',$valid_types).'):(.*)$/',$opt,$matches)){
			$type = $matches[1];
			if ($return == 'type') return $type; // no need to carry on.... performance.  
			if (in_array($type,$types_with_options)){
				unset($options_matches);
				preg_match('/^([^\:]*)\:?(.*)$/',$matches[2],$options_matches);
				$name = $options_matches[1];
				if ($options_matches[2]){
					$options = explode('|',$options_matches[2]);
				}
				else{
					$options = array();
				}
			}
			else{
				$name = $matches[2];
				$options = array();
			}			
		}
		else{
			$name = $opt;
			$type = 'text';
			$options = array();
		}
		
		$options = array_map('trim',$options);

		switch($return){
		case 'type':
			return $type;
		case 'name':
			return $name;
		case 'options':
			return $options;
		default:
			return array($type,$name,$options);
		}
	}
	
	function & get_types($return = ''){
		static $valid_types,$types_with_options,$types_need_massaging;
		if (!isset($valid_types)){			
			$valid_types = apply_filters('frmplus_valid_field_types',array('textarea','select','checkbox','radio','radioline'));
			$types_with_options = apply_filters('frmplus_field_types_with_options',array('select','checkbox','radio'));			
			
			// these are field types where the $_POST array needs to be massaged 
			// before writing to the database
			$types_need_massaging = apply_filters('frmplus_field_types_need_massaging',array('radioline'));
		}
		switch($return){
		case 'valid':
			return $valid_types;
		case 'with_options':
			return $types_with_options;
		case 'need_massaging':
			return $types_need_massaging;
		default:
			return array(&$valid_types,$types_with_options);
		}
	}
	
	function determine_precedence($row_type,$col_type){
		if ($row_type === null or ($row_type == 'text' and $col_type != 'text')){
			return 'column';
		}
		else{
			return 'row';
		}
	}
	
	function parse_with_precedence($row_opt,$col_opt){
		$row_type = FrmPlusFieldsHelper::parse_option($row_opt,'type');
		$col_type = FrmPlusFieldsHelper::parse_option($col_opt,'type');
		$precedence = FrmPlusFieldsHelper::determine_precedence($row_type,$col_type);
		list($type,$this_option) = ($precedence == 'row' ? array($row_type,$row_opt) : array($col_type,$col_opt));
		
		// Returns array((string)$type,(string)$name,(array)$options,(string)$precedence); 
		return array_merge(FrmPlusFieldsHelper::parse_option($this_option),array($precedence));
	}
	
	function get_simple_on_value(){
		static $on_value;
		if (!isset($on_value)){
			$on_value = apply_filters('frmplus_simple_on_value','on');
		}
		return $on_value;
	}
	
	function maybe_book_massage($field_id,$type,$precedence,$num){
		if (in_array($type,$this->get_types('need_massaging'))){
			$this->book_massage($field_id,$type,$precedence,$num);
		}
	}
	
	function book_massage($field_id,$type,$precedence,$num){
		if (!isset($this->fields_need_massaging)){
			$this->fields_need_massaging = array();
		}
		if (!isset($this->fields_need_massaging[$field_id])){
			$this->fields_need_massaging[$field_id] = array();
		}
		$booking = "$type|$precedence|$num"; // i.e. "radioline|row|3" or "radioline|column|2"
		if (!in_array($booking,$this->fields_need_massaging[$field_id])){ // don't double book
			$this->fields_need_massaging[$field_id][] = $booking;
		}
	}
	
	function render_massage_bookings($form, $form_action, $errors){
		if (isset($this->fields_need_massaging)){
			foreach ($this->fields_need_massaging as $field_id => $bookings){
				foreach ($bookings as $booking){
					echo '<input type="hidden" name="frmplus_massage_fields['.$field_id.'][]" value="'.$booking.'" />'."\n";
				}
			}
		}
		unset($this->fields_need_massaging); // Make way for the next form, if one exists
	}
	
	function perform_massage($atts){
		if (isset($_POST['frmplus_massage_fields'])){
			foreach ($_POST['frmplus_massage_fields'] as $field_id => $bookings){
				foreach ($bookings as $booking){
					list($type,$precedence,$num) = explode('|',$booking);
					switch($type){
					case 'radioline':
						switch($precedence){
						case 'row':
							// Data comes in in the form $_POST['item_meta'][$field_id][$num] = $col_num
							// So, massage it into $_POST['item_meta'][$field_id][$num] = array($col_num => FrmPlusFieldsHelper::get_simple_on_value())
							if (isset($_POST['item_meta'][$field_id][$num])){
								$col_num = $_POST['item_meta'][$field_id][$num];
								$_POST['item_meta'][$field_id][$num] = array($col_num => FrmPlusFieldsHelper::get_simple_on_value());
							}
							break;
						case 'column':
							// Data comes in in the form $_POST['item_meta'][$field_id]['transpose'][$num] = $row_num
							// So, massage it into $_POST['item_meta'][$field_id][$row_num] = array($num => FrmPlusFieldsHelper::get_simple_on_value())
							if (isset($_POST['item_meta'][$field_id]['transpose'][$num])){
								$row_num = $_POST['item_meta'][$field_id]['transpose'][$num];
								if (!isset($_POST['item_meta'][$field_id][$row_num])){
									$_POST['item_meta'][$field_id][$row_num] = array();
								}
								$_POST['item_meta'][$field_id][$row_num][$num] = FrmPlusFieldsHelper::get_simple_on_value();
							}
							break;
						}
						break;
					default:
						do_action('frmplus_perform_massage',$field_id,$booking);
						break;
					}
				}
				if (isset($_POST['item_meta'][$field_id]['transpose'])){
					unset($_POST['item_meta'][$field_id]['transpose']);
				}
				do_action('frmplus_done_field_massaging',$field_id);
			}
			unset($_POST['frmplus_massage_fields']);
			do_action('frmplus_done_all_massaging');
		}
	}

}

?>
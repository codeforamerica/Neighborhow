<?php ob_start(); ?>
<?php
// The value, if any, of this table cell
$value = '';
if (is_array($field['value']) and array_key_exists($row_num,$field['value'])){
	if (is_array($field['value'][$row_num])){
		if (array_key_exists($col_num,$field['value'][$row_num])){
			$value = $field['value'][$row_num][$col_num];
		}
	}
	else{
		// This is the case only for radioline: types
		if ($display_only == true){
			$value = ($field['value'][$row_num] == $col_num ? FrmPlusFieldsHelper::get_simple_on_value() : '');
		}
		else{
			$value = $field['value'][$row_num];
		}
	}
} 	
?>
<?php 
if ($display_only == true) {
	if (is_array($value)){
		echo implode(', ',$value);
	}
	elseif ($value == ''){
		echo '&nbsp;';
	}
	else{
		echo str_replace("\n","<br/>",$value);
	}
}
else {

	// FrmPlusFieldsHelper::parse_with_precedence takes two arguments
	//  - the first is the row title (i.e. textarea:My Row Name) (if there is one, null otherwise)
	//  - the second is the column title
	//  - it returns an array of ($type,$name,$options,$precedence), where: 
	//		$type is 'textarea','select',etc, , with precedence given of "anything trumps plain old text"
	//		$name is the name of the row or column
	//		$options are the options (not applicable for textare and text fields)
	//		$precedence is a string, either 'row' or 'column'
	list($type,$name,$options,$precedence) = FrmPlusFieldsHelper::parse_with_precedence((count($rows) ? $opt : null),$col_opt);
	$options = apply_filters('frmplus_field_options',$options,$field,$name,$row_num,$col_num); // Give filters the option of filtering on row/col or name of option or combination
	$this_field_id = "field_{$field['field_key']}_{$row_num}_{$col_num}";
	$this_field_name = $field_name.'['.$row_num.']';
	switch($type){ 
	case 'textarea':
		echo '<textarea id="'.$this_field_id.'" name="'.$this_field_name.'[]" class="auto_width table-cell">'.htmlspecialchars($value).'</textarea>'."\n";
		break;
	case 'radio':
		if (count($options)){
			foreach($options as $option_num => $option){
				echo '<input type="radio" class="radio" id="'.$this_field_id.'_'.$option_num.'" name="'.$this_field_name.'['.$col_num.']" value="'.esc_attr($option).'" '.checked($value,$option,false).' /><label for="'.$this_field_id.'_'.$option_num.'">'.$option.'</label>'."\n";
			}
		}
		else{
			echo '<input type="radio" class="radio" id="'.$this_field_id.'" name="'.$this_field_name.'['.$col_num.']" value="'.esc_attr(FrmPlusFieldsHelper::get_simple_on_value()).'" '.checked($value,FrmPlusFieldsHelper::get_simple_on_value(),false).' />'."\n";
		}
		break;
	case 'radioline':
		switch ($precedence){
		case 'row':
			// This is a row of radio buttons, grouped together (so selecting one column deselects all others)
			$option_value = $col_num;
			echo '<input type="radio" class="radio" id="'.$this_field_id.'" name="'.$this_field_name.'" value="'.esc_attr($option_value).'" '.checked($value,FrmPlusFieldsHelper::get_simple_on_value(),false).' />'."\n";
			break;
		case 'column':
			// This is a column of radio buttons, grouped together (so selecting one row deselects all others)
			$option_value = $row_num;
			echo '<input type="radio" class="radio" id="'.$this_field_id.'" name="'.$field_name.'[transpose]['.$col_num.']" value="'.esc_attr($option_value).'" '.checked($value,FrmPlusFieldsHelper::get_simple_on_value(),false).' />'."\n";
			break;
		}
		break;
	case 'select':
		echo '<select id="'.$this_field_id.'" name="'.$this_field_name.'[]" >'."\n";
		echo '<option value="" '.selected($value,'',false).'>&nbsp;</option>'."\n";
		foreach ($options as $option){
			echo '<option value="'.esc_attr($option).'" '.selected($value,$option,false).'>'.$option.'</option>'."\n";
		}
		echo '</select>'."\n";
		break;
	case 'checkbox':
		if (count($options)){
			foreach ($options as $option_num => $option){
				echo '<input type="checkbox" id="'.$this_field_id.'_'.$option_num.'" name="'.$this_field_name.'['.$col_num.'][]" class="checkbox" value="'.esc_attr($option).'" '.checked(in_array($option,(array)$value),true,false).' /><label for="'.$this_field_id.'_'.$option_num.'">'.$option.'</label>'."\n";
			}
		}
		else{
			echo '<input type="checkbox" class="checkbox" id="'.$this_field_id.'" name="'.$this_field_name.'['.$col_num.']" value="'.esc_attr(FrmPlusFieldsHelper::get_simple_on_value()).'" '.checked($value,FrmPlusFieldsHelper::get_simple_on_value(),false).' />'."\n";
		}
		break;
	default:
		echo '<input type="text" size="10" id="'.$this_field_id.'" name="'.$this_field_name.'[]" value="'.esc_attr($value).'" class="auto_width table-cell" />'."\n";
		break;
	}
	
	// Massaging might need to happen.  Let's see if we need to book a massage
	global $frmplus_fields_helper;
	$frmplus_fields_helper->maybe_book_massage($field['id'],$type,$precedence,($precedence == 'column' ? $col_num : $row_num));
}
?>
<?php $_o = ob_get_clean(); echo apply_filters('table_field_'.$field['field_key'],$_o,$field,$row_num,$col_num); ?>

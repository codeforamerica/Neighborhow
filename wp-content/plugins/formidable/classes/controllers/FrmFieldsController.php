<?php
/**
 * @package Formidable
 */
 
class FrmFieldsController{
    function FrmFieldsController(){
        add_action('wp_ajax_frm_insert_field', array(&$this, 'create') );
        add_action('wp_ajax_frm_field_name_in_place_edit', array(&$this, 'edit_name') );
        add_action('wp_ajax_frm_field_desc_in_place_edit', array(&$this, 'edit_description') );
        add_action('wp_ajax_frm_mark_required', array(&$this, 'mark_required') );
        add_action('wp_ajax_frm_update_ajax_option', array(&$this, 'update_ajax_option') );
        add_action('wp_ajax_frm_duplicate_field', array(&$this, 'duplicate') );
        add_action('wp_ajax_frm_delete_field', array(&$this, 'destroy') );
        add_action('wp_ajax_frm_add_field_option', array(&$this, 'add_option'));
        add_action('wp_ajax_frm_field_option_ipe', array(&$this, 'edit_option') );
        add_action('wp_ajax_frm_delete_field_option', array(&$this, 'delete_option'));
        add_action('wp_ajax_frm_import_options', array(&$this, 'import_options') );
        add_action('wp_ajax_frm_update_field_order', array(&$this, 'update_order') );
        add_filter('frm_field_type' ,array( &$this, 'change_type'));
        add_filter('frm_display_field_options', array(&$this, 'display_field_options'));
        add_action('frm_field_input_html', array(&$this, 'input_html'));
        add_filter('frm_field_value_saved', array( &$this, 'check_value'), 50, 3);
        add_filter('frm_field_label_seen', array( &$this, 'check_label'), 10, 3);
    }
    
    function create(){
        global $frm_field, $frm_ajax_url;
        $field_data = $_POST['field'];
        $form_id = $_POST['form_id'];
        $values = array();
        if(class_exists('FrmProForm'))
            $values['post_type'] = FrmProForm::post_type($form_id);
        
        $field_values = apply_filters('frm_before_field_created', FrmFieldsHelper::setup_new_vars($field_data, $form_id));
        
        $field_id = $frm_field->create( $field_values );
        
        if ($field_id){
            $field = FrmFieldsHelper::setup_edit_vars($frm_field->getOne($field_id));
            $field_name = "item_meta[$field_id]";
            $id = $form_id;
            require(FRM_VIEWS_PATH.'/frm-forms/add_field.php'); 
            require(FRM_VIEWS_PATH.'/frm-forms/new-field-js.php'); 
        }
        die();
    }
    
    function edit_name(){
        global $frm_field;
        $id = str_replace('field_', '', $_POST['element_id']);
        $values = array('name' => trim($_POST['update_value']));
        if ($_POST['original_html'] == 'Untitled')
            $values['field_key'] = $_POST['update_value'];
        $form = $frm_field->update($id, $values);
        echo stripslashes($_POST['update_value']);  
        die();
    }
    

    function edit_description(){
        global $frm_field;
        $id = str_replace('field_', '', $_POST['element_id']);
        $frm_field->update($id, array('description' => $_POST['update_value']));
        echo stripslashes($_POST['update_value']);
        die();
    } 
    
    function mark_required(){
        global $frm_field;
        $frm_field->update($_POST['field'], array('required' => $_POST['required']));
        die();
    }
    
    function update_ajax_option(){
        global $frm_field;
        $field = $frm_field->getOne($_POST['field']);
        $field->field_options = maybe_unserialize($field->field_options);
        foreach(array('clear_on_focus', 'separate_value', 'default_blank') as $val){
            if(isset($_POST[$val])){
                $new_val = $_POST[$val];
                if($val == 'separate_value')
                    $new_val = (isset($field->field_options[$val]) and $field->field_options[$val]) ? 0 : 1;
                
                $field->field_options[$val] = $new_val;   
                unset($new_val);       
            }  
            unset($val);
        }

        $frm_field->update($_POST['field'], array('field_options' => $field->field_options));
        die();
    }
    
    function duplicate(){
        global $frmdb, $frm_field, $frm_app_helper, $frm_ajax_url;
        
        $copy_field = $frm_field->getOne($_POST['field_id']);
        if (!$copy_field) return;
            
        $values = array();
        $values['field_key'] = FrmAppHelper::get_unique_key('', $frmdb->fields, 'field_key');
        $values['field_options'] = maybe_unserialize($copy_field->field_options);
        $values['form_id'] = $copy_field->form_id;
        foreach (array('name', 'description', 'type', 'default_value', 'options', 'required') as $col)
            $values[$col] = $copy_field->{$col};
        $field_count = $frm_app_helper->getRecordCount("form_id='$copy_field->form_id'", $frmdb->fields);
        $values['field_order'] = $field_count + 1;
        
        $field_id = $frm_field->create($values);
        
        if ($field_id){
            $field = FrmFieldsHelper::setup_edit_vars($frm_field->getOne($field_id));
            $field_name = "item_meta[$field_id]";
            $id = $field['form_id'];
            require(FRM_VIEWS_PATH.'/frm-forms/add_field.php'); 
            require(FRM_VIEWS_PATH.'/frm-forms/new-field-js.php'); 
        }
        die();
    }
    
    function destroy(){
        global $frm_field;
        $field_id = $frm_field->destroy($_POST['field_id']);
        die();
    }   

    /* Field Options */
    function add_option(){
        global $frm_field, $frm_ajax_url;

        $id = $_POST['field_id'];
        $field = $frm_field->getOne($id);
        $options = maybe_unserialize($field->options);
        if(!empty($options))
            $last = max(array_keys($options));
        else
            $last = 0;
        $opt_key = $last + 1;
        $opt = 'Option '.(count($options)+1);
        $field_val = $opt;
        $options[$opt_key] = $opt;
        $frm_field->update($id, array('options' => maybe_serialize($options)));
        $checked = '';

        $field_data = $frm_field->getOne($id);
        $field_data->field_options = maybe_unserialize($field_data->field_options);
        $field = array();
        $field['type'] = $field_data->type;
        $field['id'] = $id;
        $field['separate_value'] = isset($field_data->field_options['separate_value']) ? $field_data->field_options['separate_value'] : 0;
        $field_name = "item_meta[$id]";
        
        require(FRM_VIEWS_PATH.'/frm-forms/new-option-js.php');
        require(FRM_VIEWS_PATH.'/frm-fields/single-option.php');
        die();
    }

    function edit_option(){
        global $frm_field;
        $ids = explode('-', $_POST['element_id']);
        $id = str_replace('field_', '', $ids[0]);
        if(strpos($_POST['element_id'], 'key_')){
            $id = str_replace('key_', '', $id);
            $new_value = $_POST['update_value'];
        }else{
            $new_label = $_POST['update_value'];
        }
        $field = $frm_field->getOne($id);
        $options = maybe_unserialize($field->options);
        $this_opt = (array)$options[$ids[1]];
        
        $label = isset($this_opt['label']) ? $this_opt['label'] : reset($this_opt);
        if(isset($this_opt['value']))
            $value =  $this_opt['value'];
            
        if(!isset($new_label))
            $new_label = $label;
            
        if(isset($new_value) or isset($value))
            $update_value = isset($new_value) ? $new_value : $value;
        
        if(isset($update_value) and $update_value != $new_label)
            $options[$ids[1]] = array('value' => $update_value, 'label' => $new_label);
        else
            $options[$ids[1]] = $_POST['update_value'];
   
        $frm_field->update($id, array('options' => maybe_serialize($options)));
        echo stripslashes($_POST['update_value']);
        die();
    }

    function delete_option(){
        global $frm_field;
        $field = $frm_field->getOne($_POST['field_id']);
        $options = maybe_unserialize($field->options);
        unset($options[$_POST['opt_key']]);
        $frm_field->update($_POST['field_id'], array('options' => maybe_serialize($options)));
        die();
    }
    
    function import_choices($field_id){
        if(!current_user_can('frm_edit_forms'))
            return;
          
        global $frm_ajax_url;
        
        if(function_exists('register_admin_color_schemes'))
            register_admin_color_schemes();
        
        $hook_suffix = $admin_body_class = '';
        
        if ( get_user_setting('mfold') == 'f' )
        	$admin_body_class .= ' folded';

        if ( function_exists('is_admin_bar_showing') and is_admin_bar_showing() )
        	$admin_body_class .= ' admin-bar';

        if ( is_rtl() )
        	$admin_body_class .= ' rtl';

        $admin_body_class .= ' admin-color-' . sanitize_html_class( get_user_option( 'admin_color' ), 'fresh' );
        $prepop = array();
        $prepop[__('Countries', 'formidable')] = FrmAppHelper::get_countries();
        
        $states = FrmAppHelper::get_us_states();
        $prepop[__('U.S. States', 'formidable')] = array_values($states);
        $prepop[__('U.S. State Abbreviations', 'formidable')] = array_keys($states);
        
        $prepop[__('Age', 'formidable')] = array(
            __('Under 18', 'formidable'), __('18-24', 'formidable'), __('25-34', 'formidable'), 
            __('35-44', 'formidable'), __('45-54', 'formidable'), __('55-64', 'formidable'),
            __('65 or Above', 'formidable'), __('Prefer Not to Answer', 'formidable')
        );
        
        $prepop[__('Satisfaction', 'formidable')] = array(
            __('Very Satisfied', 'formidable'), __('Satisfied', 'formidable'), __('Neutral', 'formidable'), 
            __('Unsatisfied', 'formidable'), __('Very Unsatisfied', 'formidable'), __('N/A', 'formidable')
        );

        $prepop[__('Importance', 'formidable')] = array(
            __('Very Important', 'formidable'), __('Important', 'formidable'), __('Neutral', 'formidable'), 
            __('Somewhat Important', 'formidable'), __('Not at all Important', 'formidable'), __('N/A', 'formidable')
        );
        
        $prepop[__('Agreement', 'formidable')] = array(
            __('Strongly Agree', 'formidable'), __('Agree', 'formidable'), __('Neutral', 'formidable'), 
            __('Disagree', 'formidable'), __('Strongly Disagree', 'formidable'), __('N/A', 'formidable')
        );
        
        $field = FrmField::getOne($field_id);
        $field->options = stripslashes_deep(maybe_unserialize($field->options));
        $field->field_options = maybe_unserialize($field->field_options);
        
        include(FRM_VIEWS_PATH.'/frm-fields/import_choices.php');
        die();
    }
    
    function import_options(){
        if(!is_admin() or !current_user_can('frm_edit_forms'))
            return;
        
        global $frm_field, $frm_ajax_url;
        
        extract($_POST);
        
        $field = $frm_field->getOne($field_id);
        
        if(!in_array($field->type, array('radio', 'checkbox', 'select')))
            return;
        
        $field = FrmFieldsHelper::setup_edit_vars($field);
        $opts = stripslashes($opts);    
        $opts = explode("\n", rtrim($opts, "\n"));
        if($field['separate_value']){
            foreach($opts as $opt_key => $opt){
                if(strpos($opt, '|') !== false){
                    $vals = explode('|', $opt);
                    if($vals[0] != $vals[1])
                        $opts[$opt_key] = array('label' => $vals[0], 'value' => $vals[1]);
                    unset($vals);
                }
                unset($opt_key);
                unset($opt);
            }
        }
        
        $frm_field->update($field_id, array('options' => maybe_serialize($opts)));
        
        $field['options'] = stripslashes_deep($opts);
        $field_name = $field['name'];
        
        if ($field['type'] == 'radio' or $field['type'] == 'checkbox'){
            require(FRM_VIEWS_PATH.'/frm-fields/radio.php');
        }else{
            foreach ($field['options'] as $opt_key => $opt){ 
                $field_val = apply_filters('frm_field_value_saved', $opt, $opt_key, $field);
                $opt = apply_filters('frm_field_label_seen', $opt, $opt_key, $field);
                require(FRM_VIEWS_PATH.'/frm-fields/single-option.php');
            }
        }
        
        require(FRM_VIEWS_PATH.'/frm-forms/new-field-js.php'); 
        
        die();
    }

    function update_order(){
        if(isset($_POST) and isset($_POST['frm_field_id'])){
            global $frm_field;
            
            foreach ($_POST['frm_field_id'] as $position => $item)
                $frm_field->update($item, array('field_order' => $position));
        }
        die();
    }
    
    function change_type($type){
        global $frmpro_is_installed;

        if ($frmpro_is_installed) return $type;
        
        if($type == 'scale' || $type == '10radio')
            $type = 'radio';
        else if($type == 'rte')
            $type = 'textarea';
            
        $frm_field_selection = FrmFieldsHelper::field_selection();
        $types = array_keys($frm_field_selection);
        if (!in_array($type, $types) && $type != 'captcha')
            $type = 'text';

        return $type;
    }
    
    function display_field_options($display){
        switch($display['type']){
            case 'captcha':
                $display['required'] = false;
                $display['invalid'] = true;
                $display['default_blank'] = false;
            break;
            case 'radio':
                $display['default_blank'] = false;
            break;
            case 'text':
            case 'textarea':
                $display['size'] = true;
                $display['clear_on_focus'] = true;
            break;
            case 'select':
                $display['size'] = true;
            break;
        }
        
        return $display;
    }
    
    function input_html($field, $echo=true){
        global $frm_settings;
        
        $class = $field['type'];
        $add_html = '';
        
        if(isset($field['size']) and $field['size'] > 0){
            if(!in_array($field['type'], array('textarea', 'select', 'data', 'time')))
                $add_html .= ' size="'. $field['size'] .'"';
            $class .= " auto_width";
        }
        
        if(isset($field['max']) and !in_array($field['type'], array('textarea', 'rte')) and !empty($field['max']))
            $add_html .= ' maxlength="'. $field['max'] .'"';
        
        if(!is_admin() or !isset($_GET) or !isset($_GET['page']) or $_GET['page'] == 'formidable_entries'){
            $action = isset($_REQUEST['frm_action']) ? 'frm_action' : 'action';
            $action = FrmAppHelper::get_param($action);
            
            if(isset($field['required']) and $field['required']){
                //if($field['type'] != 'checkbox')
                //    $add_html .= ' required="required"';
                    
                if($field['type'] == 'file' and $action == 'edit'){
                    //don't add the required class if this is a file upload when editing
                }else
                    $class .= " required";
            }

            //if($frm_settings->use_html and isset($field['default_value']) and !empty($field['default_value']) and isset($field['clear_on_focus']) and $field['clear_on_focus'] and !in_array($field['type'], array('select', 'radio', 'checkbox', 'hidden'))) 
            //    $add_html .= ' placeholder="'.$field['default_value'].'"';

            if(isset($field['clear_on_focus']) and $field['clear_on_focus'] and !empty($field['default_value'])){
                $val = str_replace(array("\r\n", "\n"), '\r', addslashes(str_replace('&#039;', "'", esc_attr($field['default_value']))));
                $add_html .= ' onfocus="frmClearDefault('."'". $val ."'". ',this)" onblur="frmReplaceDefault('."'". $val ."'". ',this)"';
                
                if($field['value'] == $field['default_value'])
                    $add_html .= ' style="font-style:italic;"';
            }
        }
        
        if(isset($field['input_class']) and !empty($field['input_class']))
            $class .= ' '. $field['input_class'];
        
        $class = apply_filters('frm_field_classes', $class, $field);
        $add_html .= ' class="'.$class.'"';
        
        if($echo)
            echo $add_html;
        
        return $add_html;
    }
    
    function check_value($opt, $opt_key, $field){
        if(is_array($opt)){
            if(isset($field['separate_value']) and $field['separate_value']){
                $opt = isset($opt['value']) ? $opt['value'] : (isset($opt['label']) ? $opt['label'] : reset($opt));
            }else{
                $opt = (isset($opt['label']) ? $opt['label'] : reset($opt));
            }
        }
        return $opt;
    }
    
    function check_label($opt, $opt_key, $field){
        if(is_array($opt))
            $opt = (isset($opt['label']) ? $opt['label'] : reset($opt));
            
        return $opt;
    }
}
?>
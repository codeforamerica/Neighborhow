<?php
class FrmProSettings{
    var $form_width;
    var $edit_msg;
    var $update_value;
    var $already_submitted;
    var $rte_off;
    var $template_path;
    var $csv_format;
    
    var $date_format;
    var $cal_date_format;
    
    var $theme_css;
    var $theme_name;
    var $theme_nicename;
    
    var $permalinks;
    
    var $form_align;
    var $fieldset;
    var $fieldset_color;
    var $fieldset_padding;
    
    var $font;
    var $font_size;
    var $label_color;
    var $weight;
    var $position;
    var $align;
    var $width;
    var $required_color;
    var $required_weight;
    
    var $description_font;
    var $description_font_size;
    var $description_color;
    var $description_weight;
    var $description_style;
    var $description_align;
    
    var $field_font_size;
    var $field_width;
    var $auto_width;
    var $field_pad;
    var $field_margin;
    
    var $bg_color;
    var $text_color;
    var $border_color;
    var $field_border_width;
    var $field_border_style;
    
    //var $bg_color_hv;
    //var $border_color_hv;
    
    var $bg_color_active;
    var $border_color_active;
    
    var $bg_color_error;
    var $text_color_error;
    var $border_color_error;
    var $border_width_error;
    var $border_style_error;
    
    var $radio_align;
    var $check_align;
    var $check_font;
    var $check_font_size;
    var $check_label_color;
    var $check_weight;
    
    var $submit_style;
    var $submit_font_size;
    var $submit_width;
    var $submit_height;
    var $submit_bg_color;
    var $submit_bg_color2;
    var $submit_border_color;
    var $submit_border_width;
    var $submit_text_color;
    var $submit_weight;
    var $submit_border_radius;
    var $submit_bg_img;
    var $submit_margin;
    var $submit_padding;
    var $submit_shadow_color;
    
    var $border_radius;
    
    var $error_icon;
    var $error_bg;
    var $error_border;
    var $error_text;
    var $error_font_size;
    
    var $success_bg;
    var $success_border;
    var $success_text;
    var $success_font_size;

    function FrmProSettings(){
        $this->set_default_options();
    }
    
    function default_options(){
        return array(
            'already_submitted' => __('You have already submitted that form', 'formidable'),
            'template_path' => '',
            'rte_off'   => false,
            'csv_format' => 'UTF-8',
            'theme_css' => 'ui-lightness',
            'theme_name' => 'UI Lightness',
            
            'form_width' => '700px',
            'form_align' => 'left', 
            'fieldset' => '0px', 
            'fieldset_color' => '000000',
            'fieldset_padding' => '0px',
            
            'font' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif', 
            'font_size' => '12px', 
            'label_color' => '444444',
            'weight' => 'bold',
            'position' => 'none',
            'align' => 'left',
            'width' => '150px',
            'required_color' => 'ff0000',
            'required_weight' => 'bold',
            
            'description_font' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
            'description_font_size' => '11px',
            'description_color' => '666666',
            'description_weight' => 'normal',
            'description_style' => 'normal',
            'description_align' => 'left',
            
            'field_font_size' => '13px',
            'field_width' => '100%',
            'auto_width' => false,
            'field_pad' => '2px',
            'field_margin' => '20px',
            'text_color' => '444444',
            'border_color_hv' => 'dddddd',
            'border_color' => 'dddddd',
            'field_border_width' => '1px',
            'field_border_style' => 'solid',
            
            'bg_color' => 'eeeeee',
            'bg_color_hv' => 'eeeeee',
            'bg_color_active' => 'ffffff',
            'border_color_active' => 'dddddd',
            'text_color_error' => '444444',
            'bg_color_error' => 'eeeeee',
            'border_color_error' => 'ff0000',
            'border_width_error' => '1px',
            'border_style_error' => 'solid',
            
            'radio_align' => 'block',
            'check_align' => 'block',
            'check_font' => '"Lucida Grande","Lucida Sans Unicode",Tahoma,sans-serif',
            'check_font_size' => '12px',
            'check_label_color' => '444444',
            'check_weight' => 'normal',
            
            'submit_style' => false,
            'submit_font_size' => '14px',
            'submit_width' => 'auto',
            'submit_height' => 'auto',
            'submit_bg_color' => 'eeeeee',
            'submit_bg_color2' => 'cccccc',
            'submit_border_color' => 'dddddd',
            'submit_border_width' => '1px',
            'submit_text_color' => '444444',
            'submit_weight' => 'normal',
            'submit_border_radius' => '11px',
            'submit_bg_img' => '',
            'submit_margin' => '0px',
            'submit_padding' => '3px 8px',
            'submit_shadow_color' => '999999',
            
            'border_radius' => '0px',
            'error_icon' => 'update.png',
            'error_bg' => 'B81900',
            'error_border' => 'be2e17',
            'error_text' => 'ffffff',
            'error_font_size' => '14px',
            
            'success_bg_color' => 'FFFFE0',
            'success_border_color' => 'E6DB55',
            'success_text_color' => '444444',
            'success_font_size' => '14px'
        );
    }

    function set_default_options(){
        $this->edit_msg = __("Your submission was successfully saved.", 'formidable');
        $this->update_value = __('Update', 'formidable');
        
        if(!isset($this->date_format))
            $this->date_format = 'm/d/Y';
        if(!isset($this->cal_date_format))
            $this->cal_date_format = 'mm/dd/yy';
               
        $this->theme_nicename = sanitize_title_with_dashes($this->theme_name);
            
        //if(!isset($this->permalinks))
            $this->permalinks = false;
        
        $settings = $this->default_options();
        
        foreach($settings as $setting => $default){
            if(!isset($this->{$setting})){
                //set form width at 100% for existing users, but use default for new ones
                if($setting == 'form_width' and $settings['field_width'] != '100%')
                    $this->{$setting} = '100%';
                else
                    $this->{$setting} = $default;
            }
        }

        $this->font = stripslashes($this->font);
        $this->description_font = stripslashes($this->description_font);
    }

    function validate($errors, $params){
        return $errors;
    }

    function update($params){
        $this->date_format = $params['frm_date_format'];
        switch($this->date_format){
            case 'Y/m/d':
                $this->cal_date_format = 'yy/mm/dd';
            break;
            case 'd/m/Y':
                $this->cal_date_format = 'dd/mm/yy';
            break;
            case 'd.m.Y':
                $this->cal_date_format = 'dd.mm.yy';
                break;
            case 'j/m/y':
                $this->cal_date_format = 'd/mm/y';
            break;
            case 'Y-m-d':
                $this->cal_date_format = 'yy-mm-dd';
            break;
            case 'j-m-Y':
                $this->cal_date_format = 'd-mm-yy';
            break;
            default:
                $this->cal_date_format = 'mm/dd/yy';
        }
        
        $this->permalinks = isset($params['frm_permalinks']) ? $params['frm_permalinks'] : 0;
        
        $settings = $this->default_options();
        
        foreach($settings as $setting => $default){
            if(isset($params['frm_'.$setting])){
                if(preg_match('/color/', $setting) or in_array($setting, array('error_bg', 'error_border', 'error_text'))) 
                    $this->{$setting} = str_replace('#', '', $params['frm_'.$setting]); //if is a color
                else
                    $this->{$setting} = $params['frm_'.$setting];
            }
        }
        
        $this->submit_style = isset($params['frm_submit_style']) ? $params['frm_submit_style'] : 0;
        $this->auto_width = isset($params['frm_auto_width']) ? $params['frm_auto_width'] : 0;
        $this->error_icon = str_replace(FRMPRO_ICONS_URL .'/', '', $params['frm_error_icon']);
    }

    function store(){
        // Save the posted value in the database
        update_option( 'frmpro_options', $this);
        
        delete_transient('frmpro_options');
        set_transient('frmpro_options', $this);
        
        $filename = FRMPRO_PATH .'/css/custom_theme.css.php';
        if (is_file($filename)) {
            $uploads = wp_upload_dir();
            $target_path = $uploads['basedir'];

            wp_mkdir_p($target_path);

            $target_path .= "/formidable";
            wp_mkdir_p($target_path);
            
            if(!file_exists($target_path .'/index.php')){
                if ($fp = fopen($target_path .'/index.php', 'w')){
                    $index = "<?php\n// Silence is golden.\n?>";
                    fwrite($fp, $index);
                    fclose($fp);
                    unset($index);
                }
                unset($fp);
            }
              
            $target_path .= "/css";
            wp_mkdir_p($target_path);
              
            $saving = true;
            $css = $warn = "/* WARNING: Any changes made to this file will be lost when your Formidable settings are updated */";
            $css .= "\n";
            ob_start();
            include $filename;
            //system($filename);
            $css .= ob_get_contents();
            ob_end_clean();
            $css .= "\n ". $warn;
            
            $css_file = $target_path .'/formidablepro.css';
            if ($fp = fopen($css_file, 'w')){
                fwrite($fp, $css);
                fclose($fp);
                
                $stat = @stat( dirname( $css_file ) );
            	$perms = $stat['mode'] & 0007777;
            	//$perms = $perms & 0000666;
            	@chmod( $css_file, $perms );
            }
            
            update_option('frmpro_css', $css);
            
            delete_transient('frmpro_css');
            set_transient('frmpro_css', $css);
        }
    }
  
}
?>
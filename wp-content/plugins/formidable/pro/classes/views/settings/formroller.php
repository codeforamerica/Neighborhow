
<div class="clearfix frm_settings_page">

	<fieldset class="clearfix">			    
	    <div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('General', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
                <div class="field-group clearfix">
					<label><?php _e('Align', 'formidable') ?></label>
					<select name="frm_form_align" id="frm_form_align">
						<option value="left" <?php selected($frmpro_settings->form_align, 'left') ?>><?php _e('left', 'formidable') ?></option>
						<option value="right" <?php selected($frmpro_settings->form_align, 'right') ?>><?php _e('right', 'formidable') ?></option>
						<option value="center" <?php selected($frmpro_settings->form_align, 'center') ?>><?php _e('center', 'formidable') ?></option>
					</select>
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Max Width', 'formidable') ?></label>
					<input type="text" name="frm_form_width" value="<?php echo esc_attr($frmpro_settings->form_width) ?>"/>
				</div>
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('Border', 'formidable') ?></label>
					<input type="text" name="frm_fieldset" id="frm_fieldset" value="<?php echo esc_attr($frmpro_settings->fieldset) ?>" size="4" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Color', 'formidable') ?></label>
					<input type="text" name="frm_fieldset_color" id="frm_fieldset_color" class="hex" value="<?php echo esc_attr($frmpro_settings->fieldset_color) ?>" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Padding', 'formidable') ?></label>
					<input type="text" name="frm_fieldset_padding" id="frm_fieldset_padding" value="<?php echo esc_attr($frmpro_settings->fieldset_padding) ?>" size="4" />
				</div>
				<div class="clear"></div>
			</div>
		</div>

		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Label Settings', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				<div class="field-group clearfix" style="padding-right:0;width:100%;">
					<label><?php _e('Family', 'formidable') ?></label>
					<input type="text" name="frm_font" id="frm_font" value="<?php echo esc_attr(stripslashes($frmpro_settings->font)) ?>"  class="frm_full_width" />
				</div>
				<div class="field-group field-group-background clearfix">
					<label><?php _e('Color', 'formidable') ?></label>
					<input type="text" name="frm_label_color" id="frm_label_color" class="hex" value="<?php echo esc_attr($frmpro_settings->label_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Weight', 'formidable') ?></label>
					<select name="frm_weight" id="frm_weight">
						<option value="normal" <?php selected($frmpro_settings->weight, 'normal') ?>>normal</option>
						<option value="bold" <?php selected($frmpro_settings->weight, 'bold') ?>>bold</option>
					</select>
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Size', 'formidable') ?></label>
					<input type="text" name="frm_font_size" id="frm_font_size" value="<?php echo esc_attr($frmpro_settings->font_size) ?>"  size="3" />
				</div>
				
				<div class="field-group clearfix clear">
					<label><?php _e('Position', 'formidable') ?></label>
					<select name="frm_position" id="frm_position" onchange="frmSetPosClass(this.value)">
					    <?php foreach (array('none' => 'top', 'left' => 'left', 'right' => 'right') as $pos => $pos_label){ ?>
					        <option value="<?php echo $pos ?>" <?php selected($frmpro_settings->position, $pos) ?>><?php echo $pos_label ?></option>
					    <?php }?>
					</select>
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Align', 'formidable') ?></label>
					<select name="frm_align" id="frm_align">
						<option value="left" <?php selected($frmpro_settings->align, 'left') ?>><?php _e('left', 'formidable') ?></option>
						<option value="right" <?php selected($frmpro_settings->align, 'right') ?>><?php _e('right', 'formidable') ?></option>
					</select>
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Width', 'formidable') ?></label>
					<input type="text" name="frm_width" id="frm_width" value="<?php echo esc_attr($frmpro_settings->width) ?>"  size="5" />
				</div>
				<div class="clear"></div>
				<h4 style="margin-bottom:0;"><?php _e('Required Indicator', 'formidable') ?></h4>
				<div class="field-group field-group-border clearfix">
					<label class="background"><?php _e('Color', 'formidable') ?></label>
					<input type="text" name="frm_required_color" id="frm_required_color" class="hex" value="<?php echo esc_attr($frmpro_settings->required_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Weight', 'formidable') ?></label>
					<select name="frm_required_weight" id="frm_required_weight">
						<option value="normal" <?php selected($frmpro_settings->required_weight, 'normal') ?>><?php _e('normal', 'formidable') ?></option>
						<option value="bold" <?php selected($frmpro_settings->required_weight, 'bold') ?>><?php _e('bold', 'formidable') ?></option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Field Description', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				<div class="field-group clearfix" style="padding-right:0;width:100%;">
					<label><?php _e('Family', 'formidable') ?></label>
					<input type="text" name="frm_description_font" id="frm_description_font" value="<?php echo esc_attr(stripslashes($frmpro_settings->description_font)) ?>"  class="frm_full_width" />
				</div>
				<div class="field-group field-group-background clearfix">
					<label><?php _e('Color', 'formidable') ?></label>
					<input type="text" name="frm_description_color" id="frm_description_color" class="hex" value="<?php echo esc_attr($frmpro_settings->description_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Weight', 'formidable') ?></label>
					<select name="frm_description_weight" id="frm_description_weight">
						<option value="normal" <?php selected($frmpro_settings->description_weight, 'normal') ?>><?php _e('normal', 'formidable') ?></option>
						<option value="bold" <?php selected($frmpro_settings->description_weight, 'bold') ?>><?php _e('bold', 'formidable') ?></option>
					</select>
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Style', 'formidable') ?></label>
					<select name="frm_description_style" id="frm_description_style">
						<option value="normal" <?php selected($frmpro_settings->description_style, 'normal') ?>><?php _e('normal', 'formidable') ?></option>
						<option value="italic" <?php selected($frmpro_settings->description_style, 'italic') ?>><?php _e('italic', 'formidable') ?></option>
					</select>
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Size', 'formidable') ?></label>
					<input type="text" name="frm_description_font_size" id="frm_description_font_size" value="<?php echo esc_attr($frmpro_settings->description_font_size) ?>"  size="3" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Align', 'formidable') ?></label>
					<select name="frm_description_align" id="frm_description_align">
						<option value="left" <?php selected($frmpro_settings->description_align, 'left') ?>><?php _e('left', 'formidable') ?></option>
						<option value="right" <?php selected($frmpro_settings->description_align, 'right') ?>><?php _e('right', 'formidable') ?></option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
        <div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Corner Radius', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				<div class="field-group field-group-corners clearfix">
					<label><?php _e('Corners', 'formidable') ?></label>
					<input type="text" value="<?php echo esc_attr($frmpro_settings->border_radius) ?>" name="frm_border_radius" id="frm_border_radius" size="4"/>
				</div>
				<p class="cornerWarning" style="margin-top:.9em"><em><strong>Note:</strong> <?php _e('Formidable uses CSS3 border-radius for corner rounding, which is not currently supported by Internet Explorer.', 'formidable') ?></em></p>
			</div><!-- /theme group content -->
		</div><!-- /theme group -->
		
		<div class="widget clearfix global-font">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Field Settings', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside" style="padding-top:10px;">
				<div class="field-group clearfix">
					<label><?php _e('Size', 'formidable') ?></label>
					<input type="text" name="frm_field_font_size" id="frm_field_font_size" value="<?php echo esc_attr($frmpro_settings->field_font_size) ?>"  size="5" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Width', 'formidable') ?></label>
					<input type="text" name="frm_field_width" id="frm_field_width" value="<?php echo esc_attr($frmpro_settings->field_width) ?>"  size="5" />
				</div>
				
				<div class="clear">
					<label><input type="checkbox" name="frm_auto_width" id="frm_auto_width" value="1" <?php checked($frmpro_settings->auto_width , 1) ?> /></label>
					<?php _e('Automatic Width for drop-down fields', 'formidable') ?>
				</div>
				
				<div class="field-group clearfix clear">
					<label><?php _e('Padding', 'formidable') ?></label>
					<input type="text" name="frm_field_pad" id="frm_field_pad" value="<?php echo esc_attr($frmpro_settings->field_pad) ?>"  size="5" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Bottom Margin', 'formidable') ?></label>
					<input type="text" name="frm_field_margin" id="frm_field_margin" value="<?php echo esc_attr($frmpro_settings->field_margin) ?>"  size="5" />
				</div>
				
			</div>
		</div>

		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Field Colors', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				<div class="field-group field-group-border clearfix">
					<label class="background"><?php _e('BG color', 'formidable') ?></label>
					<input type="text" name="frm_bg_color" id="frm_bg_color" class="hex" value="<?php echo esc_attr($frmpro_settings->bg_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Text', 'formidable') ?></label>
					<input type="text" name="frm_text_color" id="frm_text_color" class="hex" value="<?php echo esc_attr($frmpro_settings->text_color) ?>" />
				</div>
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('Border', 'formidable') ?></label>
					<input type="text" name="frm_border_color" id="frm_border_color" class="hex" value="<?php echo esc_attr($frmpro_settings->border_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Thickness', 'formidable') ?></label>
					<input type="text" name="frm_field_border_width" id="frm_field_border_width" value="<?php echo esc_attr($frmpro_settings->field_border_width) ?>" size="4" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Style', 'formidable')?></label>
					<select name="frm_field_border_style" id="frm_field_border_style">
					    <option value="solid" <?php selected($frmpro_settings->field_border_style, 'solid') ?>><?php _e('solid', 'formidable') ?></option>
						<option value="dotted" <?php selected($frmpro_settings->field_border_style, 'dotted') ?>><?php _e('dotted', 'formidable') ?></option>
						<option value="dashed" <?php selected($frmpro_settings->field_border_style, 'dashed') ?>><?php _e('dashed', 'formidable') ?></option>
						<option value="double" <?php selected($frmpro_settings->field_border_style, 'double') ?>><?php _e('double', 'formidable') ?></option>
					</select>
				</div>
				
				<div class="clear"></div>
			</div>
		</div>	

		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Field Colors: active state', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				<div class="field-group field-group-border clearfix">
					<label class="background"><?php _e('BG color', 'formidable') ?></label>
					<input type="text" name="frm_bg_color_active" id="frm_bg_color_active" class="hex" value="<?php echo esc_attr($frmpro_settings->bg_color_active) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Border', 'formidable') ?></label>
					<input type="text" name="frm_border_color_active" id="frm_border_color_active" class="hex" value="<?php echo esc_attr($frmpro_settings->border_color_active) ?>" />
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Field Colors: error state', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">				
				<div class="field-group field-group-border clearfix">
					<label class="background"><?php _e('BG color', 'formidable') ?></label>
					<input type="text" name="frm_bg_color_error" id="frm_bg_color_error" class="hex" value="<?php echo esc_attr($frmpro_settings->bg_color_error) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Text', 'formidable') ?></label>
					<input type="text" name="frm_text_color_error" id="frm_text_color_error" class="hex" value="<?php echo esc_attr($frmpro_settings->text_color_error) ?>" />
				</div>
				
				<div class="field-group field-group-border clearfix">
				    <label><?php _e('Border/Label', 'formidable') ?></label>
    				<input type="text" name="frm_border_color_error" id="frm_border_color_error" class="hex" value="<?php echo esc_attr($frmpro_settings->border_color_error) ?>" />
    			</div>
				<div class="field-group clearfix">
					<label><?php _e('Thickness', 'formidable') ?></label>
					<input type="text" name="frm_border_width_error" id="frm_border_width_error" value="<?php echo esc_attr($frmpro_settings->border_width_error) ?>" size="4" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Style', 'formidable') ?></label>
					<select name="frm_border_style_error" id="frm_border_style_error">
					    <option value="solid" <?php selected($frmpro_settings->border_style_error, 'solid') ?>><?php _e('solid', 'formidable') ?></option>
						<option value="dotted" <?php selected($frmpro_settings->border_style_error, 'dotted') ?>><?php _e('dotted', 'formidable') ?></option>
						<option value="dashed" <?php selected($frmpro_settings->border_style_error, 'dashed') ?>><?php _e('dashed', 'formidable') ?></option>
						<option value="double" <?php selected($frmpro_settings->border_style_error, 'double') ?>><?php _e('double', 'formidable') ?></option>
					</select>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div class="widget clearfix global-font">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Radio Buttons & Check Boxes', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside" style="padding-top:10px;">
    				<div class="field-group clearfix">
    					<label><?php _e('Radio', 'formidable') ?></label>
    					<select name="frm_radio_align" id="frm_radio_align">
    					    <?php foreach (array('block' => 'Multiple Rows', 'inline' => 'Single Row') as $pos => $pos_label){ ?>
    					        <option value="<?php echo $pos ?>" <?php selected($frmpro_settings->radio_align, $pos) ?>><?php echo $pos_label ?></option>
    					    <?php }?>
    					</select>
    				</div>

    				<div class="field-group clearfix">
    					<label><?php _e('Check Box', 'formidable') ?></label>
    					<select name="frm_check_align" id="frm_check_align">
    					    <?php foreach (array('block' => 'Multiple Rows', 'inline' => 'Single Row') as $pos => $pos_label){ ?>
    					        <option value="<?php echo $pos ?>" <?php selected($frmpro_settings->check_align, $pos) ?>><?php echo $pos_label ?></option>
    					    <?php }?>
    					</select>
    				</div>
    				
    				<div class="field-group clearfix">
    					<label><?php _e('Family', 'formidable') ?></label>
    					<input type="text" name="frm_check_font" id="frm_check_font" value="<?php echo esc_attr(stripslashes($frmpro_settings->check_font)) ?>"  class="frm_full_width" />
    				</div>
    				<div class="field-group field-group-background clearfix">
    					<label><?php _e('Color', 'formidable') ?></label>
    					<input type="text" name="frm_check_label_color" id="frm_check_label_color" class="hex" value="<?php echo esc_attr($frmpro_settings->check_label_color) ?>" />
    				</div>
    				<div class="field-group clearfix">
    					<label><?php _e('Weight', 'formidable') ?></label>
    					<select name="frm_check_weight" id="frm_check_weight">
    						<option value="normal" <?php selected($frmpro_settings->check_weight, 'normal') ?>><?php _e('normal', 'formidable') ?></option>
    						<option value="bold" <?php selected($frmpro_settings->check_weight, 'bold') ?>><?php _e('bold', 'formidable') ?></option>
    					</select>
    				</div>
    				<div class="field-group clearfix">
    					<label><?php _e('Size', 'formidable') ?></label>
    					<input type="text" name="frm_check_font_size" id="frm_check_font_size" value="<?php echo esc_attr($frmpro_settings->check_font_size) ?>"  size="3" />
    				</div>

    			</div>
    	</div>
    	
    	<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Calendar', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside" style="padding-top:10px;">
				<div class="clearfix">
					<label><?php _e('Theme', 'formidable') ?></label>
					<select name="frm_theme_selector" style="line-height:1;">
                	    <?php 
                	        foreach($jquery_themes as $theme_name => $theme_title){  ?>
                        <option value="<?php echo $theme_name ?>" id="theme_<?php echo $theme_name ?>" <?php selected($theme_title, $frmpro_settings->theme_name) ?>><?php echo $theme_title ?></option> 
                        <?php } ?>
                	</select>
                	<span id="frm_show_cal" class="theme_<?php echo $frmpro_settings->theme_css ?>"></span>
                	<input type="hidden" value="<?php echo esc_attr($frmpro_settings->theme_css) ?>" id="frm_theme_css" name="frm_theme_css" />
                    <input type="hidden" value="<?php echo esc_attr($frmpro_settings->theme_name) ?>" id="frm_theme_name" name="frm_theme_name" />
                	<div class="clear"></div>
				</div>
			</div>
		</div>
		
		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Submit Button', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside" style="padding-top:10px;">
				<div class="clearfix">
					<input type="checkbox" name="frm_submit_style" id="frm_submit_style" <?php echo ($frmpro_settings->submit_style)? 'checked="checked"': ''; ?> value="1" /> <label for="frm_submit_style"><?php _e('Disable submit button styling', 'formidable'); ?></label>
				</div>
				<p class="cornerWarning"><em><?php _e('Note: If disabled, you may not see the change take effect until you make 2 more styling changes or click "Update Options".', 'formidable') ?></em></p>
				
			    <div class="clearfix">
					<label><?php _e('Weight', 'formidable') ?></label>
					<select name="frm_submit_weight" id="frm_submit_weight">
						<option value="normal" <?php selected($frmpro_settings->submit_weight, 'normal') ?>><?php _e('normal', 'formidable') ?></option>
						<option value="bold" <?php selected($frmpro_settings->submit_weight, 'bold') ?>><?php _e('bold', 'formidable') ?></option>
					</select>
				</div>
				
				<div class="clearfix">
					<label><?php _e('Corners', 'formidable') ?></label>
					<input type="text" value="<?php echo esc_attr($frmpro_settings->submit_border_radius) ?>" name="frm_submit_border_radius" id="frm_submit_border_radius" size="4"/>
				</div>
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('Size', 'formidable') ?></label>
					<input type="text" name="frm_submit_font_size" id="frm_submit_font_size" value="<?php echo esc_attr($frmpro_settings->submit_font_size) ?>"  size="3" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Width', 'formidable') ?></label>
					<input type="text" name="frm_submit_width" id="frm_submit_width" value="<?php echo esc_attr($frmpro_settings->submit_width) ?>"  size="5" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Height', 'formidable') ?></label>
					<input type="text" name="frm_submit_height" id="frm_submit_height" value="<?php echo esc_attr($frmpro_settings->submit_height) ?>"  size="5" />
				</div>
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('BG Color', 'formidable') ?></label>
					<input type="text" name="frm_submit_bg_color" id="frm_submit_bg_color" class="hex" value="<?php echo esc_attr($frmpro_settings->submit_bg_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('BG Color', 'formidable') ?></label>
					<input type="text" name="frm_submit_bg_color2" id="frm_submit_bg_color2" class="hex" value="<?php echo esc_attr($frmpro_settings->submit_bg_color2) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Text', 'formidable') ?></label>
					<input type="text" name="frm_submit_text_color" id="frm_submit_text_color" class="hex" value="<?php echo esc_attr($frmpro_settings->submit_text_color) ?>" />
				</div>

				<div class="field-group-border clearfix" style="padding-top:10px;">
					<label><?php _e('BG Image', 'formidable') ?></label>
					<input type="text" name="frm_submit_bg_img" id="frm_submit_bg_img" value="<?php echo esc_attr($frmpro_settings->submit_bg_img) ?>"  />
				</div>
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('Border', 'formidable') ?></label>
					<input type="text" name="frm_submit_border_color" id="frm_submit_border_color" class="hex" value="<?php echo esc_attr($frmpro_settings->submit_border_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Thickness', 'formidable') ?></label>
					<input type="text" name="frm_submit_border_width" id="frm_submit_border_width" value="<?php echo esc_attr($frmpro_settings->submit_border_width) ?>" size="4" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Shadow', 'formidable') ?></label>
					<input type="text" name="frm_submit_shadow_color" id="frm_submit_shadow_color" class="hex" value="<?php echo esc_attr($frmpro_settings->submit_shadow_color) ?>" />
				</div>
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('Margin', 'formidable') ?></label>
					<input type="text" name="frm_submit_margin" id="frm_submit_margin" value="<?php echo esc_attr($frmpro_settings->submit_margin) ?>" size="6" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Padding', 'formidable') ?></label>
					<input type="text" name="frm_submit_padding" id="frm_submit_padding" value="<?php echo esc_attr($frmpro_settings->submit_padding) ?>" size="6" />
				</div>
				<div class="clear"></div>
			</div>
		</div>
			
		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Error', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('BG Color', 'formidable') ?></label>
                    <div class="hasPicker">
                        <input type="text" name="frm_error_bg" id="frm_error_bg" class="hex" value="<?php echo esc_attr($frmpro_settings->error_bg) ?>" /></div>
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Border', 'formidable') ?></label>
					<input type="text" name="frm_error_border" id="frm_error_border" class="hex" value="<?php echo esc_attr($frmpro_settings->error_border) ?>" />
				</div>
				<div class="field-group clearfix">

					<label><?php _e('Text', 'formidable') ?></label>
					<input type="text" name="frm_error_text" id="frm_error_text" class="hex" value="<?php echo esc_attr($frmpro_settings->error_text) ?>" />
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Icon', 'formidable') ?></label>
					<select name="frm_error_icon" class="texture">
                	    <?php for($i = count($error_icons) - 1; $i >= 0; $i--){
                            $filename = str_replace(FRMPRO_PATH .'/images/error_icons/', '', $error_icons[$i]);
                            $image_url = FRMPRO_ICONS_URL .'/'.$filename;
                        ?>
                        <option value="<?php echo $image_url ?>" data-texturewidth="16" data-textureheight="16" <?php selected($filename, $frmpro_settings->error_icon) ?>><?php echo $filename ?></option> 
                        <?php } ?>
                	</select>
				</div>
				
				<div class="field-group clearfix">
					<label><?php _e('Size', 'formidable') ?></label>
					<input type="text" name="frm_error_font_size" id="frm_error_font_size" value="<?php echo esc_attr($frmpro_settings->error_font_size) ?>"  size="3" />
				</div>
				<div class="clear"></div>
			</div>
		</div>
		
		<div class="widget clearfix">
			<div class="widget-top">
				<div class="widget-title-action"><a class="widget-action"></a></div>
				<div class="widget-title"><h4><?php _e('Success', 'formidable') ?></h4></div>
			</div>
			<div class="widget-inside">
				
				<div class="field-group field-group-border clearfix">
					<label><?php _e('BG Color', 'formidable') ?></label>
                    <div class="hasPicker">
                        <input name="frm_success_bg_color" id="frm_success_bg_color" class="hex" value="<?php echo esc_attr($frmpro_settings->success_bg_color) ?>" type="text" /></div>
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Border', 'formidable') ?></label>
					<input type="text" name="frm_success_border_color" id="frm_success_border_color" class="hex" value="<?php echo esc_attr($frmpro_settings->success_border_color) ?>" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Text', 'formidable') ?></label>
					<input name="frm_success_text_color" id="frm_success_text_color" class="hex" value="<?php echo esc_attr($frmpro_settings->success_text_color) ?>" type="text" />
				</div>
				<div class="field-group clearfix">
					<label><?php _e('Size', 'formidable') ?></label>
					<input type="text" name="frm_success_font_size" id="frm_success_font_size" value="<?php echo esc_attr($frmpro_settings->success_font_size) ?>"  size="3" />
				</div>
				<div class="clear"></div>
			</div>
		</div>

	</fieldset>

</div>

<style type="text/css">
#frm_show_cal{
vertical-align:bottom; height:27px; width:30px;
background:url(<?php echo FRMPRO_IMAGES_URL ?>/themeGallery.png) no-repeat; 
display:inline-block;
}
#frm_show_cal.theme_black-tie{ background-position: 0 0; } 
#frm_show_cal.theme_blitzer{ background-position: 0 -28px; } 
#frm_show_cal.theme_cupertino{ background-position: 0 -56px; } 
#frm_show_cal.theme_dark-hive{ background-position: 0 -84px; } 
#frm_show_cal.theme_dot-luv{ background-position: 0 -112px; } 
#frm_show_cal.theme_eggplant{ background-position: 0 -140px; } 
#frm_show_cal.theme_excite-bike{ background-position: 0 -168px; } 
#frm_show_cal.theme_flick{ background-position: 0 -196px; } 
#frm_show_cal.theme_hot-sneaks{ background-position: 0 -224px; } 
#frm_show_cal.theme_humanity{ background-position: 0 -252px; } 
#frm_show_cal.theme_le-frog{ background-position: 0 -280px; } 
#frm_show_cal.theme_mint-choc{ background-position: 0 -308px; } 
#frm_show_cal.theme_overcast{ background-position: 0 -336px; } 
#frm_show_cal.theme_pepper-grinder{ background-position: 0 -364px; } 
#frm_show_cal.theme_redmond{ background-position: 0 -392px; } 
#frm_show_cal.theme_smoothness{ background-position: 0 -420px; } 
#frm_show_cal.theme_south-street{ background-position: 0 -448px; } 
#frm_show_cal.theme_start{ background-position: 0 -476px; } 
#frm_show_cal.theme_sunny{ background-position: 0 -504px; } 
#frm_show_cal.theme_swanky-purse{ background-position: 0 -532px; } 
#frm_show_cal.theme_trontastic{ background-position: 0 -560px; } 
#frm_show_cal.theme_ui-darkness{ background-position: 0 -588px; } 
#frm_show_cal.theme_ui-lightness{ background-position: 0 -616px; } 
#frm_show_cal.theme_vader{ background-position: 0 -644px; } 
</style>


<script type="text/javascript">
//<![CDATA[
jQuery(document).ready(function($){ $("#datepicker_sample").datepicker(); frmUpdateCSS('<?php echo FrmProAppHelper::jquery_css_url($frmpro_settings->theme_css) ?>'); });
//function to append a new theme stylesheet with the new style changes
function updateCSS(locStr){
	jQuery("head").append('<link href="<?php echo FRM_SCRIPT_URL ?>&amp;controller=settings&amp;'+ locStr +'" type="text/css" rel="Stylesheet" class="frm-custom-theme"/>');
	if( jQuery("link.frm-custom-theme").size() > 3){
		jQuery("link.frm-custom-theme:first").remove();
	}
};

function frm_import_templates(thisid){
    jQuery('#'+thisid).replaceWith('<img id="' + thisid + '" src="<?php echo FRM_IMAGES_URL; ?>/wpspin_light.gif" alt="<?php _e('Loading...', 'formidable'); ?>" />');
    jQuery.ajax({type:"POST",url:"<?php echo FRM_SCRIPT_URL ?>",data:"controller=forms&frm_action=import",
        success:function(msg){ jQuery('#'+thisid).replaceWith('<?php _e('Templates Updated', 'formidable') ?>');}
    });
}

function frmSetPosClass(value){
if(value=='none') value='top';
jQuery('.frm_pos_container').removeClass('frm_top_container frm_left_container frm_right_container').addClass('frm_'+value+'_container');    
}
//]]>
</script>
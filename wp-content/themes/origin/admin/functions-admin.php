<?php
/*
 * Theme Settings
 *
 * @package Origin
 * @subpackage Template
 */
	
add_action( 'admin_menu', 'origin_theme_admin_setup' );

function origin_theme_admin_setup() {
    
	global $theme_settings_page;
	
	/* Get the theme settings page name */
	$theme_settings_page = 'appearance_page_theme-settings';

	/* Get the theme prefix. */
	$prefix = hybrid_get_prefix();

	/* Create a settings meta box only on the theme settings page. */
	add_action( 'load-appearance_page_theme-settings', 'origin_theme_settings_meta_boxes' );

	/* Add a filter to validate/sanitize your settings. */
	add_filter( "sanitize_option_{$prefix}_theme_settings", 'origin_theme_validate_settings' );
	
	/* Enqueue scripts */
	add_action( 'admin_enqueue_scripts', 'origin_admin_scripts' );
	
}

/* Adds custom meta boxes to the theme settings page. */
function origin_theme_settings_meta_boxes() {

	/* Add a custom meta box. */
	add_meta_box(
		'origin-theme-meta-box',			// Name/ID
		__( 'General settings', 'origin' ),	// Label
		'origin_theme_meta_box',			// Callback function
		'appearance_page_theme-settings',		// Page to load on, leave as is
		'normal',					// Which meta box holder?
		'high'					// High/low within the meta box holder
	);

	/* Add additional add_meta_box() calls here. */
}

/* Function for displaying the meta box. */
function origin_theme_meta_box() { ?>

	<table class="form-table">
	    
		<!-- Favicon upload -->
		<tr class="favicon_url">
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'origin_favicon_url' ); ?>"><?php _e( 'Favicon:', 'origin' ); ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id( 'origin_favicon_url' ); ?>" name="<?php echo hybrid_settings_field_name( 'origin_favicon_url' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'origin_favicon_url' ) ); ?>" />
				<input id="origin_favicon_upload_button" class="button" type="button" value="Upload" />
				<br />
				<span class="description"><?php _e( 'Upload favicon image (recommended max size: 32x32).', 'origin' ); ?></span>
				
				<?php /* Display uploaded image */
				if ( hybrid_get_setting( 'origin_favicon_url' ) ) { ?>
                    <p><img src="<?php echo hybrid_get_setting( 'origin_favicon_url' ); ?>" alt=""/></p>
				<?php } ?>
			</td>
		</tr>
		
		<!-- Logo upload -->
		<tr class="logo_url">
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'origin_logo_url' ); ?>"><?php _e( 'Logo:', 'origin' ); ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id( 'origin_logo_url' ); ?>" name="<?php echo hybrid_settings_field_name( 'origin_logo_url' ); ?>" value="<?php echo esc_attr( hybrid_get_setting( 'origin_logo_url' ) ); ?>" />
				<input id="origin_logo_upload_button" class="button" type="button" value="Upload" />
				<br />
				<span class="description"><?php _e( 'Upload logo image (recommended max width: 200px).', 'origin' ); ?></span>
				
				<?php /* Display uploaded image */
				if ( hybrid_get_setting( 'origin_logo_url' ) ) { ?>
                    <p><img src="<?php echo hybrid_get_setting( 'origin_logo_url' ); ?>" alt=""/></p>
				<?php } ?>
			</td>
		</tr>		
		
		<!-- Font family -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'origin_font_family' ); ?>"><?php _e( 'Font family:', 'origin' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo hybrid_settings_field_id( 'origin_font_family' ); ?>" name="<?php echo hybrid_settings_field_name( 'origin_font_family' ); ?>">
				<option value="Bitter" <?php echo hybrid_get_setting( 'origin_font_family', 'Bitter' ) == 'Bitter' ? 'selected="selected"' : '' ?>> <?php echo __( 'Bitter', 'origin' ) ?> </option>
				<option value="Georgia" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Georgia' ? 'selected="selected"' : '' ?>> <?php echo __( 'Georgia', 'origin' ) ?> </option>
				<option value="Droid Serif" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Droid Serif' ? 'selected="selected"' : '' ?>> <?php echo __( 'Droid Serif', 'origin' ) ?> </option>				
				<option value="Helvetica" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Helvetica' ? 'selected="selected"' : '' ?>> <?php echo __( 'Helvetica', 'origin' ) ?> </option>
				<option value="Istok Web" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Istok Web' ? 'selected="selected"' : '' ?>> <?php echo __( 'Istok Web', 'origin' ) ?> </option>
				<option value="Arial" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Arial' ? 'selected="selected"' : '' ?>> <?php echo __( 'Arial', 'origin' ) ?> </option>
				<option value="Verdana" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Verdana' ? 'selected="selected"' : '' ?>> <?php echo __( 'Verdana', 'origin' ) ?> </option>
				<option value="Lucida Sans Unicode" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Lucida Sans Unicode' ? 'selected="selected"' : '' ?>> <?php echo __( 'Lucida Sans Unicode', 'origin' ) ?> </option>
				<option value="Droid Sans" <?php echo hybrid_get_setting( 'origin_font_family' ) == 'Droid Sans' ? 'selected="selected"' : '' ?>> <?php echo __( 'Droid Sans', 'origin' ) ?> </option>
			    </select>
			</td>
		</tr>
		
		<!-- Font size -->
		<tr>
			<th>
			    <label for="<?php echo hybrid_settings_field_id( 'origin_font_size' ); ?>"><?php _e( 'Font size:', 'origin' ); ?></label>
			</th>
			<td>
			    <select id="<?php echo hybrid_settings_field_id( 'origin_font_size' ); ?>" name="<?php echo hybrid_settings_field_name( 'origin_font_size' ); ?>">
				<option value="16" <?php echo hybrid_get_setting( 'origin_font_size', '16' ) == '16' ? 'selected="selected"' : '' ?>> <?php echo __( 'default', 'origin' ) ?> </option>
				<option value="17" <?php echo hybrid_get_setting( 'origin_font_size', '17' ) == '17' ? 'selected="selected"' : '' ?>> <?php echo __( '17', 'origin' ) ?> </option>
				<option value="16" <?php echo hybrid_get_setting( 'origin_font_size', '16' ) == '16' ? 'selected="selected"' : '' ?>> <?php echo __( '16', 'origin' ) ?> </option>
				<option value="15" <?php echo hybrid_get_setting( 'origin_font_size' ) == '15' ? 'selected="selected"' : '' ?>> <?php echo __( '15', 'origin' ) ?> </option>
				<option value="14" <?php echo hybrid_get_setting( 'origin_font_size' ) == '14' ? 'selected="selected"' : '' ?>> <?php echo __( '14', 'origin' ) ?> </option>				
				<option value="13" <?php echo hybrid_get_setting( 'origin_font_size' ) == '13' ? 'selected="selected"' : '' ?>> <?php echo __( '13', 'origin' ) ?> </option>
				<option value="12" <?php echo hybrid_get_setting( 'origin_font_size' ) == '12' ? 'selected="selected"' : '' ?>> <?php echo __( '12', 'origin' ) ?> </option>
			    </select>
			    <span class="description"><?php _e( 'The base font size in pixels.', 'origin' ); ?></span>
			</td>
		</tr>		
	    
		<!-- Link color -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'origin_link_color' ); ?>"><?php _e( 'Link color:', 'origin' ); ?></label>
			</th>
			<td>
				<input type="text" id="<?php echo hybrid_settings_field_id( 'origin_link_color' ); ?>" name="<?php echo hybrid_settings_field_name( 'origin_link_color' ); ?>" size="8" value="<?php echo ( hybrid_get_setting( 'origin_link_color' ) ) ? esc_attr( hybrid_get_setting( 'origin_link_color' ) ) : '#dd5424'; ?>" data-hex="true" />
				<div id="colorpicker_link_color"></div>
				<span class="description"><?php _e( 'Set the theme link color.', 'origin' ); ?></span>
			</td>
		</tr>	    

		<!-- Custom CSS -->
		<tr>
			<th>
				<label for="<?php echo hybrid_settings_field_id( 'origin_custom_css' ); ?>"><?php _e( 'Custom CSS:', 'origin' ); ?></label>
			</th>
			<td>
				<textarea id="<?php echo hybrid_settings_field_id( 'origin_custom_css' ); ?>" name="<?php echo hybrid_settings_field_name( 'origin_custom_css' ); ?>" cols="60" rows="8"><?php echo wp_htmledit_pre( stripslashes( hybrid_get_setting( 'origin_custom_css' ) ) ); ?></textarea>
				<span class="description"><?php _e( 'Add your custom CSS here. It would overwrite any default or custom theme settings.', 'origin' ); ?></span>
			</td>
		</tr>

		<!-- End custom form elements. -->
	</table><!-- .form-table --><?php
	
}

/* Validate theme settings. */
function origin_theme_validate_settings( $input ) {
    
	$input['origin_favicon_url'] = esc_url_raw( $input['origin_favicon_url'] );
	$input['origin_logo_url'] = esc_url_raw( $input['origin_logo_url'] );
	$input['origin_font_family'] = wp_filter_nohtml_kses( $input['origin_font_family'] );
	$input['origin_font_size'] = wp_filter_nohtml_kses( $input['origin_font_size'] );
    $input['origin_link_color'] = wp_filter_nohtml_kses( $input['origin_link_color'] );      
    $input['origin_custom_css'] = wp_filter_nohtml_kses( $input['origin_custom_css'] );

    /* Return the array of theme settings. */
    return $input;
}

/* Enqueue scripts (and related stylesheets) */
function origin_admin_scripts( $hook_suffix ) {
    
    global $theme_settings_page;
	
    if ( $theme_settings_page == $hook_suffix ) {
	    
	    /* Enqueue Scripts */
	    wp_enqueue_script( 'origin_functions-admin', get_template_directory_uri() . '/admin/functions-admin.js', array( 'jquery', 'media-upload', 'thickbox', 'farbtastic' ), '1.0', false );

		/* Localize script strings */
		wp_localize_script( 'origin_functions-admin', 'js_text', array( 'insert_into_post' => __( 'Use this Image', 'origin' ) ) );	    
	    
	    /* Enqueue Styles */
	    wp_enqueue_style( 'origin_functions-admin', get_template_directory_uri() . '/admin/functions-admin.css', false, 1.0, 'screen' );
	    wp_enqueue_style( 'farbtastic' );
    }
}

?>
<?php

class FrmRegLogin extends WP_Widget {

	function FrmRegLogin() {
		$widget_ops = array( 'description' => __( 'Add a login form anywhere on your site', 'formidable') );
		$this->WP_Widget('frm_reg_login', __('Login Form', 'formidable'), $widget_ops);
	}

	function widget( $args, $instance ) {        
        extract($args);
        
        $defaults = array(
		    'slide' => false, 'form_id' => 'loginform', 
		    'label_username' => __( 'Username' ), 'label_password' => __( 'Password' ), 
		    'label_remember' => __( 'Remember Me' ), 'label_log_in' => __( 'Login' )
		);
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title']);
		foreach($defaults as $default => $default_val)
            $instance[$default] = isset($instance[$default]) ? $instance[$default] : $default_val;
        $instance['remember'] = isset($instance['remember']) ? $instance['remember'] : 0;
        $instance['style'] = isset($instance['style']) ? $instance['style'] : 0;
        
		echo $before_widget;
		if ( $title )
			echo $before_title . stripslashes($title) . $after_title;
			
		echo FrmRegAppController::login_form($instance);

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
	    $new_instance['remember'] = isset($new_instance['remember']) ? 1 : 0;
        $new_instance['style'] = isset($new_instance['style']) ? 1 : 0;
		return $new_instance;
	}

	function form( $instance ) { 
	    //Defaults
		$instance = wp_parse_args( (array) $instance, array(
		    'title' => false, 'remember' => true, 'slide' => false, 'style' => true, 'layout' => 'v',
		    'form_id' => 'loginform', 'label_username' => __( 'Username' ),
            'label_password' => __( 'Password' ), 'label_remember' => __( 'Remember Me' ),
            'label_log_in' => __( 'Login' )
		) );
?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'formidable') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr( stripslashes($instance['title']) ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('layout'); ?>"><?php _e('Display fields in', 'formidable') ?></label>
	<select id="<?php echo $this->get_field_id('layout'); ?>" name="<?php echo $this->get_field_name('layout'); ?>">
	    <option value="v" <?php selected($instance['layout'], 'v')?>><?php _e('multiple rows') ?></option>
	    <option value="h" <?php selected($instance['layout'], 'h')?>><?php _e('a single row') ?></option>
	</select></p>
	
	<p><input class="checkbox" type="checkbox" <?php checked($instance['style'], true) ?> id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>" value="1" />
	<label for="<?php echo $this->get_field_id('style'); ?>"><?php _e('Use Formidable Styling', 'formidable') ?></label></p>
	
	<p><input class="checkbox" type="checkbox" <?php checked($instance['slide'], true) ?> id="<?php echo $this->get_field_id('slide'); ?>" name="<?php echo $this->get_field_name('slide'); ?>" value="1" />
	<label for="<?php echo $this->get_field_id('slide'); ?>"><?php _e('Slide the login area', 'formidable') ?></label></p>
	
	<p><input class="checkbox" type="checkbox" <?php checked($instance['remember'], true) ?> id="<?php echo $this->get_field_id('remember'); ?>" name="<?php echo $this->get_field_name('remember'); ?>" value="1" />
	<label for="<?php echo $this->get_field_id('remember'); ?>"><?php _e('Include Remember Me checkbox', 'formidable') ?></label></p>
	
	<p><label for="<?php echo $this->get_field_id('label_username'); ?>"><?php _e('Username Label', 'formidable') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('label_username'); ?>" name="<?php echo $this->get_field_name('label_username'); ?>" value="<?php echo esc_attr( stripslashes($instance['label_username']) ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('label_password'); ?>"><?php _e('Password Label', 'formidable') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('label_password'); ?>" name="<?php echo $this->get_field_name('label_password'); ?>" value="<?php echo esc_attr( stripslashes($instance['label_password']) ); ?>" /></p>
	
	<p><label for="<?php echo $this->get_field_id('label_remember'); ?>"><?php _e('Remember Me Label', 'formidable') ?>:</label>
	<input type="text" class="widefat" id="<?php echo $this->get_field_id('label_remember'); ?>" name="<?php echo $this->get_field_name('label_remember'); ?>" value="<?php echo esc_attr( stripslashes($instance['label_remember']) ); ?>" /></p>

<?php 
	}
}

?>
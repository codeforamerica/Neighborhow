<?php

function do_this_front_end_js() {
	global $style_url;
	$style_url = get_bloginfo('stylesheet_directory');
	
	wp_enqueue_script('do-this', $style_url . '/lib/js/dothis.js', array( 'jquery' ) );
	if(!is_user_logged_in()) {
		wp_enqueue_script( 'jquery-cookies', $style_url. '/lib/js/jquery.cookie.js', array( 'jquery' ) );
	}
	wp_localize_script( 'do-this', 'do_this_vars', 
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('do-this-nonce'),
			'already_loved_message' => __('You have already indicated you want to do this.', 'do-this'),			
			'error_message' => __('Sorry, there was a problem processing your request.', 'do-this'),
			'logged_in' => is_user_logged_in() ? 'true' : 'false'
		) 
	);	
}
add_action('wp_enqueue_scripts', 'do_this_front_end_js');

?>
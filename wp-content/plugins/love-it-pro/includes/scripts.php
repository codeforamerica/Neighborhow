<?php

function lip_front_end_js() {
	wp_enqueue_script('love-it', LI_BASE_URL . '/includes/js/love-it.js', array( 'jquery' ) );
	if(!is_user_logged_in()) {
		wp_enqueue_script( 'jquery-coookies', LI_BASE_URL . '/includes/js/jquery.cookie.js', array( 'jquery' ) );
	}
	wp_localize_script( 'love-it', 'love_it_vars', 
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('love-it-nonce'),
			'already_loved_message' => __('You have already loved this item.', 'love_it'),
			'error_message' => __('Sorry, there was a problem processing your request.', 'love_it'),
			'logged_in' => is_user_logged_in() ? 'true' : 'false'
		) 
	);	
}
add_action('wp_enqueue_scripts', 'lip_front_end_js');

function lip_custom_css() {
	global $lip_options;
	if(isset($lip_options['custom_css']) && $lip_options['custom_css'] != '') {
		echo '<style type="text/css">' . $lip_options['custom_css'] . '</style>';
	}
}
add_action('wp_head', 'lip_custom_css');
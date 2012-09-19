<?php

function vote_front_end_js() {
	wp_enqueue_script('vote', NH_BASE_URL . '/includes/js/vote.js', array( 'jquery' ) );
	if(!is_user_logged_in()) {
		wp_enqueue_script( 'jquery-coookies', NH_BASE_URL . '/includes/js/jquery.cookie.js', array( 'jquery' ) );
	}
	wp_localize_script( 'vote', 'vote_vars', 
		array( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('vote-nonce'),
//			'already_voted_message' => __('You have already voted on this item.', 'vote'),
			'already_voted_message' => __('You have already voted for this.', 'vote'),			
			'error_message' => __('Sorry, there was a problem processing your request.', 'vote'),
			'logged_in' => is_user_logged_in() ? 'true' : 'false'
		) 
	);	
}
add_action('wp_enqueue_scripts', 'vote_front_end_js');
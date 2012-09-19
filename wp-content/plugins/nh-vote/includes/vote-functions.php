<?php

// check whether a user has voted on a post / iamge
function nh_user_has_voted_post($user_id, $post_id) {

	$voted = get_user_option('user_votes', $user_id);
	if(is_array($voted) && in_array($post_id, $voted)) {
		return true; // user has voted on post
	}
	return false; // user has not voted on post
}

// increments a vote count
function nh_mark_post_as_voted($post_id, $user_id) {

	$vote_count = get_post_meta($post_id, '_nh_vote_count', true);
	if($vote_count)
		$vote_count = $vote_count + 1;
	else
		$vote_count = 1;
	
	if(update_post_meta($post_id, '_nh_vote_count', $vote_count)) {
		if(is_user_logged_in()) {
			nh_store_voted_id_for_user($user_id, $post_id);
		}
		return true;
	}

	return false;
}

// adds the voted ID to the users meta so they can't vote again
function nh_store_voted_id_for_user($user_id, $post_id) {
	$voted = get_user_option('user_votes', $user_id);
	if(is_array($voted)) {
		$voted[] = $post_id;
	} else {
		$voted = array($post_id);
	}
	update_user_option($user_id, 'user_votes', $voted);
}

// returns a vote count for a post
function nh_get_vote_count($post_id) {
	$vote_count = get_post_meta($post_id, '_nh_vote_count', true);
	if($vote_count)
		return $vote_count;
	return 0;
}

// processes the ajax request
function nh_process_vote() {
	if ( isset( $_POST['item_id'] ) && wp_verify_nonce($_POST['vote_nonce'], 'vote-nonce') ) {
		if(nh_mark_post_as_voted($_POST['item_id'], $_POST['user_id'])) {
			echo 'voted';
		} else {
			echo 'failed';
		}
	}
	die();
}
add_action('wp_ajax_vote', 'nh_process_vote');
add_action('wp_ajax_nopriv_vote', 'nh_process_vote');
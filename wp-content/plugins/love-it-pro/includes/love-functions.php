<?php

// check whether a user has loved a post / iamge
function lip_user_has_loved_post($user_id, $post_id) {

	$loved = get_user_option('li_user_loves', $user_id);
	if(is_array($loved) && in_array($post_id, $loved)) {
		return true; // user has loved post
	}
	return false; // user has not loved post
}

// increments a love count
function lip_mark_post_as_loved($post_id, $user_id) {

	$love_count = get_post_meta($post_id, '_li_love_count', true);
	if($love_count)
		$love_count = $love_count + 1;
	else
		$love_count = 1;
	
	if(update_post_meta($post_id, '_li_love_count', $love_count)) {
		if(is_user_logged_in()) {
			lip_store_loved_id_for_user($user_id, $post_id);
		}
		return true;
	}

	return false;
}

// adds the loved ID to the users meta so they can't love it again
function lip_store_loved_id_for_user($user_id, $post_id) {
	$loved = get_user_option('lip_user_loves', $user_id);
	if(is_array($loved)) {
		$loved[] = $post_id;
	} else {
		$loved = array($post_id);
	}
	update_user_option($user_id, 'li_user_loves', $loved);
}

// returns a love count for a post
function lip_get_love_count($post_id) {
	$love_count = get_post_meta($post_id, '_li_love_count', true);
	if($love_count)
		return $love_count;
	return 0;
}

// processes the ajax request
function lip_process_love() {
	if ( isset( $_POST['item_id'] ) && wp_verify_nonce($_POST['love_it_nonce'], 'love-it-nonce') ) {
		if(lip_mark_post_as_loved($_POST['item_id'], $_POST['user_id'])) {
			echo 'loved';
		} else {
			echo 'failed';
		}
	}
	die();
}
add_action('wp_ajax_love_it', 'lip_process_love');
add_action('wp_ajax_nopriv_love_it', 'lip_process_love');
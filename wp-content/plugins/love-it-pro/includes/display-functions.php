<?php

// checks to see if Love It links should be shown automatically
function lip_show_links() {
	global $lip_options, $post;
	if(isset($lip_options['show_links']) && is_array($lip_options['post_types'])) {
		if(in_array(get_post_type($post), $lip_options['post_types'])) {
			add_filter('the_content', 'lip_display_love_link');
		}
	}
}
add_action('template_redirect', 'lip_show_links');

// adds the Love It link and count to post/page content
function lip_display_love_link($content) {

	global $lip_options, $post;

	// only show the link when user is logged in and on a singular page
	if( is_singular()) {

		// setup the Love It link text
		if(isset($lip_options['love_it_text']) && $lip_options['love_it_text'] != '') {
			$link_text = $lip_options['love_it_text'];
		} else {
			$link_text = __('Love It', 'love_it');
		}
		
		// setup the Already Loved This text
		if(isset($lip_options['already_loved']) && $lip_options['already_loved'] != '') {
			$already_loved = $lip_options['already_loved'];
		} else {
//			$already_loved = __('You have loved this', 'love_it');			

$user_profile_url = get_userdata($user_ID);
$already_loved = '<a href="'.$app_url.'/author/'.$user_profile_url->user_login.'" class="love-it">You Like This</a>';			
		}
	
		$link = lip_love_it_link($post->ID, $link_text, $already_loved, false);
		
		// append our "Love It" link to the item content.
		if(isset($lip_options['post_position']) && $lip_options['post_position'] == 'top') {
			$content = $link . $content;
		} else {
			$content = $content . $link;
		}
	}
	return $content;
}

function lip_love_it_link($post_id = null, $link_text, $already_loved, $echo = true) {
global $app_url;
$app_url = get_bloginfo('url');

	global $user_ID, $post;

	if(is_null($post_id)) {
		$post_id = $post->ID;
	}
	
	// retrieve the total love count for this item
	$love_count = lip_get_love_count($post_id);
	
	ob_start();
	
	// our wrapper DIV
//	echo '<div class="love-it-wrapper">';
	
		// only show the Love It link if the user has NOT previously loved this item
		if(!lip_user_has_loved_post($user_ID, $post_id)) {
			echo '<a title="Like this Guide" href="#" class="nh-btn-blue-love love-it" data-post-id="' . $post_id . '" data-user-id="' .  $user_ID . '">Like This</a>';
			
		} else {
			// show a message to users who have already loved this item
//			echo '<span class="loved">' . $already_loved . ' <span class="love-count">' . $love_count . '</span></span>';
			
			$user_profile_url = get_userdata($user_ID);
			echo '<a title="See your other Likes" href="'.$app_url.'/author/'.$user_profile_url->user_login.'" class="nhline">You Like This</a>';
		}
	
	// close our wrapper DIV
//	echo '</div>';
	
	if($echo)
		echo ob_get_clean();
	else
		return ob_get_clean();
}
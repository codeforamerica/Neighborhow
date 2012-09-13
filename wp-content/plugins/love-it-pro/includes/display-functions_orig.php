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
//			$link_text = __('Love It', 'love_it');
			$link_text = 'Like This';
		}
		
		// setup the Already Loved This text
		if(isset($lip_options['already_loved']) && $lip_options['already_loved'] != '') {
			$already_loved = $lip_options['already_loved'];
		} else {
			$already_loved = __('You have loved this', 'love_it');
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

	global $user_ID, $post;

	if(is_null($post_id)) {
		$post_id = $post->ID;
	}
	
	// retrieve the total love count for this item
	$love_count = lip_get_love_count($post_id);
	
	ob_start();
	
	// our wrapper DIV
	echo '<div class="love-it-wrapper">';
	
		// only show the Love It link if the user has NOT previously loved this item
		if(!lip_user_has_loved_post($user_ID, $post_id)) {
			echo '<a href="#" class="love-it" data-post-id="' . $post_id . '" data-user-id="' .  $user_ID . '">Like this</a> <span class="love-count">' . $love_count . '</span>';
		} else {
			// show a message to users who have already loved this item
			echo '<span class="loved">You like this <span class="love-count">' . $love_count . '</span></span>';
		}
	
	// close our wrapper DIV
	echo '</div>';
	
	if($echo)
		echo apply_filters('lip_links', ob_get_clean() );
	else
		return apply_filters('lip_links', ob_get_clean() );
}
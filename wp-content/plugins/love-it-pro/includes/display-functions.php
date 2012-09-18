<?php
// NEIGHBORHOW MODS INCLUDED
function lip_show_links() {
	global $lip_options, $post;
	if(isset($lip_options['show_links']) && is_array($lip_options['post_types'])) {
		if(in_array(get_post_type($post), $lip_options['post_types'])) {
			add_filter('the_content', 'lip_display_love_link');
		}
	}
}
add_action('template_redirect', 'lip_show_links');

function lip_display_love_link($content) {

	global $lip_options, $post;

	if( is_singular()) {

		if(isset($lip_options['love_it_text']) && $lip_options['love_it_text'] != '') {
			$link_text = $lip_options['love_it_text'];
		} else {
//			$link_text = __('Love It', 'love_it');
			$link_text = 'Like This';
		}
		
		if(isset($lip_options['already_loved']) && $lip_options['already_loved'] != '') {
			$already_loved = $lip_options['already_loved'];
		} else {
			$already_loved = __('You have already liked this', 'love_it');
		}
	
		$link = lip_love_it_link($post->ID, $link_text, $already_loved, false);
		
		if(isset($lip_options['post_position']) && $lip_options['post_position'] == 'top') {
			$content = $link . $content;
		} else {
			$content = $content . $link;
		}
	}
	return $content;
}

function lip_love_it_link($post_id = null, $link_text = null, $already_loved = null, $echo = true) {
	global $current_user;
	global $app_url;
	$app_url = get_bloginfo('url');
//	$already_loved = __('You have already liked this', 'love_it');
	
	global $user_ID, $post;

	if(is_null($post_id)) {
		$post_id = $post->ID;
	}
	
	$love_count = lip_get_love_count($post_id);
	
	ob_start();
	
		if (!lip_user_has_loved_post($user_ID, $post_id)) {
			echo '<a id="likethis" rel="tooltip" data-placement="bottom" href="#" data-title="<strong>Like this Neighborhow Guide</strong><br/>If you&#39;re signed in, Likes will be saved in your Profile." class="love-it nh-btn-blue" data-post-id="' . $post_id . '" data-user-id="' .  $user_ID . '">Like this</a>';
		} 
		else {
// Seems to duplicate php if/else on page ??			
			echo '<a id="likedthis" title="See your other Likes" href="'.$app_url.'/author/'.$current_user->user_login.'" class="likedthis nhline">You like this</a>';
		}
	
	if($echo)
		echo apply_filters('lip_links', ob_get_clean() );
	else
		return apply_filters('lip_links', ob_get_clean() );
}
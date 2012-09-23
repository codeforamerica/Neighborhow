<?php
// NEIGHBORHOW MODS INCLUDED
function nh_show_links() {
	global $nh_options, $post;
	if(isset($nh_options['show_links']) && is_array($nh_options['post_types'])) {
		if(in_array(get_post_type($post), $nh_options['post_types'])) {
			add_filter('the_content', 'nh_display_vote_link');
		}
	}
}
add_action('template_redirect', 'nh_show_links');

function nh_display_vote_link($content) {

	global $nh_options, $post;

	if( is_singular()) {

		if(isset($nh_options['vote_text']) && $nh_options['vote_text'] != '') {
			$link_text = $nh_options['vote_text'];
		} else {
//			$link_text = __('Vote', 'vote');
			$link_text = 'Vote';
		}
		
		if(isset($nh_options['already_voted']) && $nh_options['already_voted'] != '') {
			$already_voted = $nh_options['already_voted'];
		} else {
			$already_voted = __('You already voted for this', 'vote');
		}
	
		$link = nh_vote_it_link($post->ID, $link_text, $already_voted, false);
		
		if(isset($nh_options['post_position']) && $nh_options['post_position'] == 'top') {
			$content = $link . $content;
		} else {
			$content = $content . $link;
		}
	}
	return $content;
}

function nh_vote_it_link($post_id = null, $link_text = null, $already_voted = null, $echo = true) {
	global $current_user;
	global $app_url;
	$app_url = get_bloginfo('url');
//	$already_voted = __('You already voted on this', 'vote');
	
	global $user_ID, $post;

	if(is_null($post_id)) {
		$post_id = $post->ID;
	}
	
	$vote_count = nh_get_vote_count($post_id);
	
	ob_start();
	
		if (!nh_user_has_voted_post($user_ID, $post_id)) {
			echo '<a id="votethis"';
			if (!is_user_logged_in()) {
				echo 'rel="tooltip" data-placement="bottom" href="#" data-title="If you&#39;re signed in, Votes will be saved in your Profile."';
			}
			echo ' class="vote votethis nh-btn-blue" data-post-id="' . $post_id . '" data-user-id="' .  $user_ID . '">Vote</a>';
		} 
		else {
// Seems to duplicate php if/else on page ??			
			echo '<span class="byline"><a id="votedthis" title="See your other Votes" href="'.$app_url.'/author/'.$current_user->user_login.'" class="votedthis nhline">You voted</a></span>';
		}
	
	if($echo)
		echo apply_filters('nh_links', ob_get_clean() );
	else
		return apply_filters('nh_links', ob_get_clean() );
}
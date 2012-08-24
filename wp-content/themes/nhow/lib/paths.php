<?php

function get_bodyid() {

// 	PREP
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); 
$nh_term = $term->name;
$nh_tax = $term->taxonomy;

$tmpPage = get_page_template();

$nh_post_type = get_post_type();

	if (is_home()) { 
		$bodyid = 'home'; 
	}
	
	elseif (is_archive()) {
		if ($nh_post_type === 'nh_guides' AND !isset($nh_tax)) {
			$bodyid = 'guides';
		}
		elseif ($nh_tax === 'nh_cities') {
			$bodyid = 'cities';
		}
		elseif (in_category('blog')) {
			$bodyid = 'blog';
		}
		elseif (in_category('resources')) {
			$bodyid = 'resources';
		}
		elseif (in_category('stories')) {
			$bodyid = 'stories';
		}	
		elseif (is_author()) {
			$bodyid = 'author';
		}							
	}
	
	elseif (is_page()) {
		if (is_page('topics')) {
			$bodyid = 'topics';
		}
		elseif (is_page('cities')) {
			$bodyid = 'cities';
		}
	}
	
	elseif (is_single()) {
		if ($nh_post_type === 'post' AND in_category('blog')) {
			$bodyid = 'blog';
		}
		elseif ($nh_post_type === 'nh_guides') {
			$bodyid = 'guides';
		}
		elseif (in_category('resources')) {
			$bodyid = 'resources';
		}
		elseif (in_category('stories')) {
			$bodyid = 'stories';
		}		
	}
	
	elseif (is_search()) {
		$bodyid = 'search';
	}

	
			
	return $bodyid;
}
		
//STOP HERE
?>
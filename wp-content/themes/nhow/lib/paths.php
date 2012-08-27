<?php

function get_bodyid() {

// 	PREP
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); 
$nh_term = $term->name;
//$nh_term_clean = strtolower($nh_term);
//$nh_term_clean = str_ireplace(' ','-',$nh_term_clean);
$nh_tax = $term->taxonomy;

$tmpPage = get_page_template();

$nh_post_type = get_post_type();

	if (is_home()) { 
		$bodyid = 'home'; 
	}
	
	elseif (is_archive() AND !is_author()) {
		if ($nh_post_type === 'nh_guides' AND !isset($nh_tax)) {
			$bodyid = 'guides';
		}
		elseif ($nh_tax === 'nh_cities' AND !isset($nh_term)) {
			$bodyid = 'cities';
		}
		elseif ($nh_tax === 'nh_cities' AND isset($nh_term)) {
			$bodyid = 'cities '.$nh_term;
		}		
		elseif ($nh_post_type === 'post' AND in_category('blog')) {
			$bodyid = 'blog';
		}
		elseif ($nh_post_type === 'post' AND in_category('resources')) {
			$bodyid = 'resources';
		}
		elseif ($nh_post_type === 'post' AND in_category('stories')) {
			$bodyid = 'stories';
		}							
	}
	elseif (is_archive() AND is_author()) {
		$bodyid = 'account';
	}

	elseif (is_page()) {
		if (is_page('topics')) {
			$bodyid = 'topics';
		}
		elseif (is_page('cities')) {
			$bodyid = 'cities';
		}	
		elseif (is_page('profile')) {
			$bodyid = 'settings';
		}
	}
	
	elseif (is_single()) {
		if ($nh_post_type === 'post' AND in_category('blog')) {
			$bodyid = 'blog';
		}
		elseif ($nh_post_type === 'nh_guides') {
			$bodyid = 'guides';
		}
		elseif ($nh_post_type === 'post' AND in_category('resources')) {
			$bodyid = 'resources';
		}
		elseif ($nh_post_type === 'post' AND in_category('stories')) {
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
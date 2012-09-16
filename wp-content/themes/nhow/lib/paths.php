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
	
	elseif (is_archive() AND !is_author() AND !is_tax()) {
		if (in_category('guides')) {
			$bodyid = 'guides';
		}		
		elseif (is_category('blog')) {
			$bodyid = 'blog';
		}
		elseif (is_category('resources')) {
			$bodyid = 'resources';
		}
		elseif (is_category('stories')) {
			$bodyid = 'stories';
		}
		elseif (is_category('roadmap')) {
			$bodyid = 'roadmap';
		}
/*		elseif ($nh_tax === 'nh_cities' AND isset($nh_term)) {
			$bodyid = $nh_term;
		}
/*		elseif ($nh_tax == 'nh_cities' AND !isset($nh_term)) {
			$bodyid = 'cities';
		}							
*/		
	}
	elseif (is_archive() AND is_author()) {
		$bodyid = 'profile';
	}
	
	elseif (is_archive() AND is_tax('nh_cities')) {
		$bodyid = 'cities-'.$nh_term;
	}

	elseif (is_page()) {
		if (is_page('topics')) {
			$bodyid = 'topics';
		}
		elseif (is_page('cities')) {
			$bodyid = 'cities';
		}	
		// add Sign In + Sign Up and misc login files
		elseif (is_page('login')) {
			$bodyid = 'settings';
		}
	}
	
	elseif (is_single()) {
		if (in_category('blog')) {
			$bodyid = 'blog';
		}
		elseif (in_category('guides')) {
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
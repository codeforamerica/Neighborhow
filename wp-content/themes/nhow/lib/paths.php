<?php

function get_bodyid() {

// 	PREP
$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy')); 
$nh_term = $term->name;

$cat = get_the_category();
$cat_id = $cat[0]->term_id;
$cat_parent_id = $cat[0]->category_parent;
if ($cat_parent_id) {
	$cat_name = strtolower(get_the_category_by_id($cat_parent_id));
}
else {
	$cat_name = strtolower(get_the_category_by_id($cat_id));
}


	if (is_home()) { 
		$bodyid = 'home'; 
	}
	
	elseif (is_archive() AND !is_author() AND !is_tax()) {
		$bodyid = $cat_name;
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
		if (isset($cat_name)) {
			$bodyid = $cat_name;
		}	
	}
	
	elseif (is_search()) {
		$bodyid = 'search';
	}
			
	return $bodyid;
}
		
//STOP HERE
?>
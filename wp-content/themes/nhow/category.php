<?php
$post = $wp_query->post;
if (in_category('blog')) {
	include(TEMPLATEPATH.'/category-blog.php');	
}
elseif (in_category('resources')) {
	include(TEMPLATEPATH.'/category-resources.php');	
}
elseif (in_category('guides')) {
	include(TEMPLATEPATH.'/category-guides.php');	
}
elseif (in_category('ideas')) {
	include(TEMPLATEPATH.'/category-ideas.php');		
}
else {
	$ideas = get_cat_ID('ideas');
	$ideas_args = array('child_of' => $ideas);
	$ideas_categories = get_categories($ideas_args);
	foreach($ideas_categories as $category) { 
		if (in_category($category->name)) {
			include(TEMPLATEPATH.'/category-ideas-sub.php');
		}
	}
	
	$guides = get_cat_ID('guides');
	$guides_args = array('child_of' => $guides);
	$guides_categories = get_categories( $guides_args );
	foreach($guides_categories as $category) { 
		if (in_category($category->name)) {
			include(TEMPLATEPATH.'/category-guides-sub.php');
		}
	}
}
?>
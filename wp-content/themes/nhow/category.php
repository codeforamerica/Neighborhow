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
elseif (in_category('feedback')) {
	include(TEMPLATEPATH.'/category-feedback.php');		
}
else {
	$tmp = get_cat_ID('feedback');
	$args = array('child_of' => $tmp);
	$categories = get_categories( $args );
	foreach($categories as $category) { 
		if (in_category($category->name)) {
			include(TEMPLATEPATH.'/category-feedback-sub.php');
		}
	}
}
?>
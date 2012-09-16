<?php
$post = $wp_query->post;
if (in_category('blog')) {
	include(TEMPLATEPATH.'/single-blog.php');	
}
elseif (in_category('resources')) {
	include(TEMPLATEPATH.'/single-resources.php');	
}
elseif (in_category('guides')) {
	include(TEMPLATEPATH.'/single-guides.php');	
}
elseif (in_category('feedback')) {
	include(TEMPLATEPATH.'/single-feedback.php');		
}
else {
	$tmp = get_cat_ID('feedback');
	$args = array('child_of' => $tmp);
	$categories = get_categories( $args );
	foreach($categories as $category) { 
		if (in_category($category->name)) {
			include(TEMPLATEPATH.'/single-feedback-sub.php');
		}
	}
}
?>
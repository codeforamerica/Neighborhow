<?php
// WORDPRESS THEME FUNCTIONS
/* ---------DISABLE TOOLBAR ON FRONT END-----------------*/
remove_action('init', 'wp_admin_bar_init');
add_filter('show_admin_bar', '__return_false');


/* ---------MODIFY AUTO DRAFT-----------------*/
function Kill_Auto_Save() {
	wp_deregister_script('autosave');
}
add_action( 'wp_print_scripts', 'Kill_Auto_Save');


/*--------CHANGE MIME TYPE ICON LOCATION------------*/
function change_mime_icon($icon, $mime = null, $post_id = null){
    $icon = str_replace(get_bloginfo('wpurl').'/wp-includes/images/crystal/', WP_CONTENT_URL . '/themes/nhow/images/media/', $icon);
    return $icon;
}
add_filter('wp_mime_type_icon', 'change_mime_icon');


/*-------------GET CUSTOM FIELDS--------------------*/
function get_custom($tmpID,$string) {
	$custom_fields = get_post_custom($tmpID);
	$tmp = $custom_fields[$string];
	foreach ( $tmp as $key => $value )
	$string = $value;
	return $string;
}






/*end here*/
?>
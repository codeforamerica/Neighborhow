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
function get_custom($id,$string) {
	$custom_fields = get_post_custom($id);
	$tmp = $custom_fields[$string];
	foreach ( $tmp as $key => $value )
	$string = $value;
	return $string;
}


/*----------REGISTER GUIDES CUSTOM POST TYPE---------*/
function nh_register_guides_posttype() {
	$labels = array(
		'name' => _x( 'Guides', 'post type general name' ),
		'singular_name' => _x( 'Guide', 'post type singular name' ),
		'add_new' => _x( 'Add New', 'Guide'),
		'add_new_item' => __( 'Add New Guide '),
		'edit_item' => __( 'Edit Guide '),
		'new_item' => __( 'New Guide '),
		'view_item' => __( 'View Guide '),
		'search_items' => __( 'Search Guides '),
		'not_found' =>  __( 'No Guides found' ),
		'not_found_in_trash' => __( 'No Guides found in Trash' ),
		'parent_item_colon' => ''
	);

	$supports = array( 'title','editor','author','thumbnail','excerpt', 'trackbacks','custom-fields','comments','revisions','page-attributes' );

	$post_type_args = array(
		'labels' => $labels,
		'singular_label' => __( 'Guide' ),
		'public' => true,
		'show_ui' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'rewrite' => array( 'slug' => 'guides' ),
		'supports' => $supports,
		'menu_position' => 5,
		'taxonomies' => array( 'nh_cities','category','post_tag' ),
//		'menu_icon' => 'http://mydomain.com/wp-content/themes/lib/images/discbrakes-icon.png'
	 );
	 register_post_type( 'nh_guides' , $post_type_args );
}
add_action( 'init', 'nh_register_guides_posttype' );


/*------------REGISTER CITIES TAXONOMY------------*/
function nh_register_cities_tax() {
	$labels = array(
		'name' => _x( 'Cities', 'taxonomy general name' ),
		'singular_name' => _x( 'City', 'taxonomy singular name' ),
		'add_new' => _x( 'Add New City', 'City'),
		'add_new_item' => __( 'Add New City' ),
		'edit_item' => __( 'Edit City' ),
		'new_item' => __( 'New City' ),
		'view_item' => __( 'View City' ),
		'search_items' => __( 'Search Cities' ),
		'not_found' => __( 'No Cities found' ),
		'not_found_in_trash' => __( 'No City found in Trash' ),
	);

	$pages = array( 'nh_guides' );

	$args = array(
		'labels' => $labels,
		'singular_label' => __( 'City' ),
		'public' => true,
		'show_ui' => true,
		'hierarchical' => false,
		'show_tagcloud' => false,
		'show_in_nav_menus' => true,
		'menu_position' => 6,
		'rewrite' => array('slug' => 'cities'),
	 );
	register_taxonomy( 'nh_cities' , $pages , $args );
}
add_action( 'init' , 'nh_register_cities_tax' );


/*---------MODIFY REG LINKS-------------*/
add_filter(  'gettext',  'register_text'  );
add_filter(  'ngettext',  'register_text'  );
function register_text( $translated ) {
     $translated = str_ireplace(  'Register',  'Don&#39;t have an account? Sign Up for Neighborhow.',  $translated );
     return $translated;
}

add_filter(  'gettext',  'login_text'  );
add_filter(  'ngettext',  'login_text'  );
function login_text( $translated ) {
     $translated = str_ireplace(  'Login',  'Sign In',  $translated );
     return $translated;
}

add_filter(  'gettext',  'lost_password_text'  );
add_filter(  'ngettext',  'lost_password_text'  );
function lost_password_text( $translated ) {
     $translated = str_ireplace(  'Lost Password',  'Lost Your Password?',  $translated );
     return $translated;
}



/*---------GET AVATAR URL-------------*/

function nh_get_gravatar_url( $email ) {
    $hash = md5( strtolower( trim ( $email ) ) );
    return 'http://gravatar.com/avatar/' . $hash;
}

/*end here*/
?>
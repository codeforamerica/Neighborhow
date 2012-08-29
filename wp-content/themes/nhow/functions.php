<?php
// TODO
// check security on ADD NEW PROFILE FIELDS and in form pages			


// WORDPRESS THEME FUNCTIONS
/* ---------DISABLE TOOLBAR ON FRONT END-----------------*/
remove_action('init', 'wp_admin_bar_init');
add_filter('show_admin_bar', '__return_false');


/* ---------MODIFY AUTO DRAFT-----------------*/
function Kill_Auto_Save() {
	wp_deregister_script('autosave');
}
add_action( 'wp_print_scripts', 'Kill_Auto_Save');


/*-----ENABLE POST THUMBNAILS FOR THEME------------*/
if ( function_exists( 'add_theme_support' ) ) {
add_theme_support( 'post-thumbnails' );
}


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


/*---------ADD CITY / STATE TO USER PROFILE-------------*/
add_action( 'show_user_profile', 'nh_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'nh_show_extra_profile_fields' );

function nh_show_extra_profile_fields( $user ) { ?>
	<table class="form-table">
		<div class="form-item form-item-admin">
			<label class="nh-form-label label-admin" for="user_city">City</label>
			<input class="regular-text input-admin" type="text" name="user_city" id="user_city" value="<?php echo esc_attr( get_the_author_meta( 'user_city', $user->ID ) ); ?>" required />
			<br/><span class="help-block description help-block-admin <?php foreach ($nh_error_keys as $key) { if ($key == "empty_user_city" OR $key == "invalid_user_city") { echo 'nh-error'; }} ?>">City name is required and is publicly visible.</span>
		</div>		
	</table>	
<?php }


/*---------VALIDATE + SUBMIT USER CHANGES TO PROFILE -------------*/
function nh_save_extra_profile_fields( &$errors, $update, &$user ) {
	if($update) {

// FIRST NAME		
		if(empty($_POST['first_name'])) {
			$errors->add('empty_first_name', '<strong>ERROR</strong>: First name is required. Please type your first name.', array('form-field' => 'first_name'));
		}
		elseif (!empty($_POST['first_name'])) {
			$value_first_name = trim($_POST['first_name']);
			$value_first_name = sanitize_text_field($value_first_name);			
			if (strlen($value_first_name) > '16') {
				$errors->add('maxlength_first_name', '<strong>ERROR</strong>: Please enter a first name with 16 or fewer characters.', array('form-field' => 'first_name'));
			}
			if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_first_name)) {
				$errors->add('invalid_first_name', '<strong>ERROR</strong>: Invalid characters in first name. Please enter a first name using only letters, spaces, hyphens, or apostrophes.', array('form-field' => 'first_name'));
			}			
			else // do the update
			{
				update_user_meta($user->ID, 'first_name', $value_first_name);
			}
		}	
		
// LAST NAME		
		if(empty($_POST['last_name'])) {
			$errors->add('empty_last_name', '<strong>ERROR</strong>: Last name is required. Please type your last name.', array('form-field' => 'last_name'));
		}
		elseif (!empty($_POST['last_name'])) {
			$value_last_name = trim($_POST['last_name']);
			$value_last_name = sanitize_text_field($value_last_name);			
			if (strlen($value_last_name) > '30') {
				$errors->add('maxlength_last_name', '<strong>ERROR</strong>: Please enter a last name with 30 or fewer characters.', array('form-field' => 'last_name'));
			}
			if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_last_name)) {
				$errors->add('invalid_last_name', '<strong>ERROR</strong>: Invalid characters in last name. Please enter a last name using only letters, spaces, hyphens, or apostrophes.', array('form-field' => 'last_name'));
			}			
			else // do the update
			{
				update_user_meta($user->ID, 'last_name', $value_last_name);
			}
		}	
		
// DESCRIPTION - let WP handle char validation		
		if (!empty($_POST['description'])) {
			$value_description = trim($_POST['description']);

			if (strlen($value_description) > '200') {
				$errors->add('maxlength_description', '<strong>ERROR</strong>: Please enter a description with 200 or fewer characters.', array('form-field' => 'description'));
			}			
						
			else // do the update
			{
				update_user_meta($user->ID, 'description', $value_description);
			}
		}
	
// USER CITY		
		if(empty($_POST['user_city'])) {
			$errors->add('empty_user_city', '<strong>ERROR</strong>: City is required. Please enter a city name.', array('form-field' => 'user_city'));
		}
		elseif (!empty($_POST['user_city'])) {
			$value_user_city = trim($_POST['user_city']);
			$value_user_city = sanitize_text_field($value_user_city);			
			if (!preg_match("/^[a-zA-Z \\\'-]+$/", $value_user_city)) {
				$errors->add('invalid_user_city', '<strong>ERROR</strong>: Invalid characters used. Please enter a city name using only letters, space, hyphen, or apostrophe.', array('form-field' => 'user_city'));
			}
			else // do the update
			{
				update_user_meta($user->ID, 'user_city', $value_user_city);
			}
		}
	}
}
add_action('user_profile_update_errors', 'nh_save_extra_profile_fields', 10, 3);


/*---------	INCLUDE CUSTOM ADMIN CSS -------------*/
function admin_css() { 
	wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/lib/custom-admin.css' ); 
} 
add_action('admin_print_styles', 'admin_css' );


/*---------GET AVATAR URL-------------*/
function nh_get_avatar_url($get_avatar){
    preg_match("/<img src=\"(.*?)\"/i", $get_avatar, $matches);
    return $matches[1];
}


/*---------MODIFY LOGIN ERRORS-------------*/
/*add_filter('login_errors','login_error_message');

function login_error_message($error){
    //check if that's the error you are looking for
    $pwd = strpos($error, 'incorrect password');
    $name = strpos($error, 'invalid username');
    if ($pwd === false) {
        $error = "wrong pwd";
    }
	elseif ($name === false) {
        $error = "wrong username";
    }
    return $error;
}*/

/*---------MODIFY REG LINKS-------------*/
/*
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
*/


/*---------ENQUEUE SCRIPTS FOR USER AVATAR PLUGIN-------------*/
/*if(!is_admin()):
	wp_enqueue_script("thickbox");
	wp_enqueue_style("thickbox");
endif;
*/





/*end here*/
?>
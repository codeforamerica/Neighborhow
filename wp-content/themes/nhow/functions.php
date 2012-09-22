<?php
/* Neighborhow Functions */

add_theme_support('post-thumbnails');
//add_theme_support( 'qa_style' );

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


/*---------	INCLUDE CUSTOM ADMIN CSS -------------*/
function admin_css() { 
	wp_enqueue_style( 'admin_css', get_template_directory_uri() . '/lib/custom-admin.css' ); 
} 
add_action('admin_print_styles', 'admin_css' );


/*---------GET AVATAR URL-------------*/
function nh_get_avatar_url($get_avatar){
    preg_match("/src='(.*?)'/i", $get_avatar, $matches);
    return $matches[1];
}


/*-------------GET CUSTOM FIELDS--------------------*/
function get_custom($id,$string) {
	$custom_fields = get_post_custom($id);
	$tmp = $custom_fields[$string];
	foreach ( $tmp as $key => $value )
	$string = $value;
	return $string;
}


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
	$pages = array( 'post' );
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


/*------------REGISTER FEEDBACK TAXONOMY------------*/
/*function nh_register_feedback_tax() {
	$labels = array(
		'name' => _x( 'Feedback', 'taxonomy general name' ),
		'singular_name' => _x( 'Feedback', 'taxonomy singular name' ),
		'add_new' => _x( 'Add New Feedback', 'Feedback'),
		'add_new_item' => __( 'Add New Feedback' ),
		'edit_item' => __( 'Edit Feedback' ),
		'new_item' => __( 'New Feedback' ),
		'view_item' => __( 'View Feedback' ),
		'search_items' => __( 'Search Feedback' ),
		'not_found' => __( 'No Feedback found' ),
		'not_found_in_trash' => __( 'No Feedback found in Trash' ),
	);
	$pages = array( 'post' );
	$args = array(
		'labels' => $labels,
		'singular_label' => __( 'Feedback' ),
		'public' => true,
		'show_ui' => true,
		'hierarchical' => false,
		'show_tagcloud' => false,
		'show_in_nav_menus' => true,
		'menu_position' => 6,
		'rewrite' => array('slug' => 'feedback'),
	 );
	register_taxonomy( 'nh_feedback' , $pages , $args );
}
add_action( 'init' , 'nh_register_feedback_tax' );
*/

/*--------- CREATE / EDIT GUIDE FUNCTIONS -------*/
// Show users city as placeholder on create guide
// used in Formidable Create Guide form
add_filter('frm_get_default_value', 'nh_city_default', 10, 2);
function nh_city_default($new_value, $field){
	global $current_user;
	get_currentuserinfo();
	if($field->id == 162){ 
		$user_city = get_user_meta($current_user->ID,'user_city',true);
		$new_value = $user_city;
	}
	return $new_value;
}

// Validate FRM forms
add_filter('frm_validate_field_entry', 'nh_validate_frm', 20, 3);

function nh_validate_frm($errors, $posted_field, $posted_value) {
// Check guide titles	
	if ($posted_field->id == 158 OR $posted_field->id == 169 OR $posted_field->id == 174 OR $posted_field->id == 180 OR $posted_field->id == 185 OR $posted_field->id == 190 OR $posted_field->id == 195 OR $posted_field->id == 200 OR $posted_field->id == 205 OR $posted_field->id == 210 OR $posted_field->id == 215 OR $posted_field->id == 220 OR $posted_field->id == 225 OR $posted_field->id == 230 OR $posted_field->id == 234 OR $posted_field->id == 240) { 
		if (strlen($posted_value) > 50 AND !empty($posted_value)) {
			$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Please enter a title that is fewer than 50 characters.';
		}
		if (!preg_match("/^[a-zA-Z0-9 \\\',-]+$/", $posted_value) AND !empty($posted_value)) {
			$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Invalid characters. Please enter a title using only letters, space, comma, hyphen, and apostrophe.';	
		}
	}
// Check guide descriptions - not checking special chars
// to allow newline and html - let WP handle this for now
		if ($posted_field->id == 159 OR $posted_field->id == 170 OR $posted_field->id == 175 OR $posted_field->id == 181 OR $posted_field->id == 186 OR $posted_field->id == 191 OR $posted_field->id == 196 OR $posted_field->id == 201 OR $posted_field->id == 206 OR $posted_field->id == 211 OR $posted_field->id == 216 OR $posted_field->id == 221 OR $posted_field->id == 226 OR $posted_field->id == 239 OR $posted_field->id == 238 OR $posted_field->id == 241) { 
			$words = explode(' ', $posted_value);
			$count = count($words);
			if ($count > 250 AND !empty($posted_value)) {
				$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Please enter a description that is fewer than 250 words.';
			}
		}	
// Check guide user city name - allow multiple cities
// TODO - add JS autocomplete pull from nh_cities but 
// let user enter a new one
// also let user select multiple cities
		if ($posted_field->id == 162 AND !empty($posted_value)) { 
/*			if (strlen($posted_value) > 25) {
				$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Please enter a city name that is fewer than 25 characters.';
			}
*/			
			if (!preg_match("/^[a-zA-Z \\\',-]+$/", $posted_value) AND !empty($posted_value)) {
				$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Invalid characters. Please enter city names using only letters, space, hyphen, comma, and apostrophe.';	
			}
		}			
// Guide media uploads 
// - Formidable checks for type + max size		

// Feedback Title
		if ($posted_field->id == 706 AND !empty($posted_value)) { 
			if (strlen($posted_value) > 50 AND !empty($posted_value)) {
				$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Please enter a title that is fewer than 50 characters.';
			}
			if (!preg_match("/^[a-zA-Z0-9 \\\',-]+$/", $posted_value) AND !empty($posted_value)) {
				$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Invalid characters. Please enter a title using only letters, numbers, space, hyphen, comma, and apostrophe.';	
			}
		}
// Feedback Description	- WP stripping bad chars	
		if ($posted_field->id == 704 AND !empty($posted_value)) { 
			$words = explode(' ', $posted_value);
			$count = count($words);			
			if ($count > 250 AND !empty($posted_value)) {
				$errors['field'. $posted_field->id] = '<strong>ERROR</strong>: Please enter a description that is fewer than 250 words.';
			}
		}
		
return $errors;
}


/*--------- GET FRM KEY FROM POST ID -------*/
function nh_get_frm_entry_key ($post_id) {
	global $frmdb, $wpdb, $post;
	$item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $post_id ."'");	
	return $item_key;
}

/*--------- GET FRM ID FROM FRM KEY -------*/
function nh_get_frm_key_id ($item_key) {
	$result = mysql_query("SELECT id FROM nh_frm_items WHERE item_key = '".$item_key."'");
	$row = mysql_fetch_row($result);
	$entry_id = $row[0];	
	return $entry_id;
}

/*--------- GET POST ID FROM FRM ID -------*/
function nh_get_frm_id_post_id ($item_id) {
	$result = mysql_query("SELECT post_id FROM nh_frm_items WHERE id = '".$item_id."'");
	$row = mysql_fetch_row($result);
	$entry_post_id = $row[0];	
	return $entry_post_id;
}

/*------- GET CAT ID --------------*/
function get_category_id($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->term_id;
}

/*------- GET AUTHOR POST COUNT -----------*/
function nh_get_user_posts_count($user_id,$args) {  
    $args['author'] = $user_id;
    $args['fields'] = 'ids';
    $ps = get_posts($args);
    return count($ps);
}


/*------ CREATE / EDIT GUIDE REDIRECTS -----*/
// Redirect Create to Edit page on submit
// Using ref=X to display custom message 
// on Edit page - better way ??
add_action('frm_redirect_url', 'nh_redirect_frm', 9, 3);
function nh_redirect_frm($url, $form, $params){
	global $frm_entry;
	$app_url = get_bloginfo('url');		
	$tmp = $_POST['frm_user_id'];
	$user_info = get_userdata($tmp);
	$item_key = $_POST['item_key'];
	$user_login = $user_info->user_login;

	if($form->id == 9 and $params['action'] == 'create'){ 
		$url = $app_url.'/edit-guide?entry='.$item_key.'&action=edit&ref=create';
	}
	if($form->id == 9 and $params['action'] == 'update'){
		$url = $app_url.'/edit-guide?entry='.$item_key.'&action=edit&ref=update';
	}
return $url;
}

/*------- SUBMIT GUIDE FOR REVIEW --------------*/
function nh_show_publish_button($entry_post_id){
	global $post;
	$app_url = get_bloginfo('url');
	$item_key = $_GET['entry'];	
	
	$url = $app_url.'/edit-guide?entry='.$item_key.'&action=edit&ref=review';	
	echo '<form name="front_end_publish" method="POST" action="'.$url.'">';
	echo '<input type="hidden" name="pid" id="pid" value="'.$entry_post_id.'">
	<input type="hidden" name="fe_review" id="fe_review" value="fe_review">
	<input class="nh-btn-green" type="submit" name="submitreview" id="submitreview" value="Publish Guide" title="Publish this Guide">
	</form>';
}
// Change the post status
function nh_change_post_status($post_id,$status){
	$current_post = get_post( $post_id, 'ARRAY_A' );
	$current_post['post_status'] = $status;
	wp_update_post($current_post);
}
// Handle the submit
if (isset($_POST['fe_review']) && $_POST['fe_review'] == 'fe_review'){
	if (isset($_POST['pid']) && !empty($_POST['pid'])){
		nh_change_post_status((int)$_POST['pid'],'pending');
	}
}

// SAVE POST AS DRAFT EVERY TIME USER CLICKS IT
add_action('frm_submit_button_action', 'nh_save_as_draft');
function nh_save_as_draft($form){
	global $post;
	$item_key = $_GET['entry'];

	$tmp_item_id = nh_get_frm_key_id ($item_key);
	$tmp_post_id = nh_get_frm_id_post_id ($tmp_item_id);

	if($form->id == 9 AND $_GET['ref'] == 'update') {
		$current_post = get_post( $tmp_post_id, 'ARRAY_A' );
		$current_post['post_status'] = $status;
		wp_update_post($current_post);
  	}
}

/*------- DELETE GUIDE FROM FRONT END -----------*/
function nh_frontend_delete_link($postid) {
// Changes post status to trash
// Doesnt actually delete the post or attachments	
	$url = add_query_arg(
		array(
		'action'=>'nh_frontend_delete',
		'post'=>$postid
		)
	);
	$nonce = 'nh_frontend_delete_' . $postid;
	echo  '<a onclick="return confirm(\'Delete Guide is a permanent action that cannot be undone. Are you sure you want to delete this content?\')" href="'.wp_nonce_url($url,$nonce).'"><button class="nh-btn-blue">Delete Guide</button></a>';
}

if ( isset($_REQUEST['action']) && $_REQUEST['action']=='nh_frontend_delete' ) {
	add_action('init','nh_frontend_delete_post');
}

function nh_frontend_delete_post() {
	$post_id = (isset($_REQUEST['post']) ?  (int) $_REQUEST['post'] : 0);
	// No post? Oh well..
	if ( empty($post_id) )
		return;	
	if ( ! current_user_can('delete_post',$post_id) )
		return;
	check_admin_referer('nh_frontend_delete_'.$post_id, '_wpnonce');
	// Delete post
	wp_trash_post( $post_id );
	// Redirect
	$redirect = content_url($app_url.'/edit-guide?ref=delete');
	wp_redirect( $redirect );
	exit;
}


/*------- ADD DEFAULT POST VOTE F FEEDBACK --------*/


/*------- SINGLE TEMPLATES -----------*/
// Get post cat slug + find single-[cat slug].php
/*add_filter('single_template', create_function(
	'$the_template',
	'foreach( (array) get_the_category() as $cat ) {
		if ( file_exists(TEMPLATEPATH . "/single-{$cat->slug}.php") )
		return STYLESHEETPATH . "/single-{$cat->slug}.php"; }
	return $the_template;' )
);
*/



/* ---------MODIFY COMMENT DISPLAY-----------------*/
if ( ! function_exists( 'nh_comment' ) ) :
function nh_comment( $comment, $args, $depth ) {
	global $style_url;
	$app_url = get_bloginfo('url');
	
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
?>
<li class="post pingback">
	<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'nhow' ), '<span class="edit-link">', '</span>' ); ?></p>
<?php
	break;
default :
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<div class="comment-author vcard">
<?php
$avatar_size = 36;
if ( '0' != $comment->comment_parent )
$avatar_size = 36;
echo get_avatar( $comment, $avatar_size );
?>

<?php //edit_comment_link( __( 'Edit', 'nhow' ), '<span class="edit-link">', '</span>' ); ?>
	</div><!-- .comment-author .vcard -->

<?php if ( $comment->comment_approved == '0' ) : ?>
	<div class="comment-moderation"><?php _e( 'Hey, this is your first comment! It&#39;s being moderated and will be approved shortly. After that you won&#39;t need to wait for approval.', 'nhow' ); ?></div>
<?php endif; ?>
	<div class="comment-content">
<?php 
comment_text(); 
echo '<p class="comment-meta"><!--span class="comment-author-mod"-->';
$comment_author_id = get_comment(get_comment_ID())->user_id;
$comment_author_username = get_userdata($comment_author_id);
echo '<span class="byline">by</span> ';
if (!empty($comment_author_username)) {
	echo '<a href="'.$app_url.'/author/'.$comment_author_username->user_login.'" title="View author&#39s profile">'.get_comment_author().'</a>';
}
else {
	comment_author();
}
echo '</span>';
echo '<span class="comment-time"><span class="byline">added</span> '.nh_time_comment().'&nbsp;&nbsp;';
echo comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'])));
echo comment_action_links(get_comment_ID());
echo '</span></p>';
?>
	</div>
<?php
break;
endswitch;
}
endif; // ends check for nh_comment()


/* --- REMOVE WEBSITE FIELD FROM COMMENTS ----*/
add_filter('comment_form_default_fields', 'nh_comment_url');
function nh_comment_url($fields)
{
	if(isset($fields['url'])) {
		unset($fields['url']);
	}	
	return $fields;
}


/* ---------MODIFY POST TIMESTAMP-----------------*/
//add_filter('the_time', 'nhow_time_post'); //don't use filter cause overrides the_time() everywhere
function nh_time_post() {
  global $post;
  $date = $post->post_date;
  $time = get_post_time('G', true, $post);
  $mytime = time() - $time;
  if($mytime > 0 && $mytime < 7*24*60*60)
    $mytimestamp = sprintf(__('%s ago'), human_time_diff($time));
  else
    $mytimestamp = date(get_option('date_format'), strtotime($date));
  return $mytimestamp;
}

function nh_time_comment() {
  global $post;
  $date = $post->post_date;
  $time = get_comment_time('G', true, $post);
  $mytime = time() - $time;
  if($mytime > 0 && $mytime < 7*24*60*60)
    $mytimestamp = sprintf(__('%s ago'), human_time_diff($time));
  else
    $mytimestamp = date(get_option('date_format'), strtotime($date));
  return $mytimestamp;
}

function nh_time_ago( $type = 'post' ) {
	$d = 'comment' == $type ? 'get_comment_time' : 'get_post_time';
	return human_time_diff($d('U'), current_time('timestamp')) . " " . __('ago');
}


/* ---------MODERATE FROM FRONT END-----------------*/
function comment_action_links($id) {
	if (current_user_can('edit_post')) {
    echo '&nbsp;|&nbsp;<a class="comment-actions" href="'.admin_url("comment.php?action=editcomment&c=$id").'">Edit</a>';
	echo '&nbsp;|&nbsp;<a class="comment-actions" href="'.admin_url("comment.php?action=cdc&c=$id").'">Delete</a>';
    echo '&nbsp;|&nbsp;<a class="comment-actions" href="'.admin_url("comment.php?action=cdc&dt=spam&c=$id").'">Spam</a>';
  }
}


/*---ADD NHLINE CLASS TO AUTHOR LINK-----*/
add_filter('the_author_posts_link', 'nh_the_author_posts_link');
function nh_the_author_posts_link()
{
	global $authordata;
	global $app_url;
	$app_url = get_bloginfo('url');
	$author_name = $authordata->first_name.' '.$authordata->last_name;
	$link = '<a class="nhline" href="'.$app_url.'/author/'.$authordata->user_login.'" title="See posts by '.$author_name.'">'.$author_name.'</a>'; 	
	return $link;
}


/*--- EXCERPT FUNCTIONS -----*/
function nh_continue_reading_link() {
	return ' <a class="more-link" href="'. esc_url( get_permalink() ) . '">' . __( '[<span class="more-link">continue</span> <span class="meta-nav">&raquo;</span>]', 'nhow' ) . '</a>';	
}

function nh_auto_excerpt_more( $more ) {
	return ' &hellip;' . nh_continue_reading_link();
}
add_filter( 'excerpt_more', 'nh_auto_excerpt_more' );

function nh_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= nh_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'nh_custom_excerpt_more' );

/* Get "excerpt" outside loop - uses post content instead */
function get_excerpt_by_id($post_id){
	$the_post = get_post($post_id);
	$the_excerpt = $the_post->post_content;
	$excerpt_length = 35;
	$the_excerpt = strip_tags(strip_shortcodes($the_excerpt));
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);
	if(count($words) > $excerpt_length) :
		array_pop($words);
		array_push($words, '…');
		$the_excerpt = implode(' ', $words);
	endif;
	$the_excerpt = '<p>' . $the_excerpt . '</p>';
	return $the_excerpt;
}


/* GET PAGE ID BY SLUG */
function get_ID_by_slug($page_slug) {
	$page = get_page_by_path($page_slug);
	if ($page) {
		return $page->ID;
	} 
	else {
		return null;
	}
}

/* NOT USING ? */
add_filter('frm_before_display_content', 'add_stuff', 20, 2);
function add_stuff($content, $display){
	make_clickable($content); 
 return $content;
}


	
	
	


//STOP HERE
?>
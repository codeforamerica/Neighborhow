<?php /* Template Name: page-edit-guide */ 
 
// This WP PAGE doesnt know who the author of
// FRM Entries is or any info about FRM POSTS
// bc the author of this PAGE is Admin and the
// PAGE Post ID is the PAGE Post ID.
// We are using the URL to assume the FRM Entry Key
// From there we get the FRM Entry ID and from there
// we get the Entry's POST ID.

// TODO - Add display lists for other post types 

if (get_magic_quotes_gpc()) { 'magic here'; }

$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>

<?php get_header();?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">
	<div class="wrapper">	
		<div id="main">			
			<div id="content">
<h3 class="page-title">Edit Your Content</h3>

<?php
// Get location
/*$tmp = $_SERVER['REQUEST_URI'];
$uri = parse_url($tmp);
$base = $uri['query'];*/

// Get current user
global $current_user;
/*$nh_viewer_id = $current_user->ID;
echo 'viewer- '.$nh_viewer_id;*/

// Get the entry info
$item_key = $_GET['entry'];
$item_id = nh_get_frm_key_id($item_key);
$item_post_id = nh_get_frm_id_post_id($item_id);
$entry_info = get_post($item_post_id);
/*$entry_status = $entry_info->post_status;
$entry_author_id = $entry_info->post_author;*/

//echo ' item id- '.$item_id.' item post id- '.$item_post_id.' entry key- '.$item_key;
//echo '<br/> post status- '.$entry_status.' post author id- '.$entry_author_id.'<br/>';

// Prep buttons
$btn_delete = '<li style="float:right;"><a onclick="return confirm(\'Delete Guide is a permanent action that cannot be undone. Are you sure you want to delete this content?\')" href="'.get_delete_post_link($item_post_id).'"><button class="nh-btn-orange">Delete Guide</button></a></li>';

$btn_preview = '<li style="float:right;margin-left:1em;"><a href="'.$app_url.'/?post_type=nh_guides&p='.esc_attr($item_post_id).'&preview=true" title="See what your Guide will look like" target="_blank"><button class="nh-btn-orange">Preview Guide</button></a></li>';
?>


<?php
$mypost = get_post($item_post_id);

if ($current_user->ID == $mypost->post_author AND is_user_logged_in()) {
// LOGGED IN AND IS AUTHOR
	if ($mypost->post_status == 'draft') {
		echo 'draft';
		if ($_GET['ref'] == 'create') {	
			echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Thank you for writing a Neighborhow Guide!</strong><p>Your Guide has been saved as a Draft, so you can keep working on it until you&#39;re ready to publish.</div>';
		}
		elseif ($_GET['ref'] == 'update') {	
			echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Changes to this Guide were saved!</strong></div>';
		}				
		echo '<div class="block-instruct"><p class="instructions">When you&#39;re ready to publish this Neighborhow Guide, click "Publish Guide." Neighborhow Editors will email you when it&#39;s been posted  so you can share the link with your friends!</p></div>';
		echo '<ul>';
		echo $btn_preview;
		echo $btn_delete;
		echo '<li style="float:left;">';
		$button = nh_show_publish_button($item_post_id);
		echo '</li>';
		echo '</ul>';
		echo '<div style="clear:both;"></div>';
		echo do_shortcode('[formidable id=9]');	
	}

	elseif ($mypost->post_status == 'pending') {
		echo 'pending';
		if ($_GET['ref'] == 'review') {
			echo '<div class="alert alert-success"><strong>This Neighborhow Guide was submitted for review!</strong>';
			echo '<p class="instructions">Neighborhow Editors will quickly review your Guide. Then they&#39;ll email you when it&#39;s posted so you can share the link with your friends</p></div>';
		}
		else {
			echo '<div class="block-instruct"><p class="instructions"><strong>This Neighborhow Guide is being reviewed.</strong></p>';
			echo '<p class="instructions">Neighborhow Editors will email you when it&#39;s posted so you can share the link with your friends</p></div>';
		}
		echo '<div class="block-instruct"><p class="instructions">Click "Preview" to see what it will look like when it&#39;s published. If you want to work on another Guide, select it from the list on the right.</p>';
		echo '<p><a href="'.$app_url.'/?post_type=nh_guides&p='.$item_post_id.'&preview=true" title="See what it will look like" target="_blank"><button class="nh-btn-orange">Preview Guide</button></a></p></div>';		
	}

	elseif ($mypost->post_status == 'publish') {
		echo 'publish';
		echo '<div class="block-instruct"><p class="instructions"><strong>This <a href="'.get_permalink($item_post_id).'" title="View your Neighborhow Guide" target="_blank">Neighborhow Guide</a> has been published!</strong></p>';
		echo '<p class="instructions">To make changes, edit the content and click "Save Guide." Then click "Publish Guide" to send it back to Neighborhow Editors for review.</p></div>';
		echo '<ul>';
		echo $btn_preview;
		echo $btn_delete;
		echo '<li style="float:left;">';
		$button = nh_show_publish_button($item_post_id);
		echo '</li>';
		echo '</ul>';
		echo '<div style="clear:both;"></div>';
		echo do_shortcode('[formidable id=9]');		
	}
}
// LOGGED IN AND NOT AUTHOR
elseif ($current_user->ID != $mypost->post_author AND is_user_logged_in()) {
	echo 'not the author';
	$gdeargs = array(
	'author' => $current_user->ID,
	'post_type' => 'nh_guides',
	'post_status' => array('publish','draft','pending')
	);
	$gde_query = new WP_Query($gdeargs); 
	if ($gde_query->have_posts()) {
		echo '<div class="block-instruct"><p class="instructions">Looking for your Neighborhow Guides? Use the menu on the right to select an item to edit.</p></div>';	
	}
	else {
		echo '<div>You haven&#39;t created any Neighborhow Guides yet. <a href="'.$app_url.'/create-guide" title="Create a Neighborhow Guide">Get started</a> now, or <a href="'.$app_url.'/guides" title="Explore Neighborhow Guides">explore some other Guides</a>.</div>';
	}		
}
// NOT LOGGED IN
elseif (!is_user_logged_in()) {
	echo '<div class="block-instruct"><p class="instructions">Please <a href="<?php echo $app_url;?>/signin" title="Sign In now">sign in</a> to edit content.</p></div>';
}
wp_reset_query();
?>

			</div><!--/ content-->	

<div id="sidebar-nh" class="sidebar-nh">
<div class="widget-side">

<?php
if (is_user_logged_in()) {
	$myargs = array(
	'author' => $current_user->ID,
	'post_type' => 'nh_guides',
	'post_status' => array('publish','draft','pending')
	);
	$myquery = new WP_Query($myargs); 
	if ($myquery->have_posts()) {
		echo 'my posts';
	}
}

elseif (!is_user_logged_in()) {
	echo 'not logged in';
}
?>

</div><!--/ widget copy-->	
</div><!--/ widget-->
</div><!--/ sidebar-->			
			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
<?php /* Template Name: page-edit-guide */  
echo '<pre>';
print_r($_POST);
echo '</pre>';

$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
$nh_viewer_id = $current_user->ID;

/*$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
$curr_author = $_GET['auth'];
*/
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

<?php if (is_user_logged_in()) : ?>

<?php // if url has query
$tmp = $_SERVER['REQUEST_URI'];
$uri = parse_url($tmp);
$base = $uri['query'];
if (!empty($base)) : ?>

<p>Instructions</p>

<p><a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Send for Review</a></p>
<?php 
//show_publish_button($entry_id); ?>
here it is
<?php //echo do_shortcode('[submitreview]'); ?>

<?php
if ($_GET['ref'] == 'create') {
	echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Thank you for writing a Neighborhow Guide!</strong><p>Your Guide has been saved as a Draft, so you can keep working on it until you&#39;re ready to send it for review.</p><p>When you click the "Send for Review" button, Neighborhow Editors will quickly review your Guide. Then they&#39;ll email you when it&#39;s posted so you can share the link with your friends.</p></div>';
	}
elseif ($_GET['ref'] == 'update') {
	echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Changes to your draft were saved!</strong><p>When you&#39;re ready, click "Send for Review" to send your Guide to our editors, and they&#39;ll email you when your Guide has been published.</p></div>';
}	
?>

<?php echo do_shortcode('[formidable id=9]'); ?>
<?php
else :
	echo '<p>Please select an item from the right.</p>';
endif; // if query
else :
	echo '<p>You must be <a href="'.$app_url.'/signin">signed in</a> to edit content.</p>';
endif; // if logged in
?>
			</div><!--/ content-->

<div id="sidebar-nh" class="sidebar-nh">
<div class="widget-side">
<?php if (is_user_logged_in()) : ?>	
<?php
$type = 'nh_guides';
$gdeargs = array(
	'author' => $nh_viewer_id,
	'post_type' => $type,
	'post_status' => array('publish','draft','pending'),
	'paged' => $paged,
	'posts_per_page' => -1,
	'orderby' => 'date',
	'order' => DESC
);
$gdetemp = $wp_query; //assign ordinal for later use  
$gde_query = null;
$gde_query = new WP_Query($gdeargs); 
if ( $gde_query->have_posts() ):
?>		
<h5 class="widget-title">Neighborhow Guides</h5>
<div class="widget-copy">
<ul>
<?php while( $gde_query->have_posts() ) : $gde_query->the_post();?>
<li>
<?php 
global $frmdb, $wpdb, $post;
$item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $post->ID ."'");
?>
<a href="<?php echo $app_url;?>/edit-guide?action=edit&amp;entry=<?php echo esc_attr($item_key);?>"><?php the_title();?></a> <span>Status: <?php echo ucwords(get_post_status());?> Last saved:<?php the_modified_date('j M Y');?> at <?php the_modified_date('g: i a');?></span>	
</li>
<?php 
endwhile; // end while nh_guides
?>
</ul>
<?php 
else : ?>
	<div>do something here for logged in users with no guides and/or other content</div>
<?php 
endif; // end if nh_guides
wp_reset_query();
?>
<?php 
// TODO - Add display lists for other post types 
?>
<?php else : ?>
	<div>do something here for logged out users</div>
<?php endif; // if logged in
?>
</div>			
</div><!--/ widget-->
</div><!--/ sidebar-->

		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
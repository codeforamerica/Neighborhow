<?php /* Template Name: page-edit-guide */  
//echo '<pre>';
//print_r($_POST);
//print_r($_GET);
//echo '</pre>';

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

<?php // USER IS LOGGED IN
if (is_user_logged_in()) : ?>

<?php // URL HAS QUERY
$tmp = $_SERVER['REQUEST_URI'];
$uri = parse_url($tmp);
$base = $uri['query'];
if (!empty($base)) : ?>

<?php
// Get key from url - tmp solution
// need to get it from frm somehow
$item_key = $_GET['entry'];
$item_id = nh_get_frm_entry_post_id($item_key);
?>
	
<?php
$instructions = '<p>When you&#39;re ready to publish this Neighborhow Guide, click "Send for Review." Neighborhow Editors will email you when it&#39;s been posted  so you can share the link with your friends.</p>';
?>

<?php
// After create guide
if ($_GET['ref'] == 'create') : ?>
<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Thank you for writing a Neighborhow Guide!</strong><p>Your Guide has been saved as a Draft, so you can keep working on it until you&#39;re ready to publish.</p><!--p>When you click the "Send for Review" button, Neighborhow Editors will quickly review your Guide. Then they&#39;ll email you when it&#39;s posted so you can share the link with your friends.</p--></div>
<p><?php echo $instructions;?></p>
<p><?php $button = nh_show_publish_button($item_id);?></p>
<?php echo do_shortcode('[formidable id=9]'); ?>

<?php
// After editing
elseif ($_GET['ref'] == 'update') :
?>	
<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Changes to your draft were saved!</strong></div>
<p><?php echo $instructions;?></p>
<p><?php $button = nh_show_publish_button($item_id);?></p>
<?php echo do_shortcode('[formidable id=9]'); ?>

<?php
// After submitting for review
elseif ($_GET['ref'] == 'review') :
?>
<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Your Neighborhow Guide was submitted for review!</strong><p>Click "Preview" to see what it will look like when it's published.</p><p>If you want to work on another Guide, select it from the list on the right.</p></div>
<p>preview button here<?php //$button = nh_show_publish_button_disabled();?></p>

<?php
// All else
else :
?>
<p><?php echo $instructions;?></p>
<p><?php $button = nh_show_publish_button($item_id);?></p>
<?php echo do_shortcode('[formidable id=9]'); ?>
<?php
endif;
?>


<?php
else : // URL HAS NO QUERY
	echo '<p>Please select an item from the right.</p>';
endif; // END IF URL HAS QUERY
else :
	echo '<p>You must be <a href="'.$app_url.'/signin">signed in</a> to edit content.</p>';
endif; // END USER IS LOGGED IN
?>
			</div><!--/ content-->

<div id="sidebar-nh" class="sidebar-nh">
<div class="widget-side">
<?php // USER IS LOGGED IN
if (is_user_logged_in()) :
// Get users guides
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
// GUIDES EXIST ?
if ( $gde_query->have_posts() ): 
?>		
<h5 class="widget-title">Neighborhow Guides</h5>
<div class="widget-copy">
<ul>
<?php 
// USER HAS GUIDES
while( $gde_query->have_posts() ) : 
$gde_query->the_post();?>
<li>
<?php 
global $frmdb, $wpdb, $post;
$item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $post->ID ."'");
?>
<a href="<?php echo $app_url;?>/edit-guide?action=edit&amp;entry=<?php echo esc_attr($item_key);?>"><?php the_title();?></a> <span>Status: <?php echo ucwords(get_post_status());?> Last saved:<?php the_modified_date('j M Y');?> at <?php the_modified_date('g: i a');?></span>	
</li>
<?php 
// END USER HAS GUIDES
endwhile; 
?>
</ul>
<?php 
// GUIDES DONT EXIST
else :
?>
	<div>do something here for logged in users with no guides and/or other content</div>
<?php 
// END GUIDES EXIST ?
endif;
wp_reset_query();
?>
<?php 
// TODO - Add display lists for other post types 
?>
<?php 
// USER IS NOT LOGGED IN
else :
?>
	<div>do something here for logged out users</div>
<?php 
// END USER IS NOT LOGGED IN
endif;
?>
</div>			
</div><!--/ widget-->
</div><!--/ sidebar-->

		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
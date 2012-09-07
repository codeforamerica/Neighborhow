<?php /* Template Name: page-edit-guide */ 
 
// This WP PAGE doesnt know who the author of
// FRM Entries is or any info about FRM POSTS
// bc the author of this PAGE is Admin and the
// PAGE Post ID is the PAGE Post ID.
// We are using the URL to assume the FRM Entry Key
// From there we get the FRM Entry ID and from there
// we get the Entry's POST ID.

//echo '<pre>';
//print_r($_POST);
//print_r($_GET);
//echo '</pre>';
if (get_magic_quotes_gpc()) {
	'magic here';
}

$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
$nh_viewer_id = $current_user->ID;
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
$item_key = $_GET['entry'];
$item_id = nh_get_frm_key_id($item_key);
$item_post_id = nh_get_frm_id_post_id($item_id);
$entry_info = get_post($item_post_id);
$entry_status = $entry_info->post_status;
//echo 'here'.$entry_status;
?>
	
<?php
$btn_delete = '<li style="float:right;><a href="" title="Delete this Guide"><button class="nh-btn-orange">Delete Guide</button></a></li>';

$btn_preview = '<li style="float:right;margin-left:1em;"><a href="'.$app_url.'/?post_type=nh_guides&p='.esc_attr($item_post_id).'&preview=true" title="See what your Guide will look like" target="_blank"><button class="nh-btn-orange">Preview Guide</button></a></li>';
?>

<?php
// GUIDE IS DRAFT - STATUS SET BY SYSTEM
if ($entry_status == 'draft') :
// DRAFT CREATED
	if ($_GET['ref'] == 'create') : ?>
<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Thank you for writing a Neighborhow Guide!</strong><p>Your Guide has been saved as a Draft, so you can keep working on it until you&#39;re ready to publish.</p></div>
<?php 
// DRAFT HAS BEEN EDITED
	elseif ($_GET['ref'] == 'update') :
?>
<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Changes to this Guide were saved!</strong></div>
<?php
	endif;
// DRAFT BASE	
?>
<p class="instructions">When you&#39;re ready to publish this Neighborhow Guide, click "Publish Guide." Neighborhow Editors will email you when it&#39;s been posted  so you can share the link with your friends!</p>
<ul>
<?php 
echo $btn_preview;
echo $btn_delete; ?>
<li style="float:left;"><?php $button = nh_show_publish_button($item_post_id);?></li>
</ul>
<div style="clear:both;"></div>
<?php echo do_shortcode('[formidable id=9]');?>
<?php
endif;
?>

<?php
// GUIDE IS PENDING - STATUS SET BY USER
if ($entry_status == 'pending') :
// JUST SUBMITTED	
	if ($_GET['ref'] == 'review') : ?>
<div class="alert alert-success"><strong>This Neighborhow Guide was submitted for review!</strong>
<p class="instructions">Neighborhow Editors will quickly review your Guide. Then they&#39;ll email you when it&#39;s posted so you can share the link with your friends</p>
<p class="instructions">Click "Preview" to see what it will look like when it's published. If you want to work on another Guide, select it from the list on the right.</p></div>
<p><a href="<?php echo $app_url;?>/?post_type=nh_guides&p=<?php echo $item_post_id;?>&preview=true" title="See what it will look like" target="_blank"><button class="nh-btn-orange">Preview Guide</button></a></p> 
<?php
// OTHER ACTIONS - CREATE/UPDATE/ANY
	else : ?>
<p class="instructions"><strong>This Neighborhow Guide is being reviewed.</strong></p>
<p class="instructions">Neighborhow Editors will email you when it&#39;s posted so you can share the link with your friends</p>
<p class="instructions">Click "Preview" to see what it will look like when it's published. If you want to work on another Guide, select it from the list on the right.</p>
<p><a href="<?php echo $app_url;?>/?post_type=nh_guides&p=<?php echo $item_post_id;?>&preview=true" title="See what it will look like" target="_blank"><button class="nh-btn-orange">Preview Guide</button></a></p>
<?php
	endif; 
endif;
?>

<?php
// GUIDE IS PUBLISHED - PUBLISHED STATUS SET BY EDITOR
// PUBLISHED - same as draft functionality 
if ($entry_status == 'publish') :
?>
<p class="instructions"><strong>This <a href="<?php echo get_permalink($item_post_id);?>" title="View your Neighborhow Guide" target="_blank">Neighborhow Guide</a> has been published!</strong></p>
<p class="instructions">To make changes, edit the content and click "Save Guide." Then click "Publish Guide" to send it back to Neighborhow Editors for review.</p>
<ul>
<?php 
echo $btn_preview;
echo $btn_delete;
?>
<li style="float:left;"><?php $button = nh_show_publish_button($item_post_id);?></li>	
</ul>
<div style="clear:both;"></div>
<?php echo do_shortcode('[formidable id=9]'); ?>
<?php 
endif;
?>

<?php
else : // URL HAS NO QUERY
	echo '<p>Use the menu on the right to select an item to edit.</p>';
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
$item_key_by_post = nh_get_frm_entry_key($post->ID);
$pub_date = get_the_modified_date('j M Y');
$pub_time = get_the_modified_time('g: i a');
// GUIDE IS PENDING
if ($post->post_status === 'pending') :
?>	
<span class="pending"><?php the_title();?></span> <span>Submitted on <?php echo $pub_date;?> and pending review. When it&#39;s published, you&#39;ll be able to edit it again. <a href="<?php echo $app_url;?>/?post_type=nh_guides&p=<?php echo $post->ID;?>&preview=true" title="See what it will look like" target="_blank">Preview</a> it here.</span>
<?php
// GUIDE IS PUBLISHED
elseif ($post->post_status === 'publish') :
?>
<a href="<?php echo $app_url;?>/edit-guide?action=edit&entry=<?php echo esc_attr($item_key_by_post);?>"><?php the_title();?></a><span>Status: <?php echo ucwords(get_post_status());?> Last saved:<?php echo $pub_date;?> at <?php echo $pub_time;?></span>
<?php
// ALL ELSE
else :
?>
<a href="<?php echo $app_url;?>/edit-guide?action=edit&entry=<?php echo esc_attr($item_key_by_post);?>"><?php the_title();?></a><span>Status: <?php echo ucwords(get_post_status());?> Last saved:<?php echo $pub_date;?> at <?php echo $pub_time;?></span>
<?php
endif;
?> 
	
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
	<div>do something here for logged in users with no guides -- start creating and exploring</div>
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
	<div>do something here for logged out users - sign in or sign up</div>
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
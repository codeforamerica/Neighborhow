<?php /* Template Name: page-edit-guide */  
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
echo $curauth;
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

<p>Instructions</p>

<p><a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Send for Review</a></p>
			
<div class="alert alert-success">
</div>			

<p>Status: <?php echo ucwords(get_post_status());?></p>

<p>Last saved: <?php the_modified_date('j M Y');?> at <?php the_modified_date('g:i a');?></p>

<?php echo do_shortcode('[formidable id=9]'); ?>
		
			</div><!--/ content-->

<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Some links for authors</h5>
		<div class="widget-copy">
			
<?php
$type = 'nh_guides';
$args=array(
	'post_type' => $type,
	'post_status' => array('publish','draft','pending'),
	'paged' => $paged,
	'posts_per_page' => -1,
	'orderby' => 'date',
	'order' => DESC
// 'ignore_sticky_posts'=> 1
);
$temp = $wp_query; // assign ordinal query for later use  
$wp_query = null;
$wp_query = new WP_Query($args); 
			
if ( $wp_query->have_posts() ):
while( $wp_query->have_posts() ) : $wp_query->the_post();
?>

<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
<?php global $frmdb, $wpdb, $post;
$item_key = $wpdb->get_var("SELECT item_key FROM $frmdb->entries WHERE post_id='". $post->ID ."'");?>
<a href="<?php echo $app_url;?>/edit-guide?action=edit&entry=<?php echo $item_key;?>">Edit entry</a>	
	
<?php
// get the post type for each post
$posttype = get_post_type( $post->ID );
if ( $posttype) {
echo '(' . $posttype . ')';
} ?>
</li>
<?php
endwhile;
endif;
wp_reset_query();
?>

		
			
			<div><?php //do_shortcode('[display-frm-data id=display-edit-guide]');?>
			</div>
<?php 
//echo do_shortcode('[frm-entry-links id=9  field_key=guide-title logged_in=1 edit=1]'); 
?>
		</div>			
	</div><!--/ widget-->
</div><!--/ sidebar-->
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
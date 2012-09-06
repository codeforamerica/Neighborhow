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
		
			</div><!--/ content-->

<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Some links for authors</h5>
		<div class="widget-copy">
			
<?php
global $post;
rewind_posts();

$wpcust = new WP_Query(
array(
'post_type' => array(
'nh_guides',
),
'showposts' => '25' )
);
// the $wpcust-> variable is used to call the Loop methods. not sure if required
if ( $wpcust->have_posts() ):
while( $wpcust->have_posts() ) : $wpcust->the_post();
?>

<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
<?php
// get the post type for each post
$posttype = get_post_type( $post->ID );
if ( $posttype) {
echo '(' . $posttype . ')'; // display what each post is in parenthesis
} ?>
</li>
<?php
endwhile;  // close the Loop
endif;
wp_reset_query(); // reset the Loop
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
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
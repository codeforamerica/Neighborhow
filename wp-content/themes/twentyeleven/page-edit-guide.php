<?php
/* Template Name: page-edit-guide */

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

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
<?php get_sidebar('edit-content'); ?>			
			<div id="content">
<?php
$nhow_authorID = $posts[0]->post_author;
$nhow_postID = $post->ID;
$nhow_authorAlt = 'Photo of '.get_the_author(); 
//echo get_avatar($nhow_authorID,30,'',$nhow_authorAlt);
//echo '&nbsp;&nbsp;';
//the_author_posts_link();
?>
<?php //while ( have_posts() ) : the_post(); ?>

<?php //the_content(); ?>
			<p>Instructions</p>
			<p><a href="<?php echo $app_url;?>/" class="nh-btn-orange">Save Draft</a> <a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Submit for Review</a></p>
			<p>Status: <?php echo ucwords(get_post_status());?></p>
			<p>Last saved: <?php the_modified_date('j M Y');?> at <?php the_modified_date('g:i a');?></p>

<?php echo do_shortcode('[formidable id=9]'); ?>

<?php //endwhile;?>
		
			</div><!--/ content-->
			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
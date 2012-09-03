<?php
/*
Template Name: page-create-guide
*/

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

?>
<?php get_header();?>
<div class="row-fluid row-breadcrumbs">
	<div class="wrapper">
		<div id="nhbreadcrumb">
<?php //nhow_breadcrumb(); ?>
		</div>
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->

<div class="row-fluid row-content">
	<div class="wrapper">	
		<div id="main">

<?php
$nhow_authorID = $posts[0]->post_author;
$nhow_postID = $post->ID;
$nhow_authorAlt = 'Photo of '.get_the_author(); 
echo get_avatar($nhow_authorID,30,'',$nhow_authorAlt);
echo '&nbsp;&nbsp;';
the_author_posts_link();
?>
<?php while ( have_posts() ) : the_post(); ?>

<?php echo do_shortcode('[formidable id=7]'); ?>

<?php the_content(); ?>

<?php endwhile;?>
		
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/createguide.js"></script>

<?php get_footer(); ?>		

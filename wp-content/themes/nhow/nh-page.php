<?php
/*
Template Name: nh-page
*/
?>
	
<?php get_header();?>

<div class="row-fluid row-content">	
	<div id="main">
		<div id="content">
			
<?php while ( have_posts() ) : the_post(); ?>

<?php the_content(); ?>

<?php endwhile;?>
			
		</div><!--/ content-->
<?php get_sidebar('home'); ?>
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

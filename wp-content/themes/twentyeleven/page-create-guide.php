<?php /* Template Name: page-create-guide */ ?>
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
				<h3 class="page-title">Create a Neighborhow Guide</h3>
<?php

?>
<?php while ( have_posts() ) : the_post(); ?>

<?php echo do_shortcode('[formidable id=9]'); ?>

<?php the_content(); ?>

<?php endwhile;?>

			</div><!--/ content-->
<?php get_sidebar('home'); ?>		
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
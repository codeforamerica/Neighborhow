<?php
/*
Template Name: page-signin
*/
?>
	
<?php get_header();?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div id="main">

<?php while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile;?>
		
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

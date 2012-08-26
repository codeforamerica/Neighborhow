<?php
/*
Template Name: page-login
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
		<!--div id="content">
			<div class="backto"><a href="" title="">&#60; back to Your Profile</a>
			</div-->

<?php while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile;?>

		<!--/div></ content-->
<?php //get_sidebar('login-profile'); ?>		
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

<?php
/*
Template Name: page-login
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
<?php
if (is_user_logged_in()) {
	get_sidebar('login-profile');
}
else {
	get_sidebar('login-base');
}
?>		
		
		
<?php //get_sidebar('login'); ?>



	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

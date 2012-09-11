<?php /* Template Name: page-create-guide */ ?>
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
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
				<h3 class="page-title">Create a Neighborhow Guide</h3>
<?php

?>
<?php while ( have_posts() ) : the_post();
if (is_user_logged_in()) {
	echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>We&#39;re working on improving this form.</strong><p>For now, you are limited to 15 steps, so you might want to plan out your Guide content before you start entering it.</p><p>For each step, Title and Description are required, and you can also add an optional photo. To add another step, use the "Add Another Step" toggle.</p><p>Note: If you try to save with a step that has no Title or Description, you&#39;ll get an error. So be sure that you close any steps that you don&#39;t want to submit.</div>';
	echo do_shortcode('[formidable id=9]');
	//the_content();
}
elseif (!is_user_logged_in()) {
	echo 'Please <a href="'.$app_url.'/signin" title="Sign In">sign in</a> to create a Neighborhow Guide.';
}
endwhile;?>

			</div><!--/ content-->
<?php //get_sidebar('home'); ?>		
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
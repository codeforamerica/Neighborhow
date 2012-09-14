<?php /* Template Name: page-create-guide */ ?>
<?php
//echo '<pre>';
//print_r($_SERVER);
//print_r($_POST);
//print_r($_GET);
//print_r($_FILES);
//echo '</pre>';
?>
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
<?php while ( have_posts() ) : the_post();
if (is_user_logged_in()) {
	echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">×</a><strong>Share Your Neighborhow!</strong><p>A Neighborhow Guide can be about anything you think would be useful to other people in your community. Maybe that&#39;s how to organize a block party. Maybe it&#39;s how to get a free backyard tree from the city. Maybe it&#39;s how to track blighted properties in your neighborhood.</p><p>If it&#39;s something you know how to do, it&#39;s probably something other people want to know how to do. Share it!</div>';
//	echo '<p class="message">'.$new_message.'</p>';
	echo '<div id="create-gde">'.do_shortcode('[formidable id=9]').'</div>';
}
elseif (!is_user_logged_in()) {
	echo 'Please <a href="'.$app_url.'/signin" title="Sign In">sign in</a> to create a Neighborhow Guide.';
	echo '<p>More here about benefits</p>';
}
endwhile;?>

			</div><!--/ content-->
<?php
if (is_user_logged_in()) {
	get_sidebar('create-guide');
}
?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>		
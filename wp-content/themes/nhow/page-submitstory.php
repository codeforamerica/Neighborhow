<?php /* Template Name: page-submitstory */ ?>
<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			<div id="content">
				<h3 class="page-title">Submit Your Story to Neighborhow</h3>
<?php
if ($_POST['frm_action'] == 'create') {}
else {
?>						
				<p>There are a lot of great stories out there about people working to improve their neighborhoods and communities. And we want to document and celebrate those on Neighborhow.</p>
				<p>If you (or someone you know) has a story to share, please fill out the form below. We&#39;ll review the submissions and get in touch to gather the content we need to publish your story.</p>
				<p>We hope to hear from you soon!</p>	
<?php } ?>	
				<div id="contactus">
<?php
if (have_posts()) :
while (have_posts()) :
the_post();
the_content();
endwhile;
endif;
?>				
				</div>				
			</div><!--/ content-->

<?php get_sidebar('misc');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
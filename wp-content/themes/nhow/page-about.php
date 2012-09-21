<?php /* Template Name: page-about */ ?>
<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			<div id="content" class="about">
				<h3 class="page-title">Neighborhow &#8212; making it easy to find and share ways to improve your neighborhood.</h3>
<?php
if (have_posts()) :
while (have_posts()) :
the_post();
the_content();
endwhile;
endif;
?>			
			</div><!--/ content-->

<?php get_sidebar('about');?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>
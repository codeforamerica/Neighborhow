<?php /* Template Name: page-terms */ ?>
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
				<h3 class="page-title">Terms of Service</h3>		
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
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
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
echo '<p style="text-align:center;"><a style="padding:0 2em 0 1em;" href="http://www.codeforamerica.org" title="Go to Code for America" target="_blank"><img src="'.$style_url.'/images/logo_cfa_color.png" alt="Code for America logo" /></a> <a style="padding:0 1em 0 2em;" href="http://www.phila.gov" title="Go to City of Philadelphia" target="_blank"><img src="'.$style_url.'/images/logo_phl_color.png" alt="City of Philadelphia logo" /></a></p>';
endwhile;
endif;
?>			
			</div><!--/ content-->

<?php get_sidebar('about');?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>
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
<h3 class="page-title"><?php the_title();?></h3>
	
<?php 
if ( have_posts() ) :
while ( have_posts() ) : the_post(); 
?>	
		
<div><?php the_content();?></div>

<?php
if (!is_preview()) {
?>
<div id="leavecomment" class="nhow-comments">
<?php comments_template( '', true ); ?>
</div><!-- / comments-->				
<?php
}
?>
<?php
endwhile;
endif;
?>			

			</div><!--/ content -->
<?php 
if (!is_preview()) {
	get_sidebar('feedback-single');	
}
?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer(); ?>
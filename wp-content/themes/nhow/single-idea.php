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
		
<div><?php the_content();?>
<?php
$guide_answer = get_post_meta($post->ID,'gde-answer',true);
if ($guide_answer) {
	echo '<p class="comment-meta"><span class="answered"><a href="'.$guide_answer.'" title="View this Guide">Answered in this Guide!</a></span></p>';
}
?>	
</div>

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
	get_sidebar('idea-single');	
}
?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer(); ?>
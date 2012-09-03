<?php get_header(); ?>

<div id="container-int" class="container-int">sdfsd
	<div class="row-fluid">
		<div class="span12">
<?php //nhow_breadcrumb(); ?>
		</div>
	</div>
	<div class="row-fluid">
<?php 
if ( have_posts() ) :
while ( have_posts() ) : the_post(); 
$nhow_authorID = $posts[0]->post_author;
$nhow_postID = $post->ID;
?>			
		<div class="span8">
			<h1 class="page-title" style="margin-bottom:2em;"><?php the_title();?></h1>
			<div class="content content-page">
<?php echo 'content: '.get_the_content().'<br/>';?>	

<?php //echo do_shortcode('[display-frm-data id=1]'); ?>

<?php
$thumbnail_id = get_post_thumbnail_id($post->ID);

$img_feature_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

$img_feature_caption = get_post($thumbnail_id)->post_title;

echo '<br/>guide image: <img src="'. get_bloginfo('stylesheet_directory').'/lib/timthumb.php?src='. $img_feature_src[0].'&h=300&q=100&zc=1" alt="Photo of '.$img_feature_caption.'">';

echo '<br/>'.$img_feature_caption.'<br/>';

$user_city = get_post_meta($post->ID,'gde-user-city',true);
echo $user_city;

$guide_steps = get_post_meta($post->ID,'gde-steps',true);
$guide_steps = unserialize($guide_steps);
foreach ($guide_steps as $key => $value) {
	$step_title = $value[0];
	$step_description = $value[1];	
	$step_image = $value[2];
	
	echo '<p>'.$step_title.'</p>';
	echo '<p>'.$step_description.'</p>';	
	echo '<p>'.$step_image.'</p>';	
}





 



?>

<?php 
// get users city and make it the placeholder for the input form
?>

	
				<div class="tabbable">

					<div class="tab-content">
						<div class="tab-pane active" id="tab1">							
							<div class="guide-tab-inner">


								<div class="single-guide-img overview">
									<div class="carousel-inner"><!--img src="<?php bloginfo('stylesheet_directory');?>/lib/timthumb.php?src=<?php //echo $img_feature_src[0];?>&h=300&q=100&zc=1" alt="Photo of <?php //echo $step['title'];?>" /-->
										<div class="carousel-caption single-caption">
											<h4><?php //echo $img_feature_caption;?></h4>
										</div>
									</div>
								</div>							
							</div><!-- /guide-tab-inner-->
						</div><!-- /tab pane-->


					</div><!--/tab-content-->
				</div><!--/tabbable-->
				<div class="nhow-comments">
<?php comments_template( '', true ); ?>
<?php
endwhile;
endif;
?>				</div><!--/content-->
			</div>
		</div>				
			
		<div class="span4" style="margin-top:0em;">
<?php get_sidebar('guides'); ?>
		</div><!--/span-->
	</div><!--/row-->
</div><!--/container-->
<?php get_footer(); ?>
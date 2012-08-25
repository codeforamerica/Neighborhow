<?php get_header(); ?>

<div id="container-int" class="container-int">
	<div class="row-fluid">
		<div class="span12">

		</div>
	</div>
	<div class="row-fluid">
			<div id="nhbreadcrumb">
		<?php nhow_breadcrumb(); ?>
			</div>		
<?php 
if ( have_posts() ) :
while ( have_posts() ) : the_post(); 

?>			
		<div class="span8">
			<h1 class="page-title" style="margin-bottom:2em;"><?php the_title();?></h1>
			<div class="content content-page">
<?php if (post_password_required()) {
	echo the_content();	
}
else {
?>						
				<div class="tabbable">
					<ul class="nav2 nav-tabs">
						<li class="active"><a href="#tab1" data-toggle="tab">Overview</a></li>
						<li><a href="#tab2" data-toggle="tab">Steps</a></li>
						<li><a href="#tab3" data-toggle="tab">Neighbor Tips</a></li>
						<li><a href="#tab4" data-toggle="tab">Supplies</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab1">							
							<div class="guide-tab-inner">
<?php the_content();?>
<?php 
$img_feature_src = wp_get_attachment_image_src(get_post_thumbnail_id($nhow_postID), 'full');
$thumbnail_id = get_post_thumbnail_id($nhow_postID);
$img_feature_caption = get_post($thumbnail_id)->post_title;
?>
								<div class="single-guide-img overview">
									<div class="carousel-inner"><img src="<?php bloginfo('stylesheet_directory');?>/lib/timthumb.php?src=<?php echo $img_feature_src[0];?>&h=300&q=100&zc=1" alt="Photo of <?php echo $step['title'];?>" />
										<div class="carousel-caption single-caption">
											<h4><?php echo $img_feature_caption;?></h4>
										</div>
									</div>
								</div>							
							</div><!-- /guide-tab-inner-->
						</div><!-- /tab pane-->

						<div class="tab-pane tab-steps" id="tab2">
							<div class="guide-tab-inner">	
<?php
$steps = get_post_meta($nhow_postID,'guides-steps',true);
$count = 0;
if (!empty($steps)) {
echo '<ul class="guide-steps">';
foreach ($steps as $step) {
	$count++;		
	echo '<li class="guide-step">';
	echo '<p>';
	echo '<div class="guide-step-number">'.$count.'</div>';
	echo '<div class="guide-step-title">'.$step['title'].'</div>';
//	echo '</p>';
?>		
<?php		
	$step_description = preg_replace('#\R+#', '</p><p>', $step['description']);
	echo '<p>'.$step_description.'</p>';		
	//step images
	$step_photo_ID = $step['media'];
	$step_photo_url = wp_get_attachment_url($step_photo_ID);
	//caption is the img or file title when uploaded
	$step_photo_caption = get_post($step_photo_ID)->post_title;		
	//step files
	$step_file_ID = $step['file'];		
	$step_file_url = wp_get_attachment_url($step_file_ID);		
	$step_file_caption = get_post($step_file_ID)->post_title;		
	if (!empty($step_photo_ID)) { 
?>
<div class="single-guide-img">
<div class="carousel-inner"><img src="<?php bloginfo('stylesheet_directory');?>/lib/timthumb.php?src=<?php echo $step_photo_url;?>&h=400&q=100&zc=1" alt="Photo of <?php echo $step['title'];?>" />
	<div class="carousel-caption single-caption">
		<h4><?php echo $step_photo_caption;?></h4>
	</div>
</div>
</div>
<?php
	}		
	if (!empty($step_file_ID)) {
?>			
<div class="single-guide-file">
<div class="carousel-inner"><a class="guide-step-image" target="_blank" href="<?php echo $step_file_url;?>"><?php echo wp_get_attachment_image($step_file_ID,'full',true);?>
	<div class="carousel-caption single-caption file"><h4><?php echo $step_file_caption;?></h4>
	</div>
</div></a>
</div>
<?php			
	}
	else { /*nothing here*/ }
	echo '</li>';
}
echo '</ul>';
}
else {
echo '<p>Sorry, there are no steps yet for this Neighborhow Guide.</p>';
}
?>								</div><!--/ guide-tab-inner-->
						</div><!-- /tab pane-->
						
						<div class="tab-pane" id="tab3">
<?php 
get_currentuserinfo();
$nhow_viewer_id = $current_user->ID;
$nhow_viewer_name = $current_user->display_name;
?>
							<div class="guide-tab-inner ">
								<div class="gray-box">
<a class="btn btn-success fancybox fancybox.iframe" rel="gallery" href="http://neighborhow/modal-forms/?form=nizlpn" title="Add a Tip!">Add a Tip</a>
<div style="margin-top:-12px" class="guide-step-title">Help make this Neighborhow Guide even better</div>

<?php
if (!is_user_logged_in()) {
echo '<p class="login-now">Contribute your Neighborhow! <a class="noline" href="">When you sign in</a>, you&#39;ll be able to add contacts, links, and suggestions to this Guide and help other people who want to do it. If you don&#39;t have a Neighborhow account, <a class="noline" href="">create one now</a>.</p>';
}
else {
?>
<p>Your neighbors have shared these Tips about this Guide. Add your own to make it even better.</p>
<?php
}
?>
								</div><!--/gray-box-->
<?php 
echo '<ul class="guide-tips">';
echo do_shortcode("[display-frm-data id=2 filter=1]"); 
echo '</ul>';
?>
							</div><!-- /guide-tab-inner-->
						</div><!-- /tab pane-->

						<div class="tab-pane" id="tab4">
							<div class="guide-tab-inner">
								<div class="gray-box">
<a class="btn btn-success fancybox fancybox.iframe" rel="gallery" href="http://neighborhow/modal-forms/?form=mk4oa6" title="Add a Resource!">Add a Resource</a>

<div style="margin-top:-12px" class="guide-step-title">Supplies and Resources for this Neighborhow Guide</div>
<p>Your neighbors have shared these Tips about this Guide. Add your own to make it even better.</p>
<?php
if (!is_user_logged_in()) {
echo '<p class="login-now">Contribute your Neighborhow! <a class="noline" href="">When you sign in</a>, you&#39;ll be able to add recommendations for resources or supplies related to this Guide. If you don&#39;t have a Neighborhow account, <a class="noline" href="">create one now</a>.</p>';
}
else { //no else remove later
?>
else here
<?php
}
?>
<?php
$supplies = get_post_meta($nhow_postID,'guides-resources',true);
//if (!empty($supplies)) {
//	echo '<p>There are no resources or supplies recommended for this Guide yet. Recommend some!</p>';
//}
?>
								</div><!--end gray-box-->
<?php 
echo '<ul class="guide-resources">';
echo do_shortcode("[display-frm-data id=3 filter=1]"); 
echo '</ul>';
?>								
							</div><!-- /guide-tab-inner-->
						</div><!-- /tab pane-->
					</div><!--/tab-content-->
				</div><!--/tabbable-->
				<div class="nhow-comments">
<?php comments_template( '', true ); ?>
<?php
} //end pwd protection
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
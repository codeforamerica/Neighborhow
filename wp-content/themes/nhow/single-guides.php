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
$are_there_steps = get_post_meta($post->ID,'step-title-01',true);
?>				
<div class="tabbable">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">Summary</a></li>
<?php if ($are_there_steps) { ?>		
		<li><a href="#tab2" data-toggle="tab">Step-by-Step</a></li>
<?php } ?>
		<!--li><a href="#tab3" data-toggle="tab">Supplies + Resources</a></li-->
	</ul>

	<div class="tab-content">
		<div class="tab-pane tab-pane-guide active" id="tab1">	
<?php 
if ( have_posts() ) :
while ( have_posts() ) : the_post(); 
$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nhow_post_id = $post->ID;
?>			

			<div class-"guide-overview"><p>
<?php 
$tmpcontent = get_the_content();
$guide_summary = preg_replace('#\R+#', '</p><p>', $tmpcontent);
echo make_clickable($guide_summary);
?>	
			</p></div>
<?php
$img_feature_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
?>
			<div class="single-guide-img overview">
				<div class="carousel-inner"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $img_feature_src[0];?>&w=400&q=95&zc=2&a=t" alt="Photo of <?php the_title();?>" />
					<!--div class="carousel-caption single-caption">
					<h4><?php //echo $img_feature_caption;?></h4>
					</div-->
				</div>
			</div>
		</div><!--/ tab 1-->

		<div class="tab-pane tab-pane-guide" id="tab2">
			<ul class="guide-steps">
<?php
// steps limited to 15 for now
$step_total = '15'; 
// display step number counter
$j = 1; 
for ($i=1;$i <= $step_total;$i++) {
	// Align w the padded number from db
	$i = str_pad($i, 2, "0", STR_PAD_LEFT);
	// Titles
	$step_t = 'step-title-'.$i;
	$step_title = get_post_meta($post->ID,$step_t,true);
	// Descriptions
	$step_d = 'step-description-'.$i;
	$step_description = get_post_meta($post->ID,$step_d,true);
	//Images
	$step_m = 'step-media-'.$i;
	$step_media_id = get_post_meta($post->ID,$step_m,true);	
		$step_media_url = wp_get_attachment_url($step_media_id);	
	$step_media_src = wp_get_attachment_image_src($step_media_id);
	
	if (!empty($step_title)) {
		echo '<li class="guide-step">';		
		echo '<p class="guide-step-number">'.$j.'</p>';	
		echo '<div class="guide-step-title"><h4>'.$step_title.'</h4>';	

		if (!empty($step_description)) {
			$step_description = preg_replace('#\R+#', '</p><p>',$step_description);
			echo '<p>'.make_clickable($step_description).'</p></div>'; 
		}
		if (!empty($step_media_id)) {
// Do captions + files later		
?>
		<div class="single-guide-img">
			<div class="carousel-inner"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $step_media_url;?>&zc=2&w=400&h=280&q=95&a=t" alt="Photo of <?php echo $step_title;?>" />
			</div>
		</div>
		</li>
<?php
		}
//		else {
//			echo '<p>Sorry, there are no steps yet for this Neighborhow Guide.</p>';			
//		}
		$j++;
	}
}	
?>
			</ul>
		</div><!--/ tab 2-->
		
		<div class="tab-pane tab-pane-guide" id="tab3">
			<p class="add-supply"><a class="nh-btn-blue" href="">Add Another Supply</a></p>

<?php
$supplies = get_post_meta($post->ID,'gde-supplies');
$count = 0;
if (!empty($supplies)) {
	echo '<ul class="supply-steps">';
	foreach ($supplies as $supply) {
		$count++;		
		echo '<li class="supply-step">';
		echo '<div class="supply-title"><h4>'.$supply['guide-supply-title'].'</h4>';
		echo '</div>';		
	}
}
?>
		</div><!-- / tab 3-->
		
	</div><!-- / tab content-->
</div><!-- / tabbable-->

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
	get_sidebar('guide-single');	
}
?>			
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer(); ?>
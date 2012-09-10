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
<?php 
if ( have_posts() ) :
while ( have_posts() ) : the_post(); 
$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nhow_post_id = $post->ID;
?>			
<h3 class="page-title"><?php the_title();?></h3>
				
<div id="post-<?php echo $TmpID;?>">
<?php echo 'Summary: '.get_the_content().'<br/>';?>	

<?php
$thumbnail_id = get_post_thumbnail_id($post->ID);

$img_feature_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

//$img_feature_caption = get_post($thumbnail_id)->post_title;

echo '<br/>Summary image: <img src="'. $style_url.'/lib/timthumb.php?src='. $img_feature_src[0].'&h=300&q=100&zc=1" alt="Photo of '.$img_feature_caption.'">';

echo '<br/>'.$img_feature_caption.'<br/>';

$guide_city = get_post_meta($post->ID,'gde-user-city',true);
echo 'Guide city: '.$guide_city.'<br/><br/>Guide Steps<br/><br/>';

$step_total = '15';

//$i = 1;
//$i = str_pad($i, 2, "0", STR_PAD_LEFT);
//echo $i;

for ($i=1;$i <= $step_total;$i++) {
//while ($i < $step_total) {	
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

	if (!empty($step_title)) {
		echo '<b>Step '.$i.'</b><br/>';
		echo 'title: '.$step_title.'<br/>'; 
	}
	if (!empty($step_description)) {
		echo 'description: '.$step_description.'<br/>'; 
	}
	if (!empty($step_media_id)) {
		echo 'image id: '.$step_media_id.'<br/>'; 
		$img_src = wp_get_attachment_image_src($step_media_id);
		echo '<img src="'.$style_url.'/lib/timthumb.php?src='.$img_src[0].'&w=300&h=100&zc=1&at=t" alt="Photo from '.$step_title.'" />';
		echo '<br/>';
	}
	else {}
	
	
}

$step_total = get_post_meta($post->ID,'gde-steps');
$test1 = count($step_total).' ttotal<br/>';
echo $test1;

$guide_steps = get_post_meta($post->ID,'gde-steps',true);


$guide_step_images = get_post_meta();

$guide_steps = unserialize($guide_steps);

$i = 0;
$j = 1;
foreach ($guide_steps as $key => $value) {
	
		echo 'Step '.$j;
		$step_title = $value[0];
		$step_description = $value[1];	
		$step_image = $value[2];
		
		echo '<p>title: '.$step_title.'</p>';
		echo '<p>description: '.$step_description.'</p>';	
		echo '<p>image: '.$step_image.'</p>';	
	
		
		$test2 = get_post_meta($post->ID,'step-image-0',true);
		echo 'image id: '.$test2;
		
		$attachments = get_posts( array(
					'post_type' => 'attachment',
					'posts_per_page' => -1,
					'post_parent' => $post->ID
				) );
			echo '<pre>';
			var_dump($attachments);
			echo '</pre>';
		
		
				echo '<hr>';
$i++;
$j++;	
	
	
}
?>

	<div class="nhow-comments">
<?php comments_template( '', true ); ?>
<?php
endwhile;
endif;
?>
	</div>
</div>				
			
			</div><!--/ content-->
<?php get_sidebar('guides');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
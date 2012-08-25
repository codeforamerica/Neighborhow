<?php
/*
Template Name: page-login
*/
?>
	
<?php get_header();?>
<?php
if (is_user_logged_in()) :
get_currentuserinfo();
$nh_profile_ID = $current_user->ID;
$nh_profile_name = $current_user->display_name;
$nh_profile_avatar_alt = 'Photo of '.$nh_profile_name;
$nh_profile_avatar = get_avatar($nh_profile_ID, '96','',$nh_profile_avatar_alt);
?>
<div class="row-fluid row-content">	
	<div id="main">
		<div id="content">
			<div class="profile-div" style="border:1px solid #ddd;">
				<div class="profile-welcome">
					<h3 class="page-title"><?php echo $nh_profile_avatar;?>&nbsp;&nbsp;Hi, <?php echo $nh_profile_name;?></h3>

<?php
$args = array(
'author'  => $nh_profile_ID,	
'posts_per_page' => -1,
'post_status' => 'publish',
'orderby' => 'date',
'order' => 'DESC'
);
$query1 = new WP_Query($args);
$post_count = $query1->found_posts;
if ($post_count > 0) :
?>
					<p>View or edit your Neighborhow content. You can also edit your Settings using the links on the right.</p></div>
<?php
while ($query1->have_posts()) : $query1->the_post();
$feat_id = $post->ID;
$featImgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($feat_id), 'full');
?>

<div class="feat-container">sdfkjsdlkj	
	</div>					
	<div class="feat-details">

	</div><!--/ feat-details-->
</div><!--feat-container-->

<?php 
endwhile;
else :
?>
					<p>Get started! Explore or create a guide!</p></div>
<?php 
endif; // end if post count > 0
endif; // end if logged in
?>			
			</div><!--/ profile-div-->
	
<?php while ( have_posts() ) : the_post(); ?>
<?php the_content(); ?>
<?php endwhile;?>

		</div><!--/ content-->
<?php get_sidebar('login-profile'); ?>		
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

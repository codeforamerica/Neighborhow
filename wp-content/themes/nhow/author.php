<?php get_header(); ?>
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
// Get viewer
global $current_user;
$nh_viewer_id = $current_user->ID;

// author
// Set up author
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;
?>

<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>

<?php // if viewer = author
if (is_user_logged_in() AND $nh_viewer_id === $nh_author_id) {
	$welcometxt = 'Hi, '.$nh_author_name;
	$descriptiontxt = $nh_author->description;
}
else {
	$welcometxt = $nh_author_name;
	$descriptiontxt = $nh_author->description;
}
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">		
			<div id="content">
<div class="author-welcome" style="border:1px solid #ddd;padding:1em;">
	<p style="float:left;margin-right:1em;">
<?php
$nh_avatar_alt = 'Photo of '.$nh_author_name;
$nh_avatar = get_avatar($nh_author_id, '96','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);

if ($nh_user_photo_url) {
echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=96&h=96&q=100&zc=1"><br/>';
echo userphoto($posts[0]->post_author);
}
else {
echo $nh_avatar.'<br/>';
}
?>				
	</p>
	<div class="author-elements">
		<h3 class="page-title" style=""><?php echo $welcometxt;?></h3>
		<p><?php echo $descriptiontxt;?></p>
	</div>
</div><!--/ author-welcome-->

<div class="author-posts" style="border:1px solid #ddd;">
	<div class="feat-container">

<?php  
$guide_cat = get_category_id('guides');
$stories_cat = get_category_id('stories');
$resources_cat = get_category_id('resources');
$blog_cat = get_category_id('blog');

// VIEWER IS AUTHOR    
if ($curauth->ID == $current_user->ID) {
	$count = custom_get_user_posts_count($curauth->ID,array(
		'post_type' =>'post',
		'post_status'=> array('draft','pending','publish')
		));

// Viewer author has posts		
	if ($count > 0) {
		// Guides
		$guideargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $guide_cat
			);
		$guidequery = new WP_Query($guideargs);
		if ($guidequery->have_posts()) {
			echo '<h5>Neighborhow Guides</h5>';
			echo '<ul>';	
			while ($guidequery->have_posts()) {
				$guidequery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo $app_url;?>/edit-guide?entry=<?php echo $post_key;?>&action=edit" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		// Resources
		$resourcesargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $resources_cat
			);
		$resourcesquery = new WP_Query($resourcesargs);
		if ($resourcesquery->have_posts()) {
			echo '<h5>Neighborhow Resources</h5>';
			echo '<ul>';	
			while ($resourcesquery->have_posts()) {
				$resourcesquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		// Blog posts
		$blogargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $blog_cat
			);
		$blogquery = new WP_Query($blogargs);
		if ($blogquery->have_posts()) {
			echo '<h5>Blog Posts</h5>';
			echo '<ul>';	
			while ($blogquery->have_posts()) {
				$blogquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();			
	} 

// Viewer author doesnt have posts	
	else {
		echo '<h5>You don&#39;s have any posts yet!</h5>';
		echo '<p>Start by creating a <a href="'.$app_url.'/create-guide" title="Create a Neighborhow Guide">Neighborhow Guide</a>, or <a href="'.$app_url.'/guides" title="Explore Neighborhow Guides">explore other Guides</a> for inspiration.';
	}
// VIEWER IS NOT AUTHOR
} 
elseif ($curauth->ID != $current_user->ID) {
	$count = custom_get_user_posts_count($curauth->ID,array(
		'post_type' =>'post',
		'post_status'=> 'publish'
		));	

	// Author has posts		
	if ($count > 0) {
		// Guides
		$guideargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $guide_cat
			);
		$guidequery = new WP_Query($guideargs);
		if ($guidequery->have_posts()) {
			echo '<h5>Neighborhow Guides</h5>';
			echo '<ul>';	
			while ($guidequery->have_posts()) {
				$guidequery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		$resourcesargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $resources_cat
			);
		$resourcesquery = new WP_Query($resourcesargs);
		if ($resourcesquery->have_posts()) {
			echo '<h5>Neighborhow Resources</h5>';
			echo '<ul>';	
			while ($resourcesquery->have_posts()) {
				$resourcesquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		$blogargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $blog_cat
			);
		$blogquery = new WP_Query($blogargs);
		if ($blogquery->have_posts()) {
			echo '<h5>Blog Posts</h5>';
			echo '<ul>';	
			while ($blogquery->have_posts()) {
				$blogquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a> (<?php the_time('j M Y');?>)</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();		
	}

	// Author doesnt have posts	
	else {
		echo '<h5>This author doesn&#39;t have any posts yet!</h5>';
	}			
}
?>

		</div>
	</div><!--/ feat-container-->	
</div><!--/ profile-posts-->

			</div><!--/ content-->
<?php get_sidebar(''); ?>
		</div><!--/ main-->
	</div><!--/ wrapper-->		
</div><!--/ row-content-->
<?php get_footer(); ?>
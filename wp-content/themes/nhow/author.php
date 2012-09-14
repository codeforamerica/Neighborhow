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
if (is_user_logged_in() AND $nh_viewer_id == $nh_author_id) {
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
			<div id="content-full">
<div class="author-welcome">
	<p class="author-img">
<?php
$nh_avatar_alt = 'Photo of '.$nh_author_name;
$nh_avatar = get_avatar($nh_author_id, '96','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);

if ($nh_user_photo_url) {
echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=96&h=96&q=95&zc=1">';
}
else {
echo $nh_avatar;
}
?>				
	</p>
	<h3 class="page-title"><?php echo $welcometxt;?></h3>
	<div class="author-elements">
		<?php if ($descriptiontxt) { ?><p><span class="byline">bio:</span> <?php echo $descriptiontxt;?></p><?php } ?>
		<p><span class="byline">city:</span> <?php echo $nh_author->user_city;?></p>
		<?php if ($nh_author->user_org) { ?><p><span class="byline">organization:</span> <?php echo $nh_author->user_org;?></p><?php } ?>
<?php 
if ($nh_author->user_url) {
	echo '<p><span class="byline">website:</span>';
	echo '<a href="'.$nh_author->user_url.'" title="Go to '.$nh_author->user_url.'" target="_blank">';
	echo $nh_author->user_url;
	echo '</a></p>';
}
?>		
	</div><!--/ author-elements-->
</div><!--/ author-welcome-->

<div class="author-content">
	<div class="tabbable">
		<ul class="nav nav-tabs nav-tabs-author">
			<li class="active"><a href="#tab1" data-toggle="tab"><?php if (is_user_logged_in() AND $nh_viewer_id == $nh_author_id) {echo 'Your ';}?>Content</a></li>
			<li><a href="#tab2" data-toggle="tab"><?php if (is_user_logged_in() AND $nh_viewer_id == $nh_author_id) {echo 'Your ';}?>Comments</a></li>
			<li><a href="#tab3" data-toggle="tab"><?php if (is_user_logged_in() AND $nh_viewer_id == $nh_author_id) {echo 'Your ';}?>Likes</a></li>
			
			<?php if (is_user_logged_in() AND $nh_viewer_id == $nh_author_id) {?><li class="tab-author tab-settings"><a title="Edit Settings" href="<?php echo $app_url;?>/settings"><img src="<?php echo $style_url;?>/images/icons/settings_white.png" alt="Settings image" /> Edit Settings</a></li><?php }?>
		</ul>

		<div class="tab-content">
			<div class="tab-pane tab-pane-author-content active" id="tab1">
				<div class="author-posts">
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
			echo '<ul class="author-links">';	
			while ($guidequery->have_posts()) {
				$guidequery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo $app_url;?>/edit-guide?entry=<?php echo $post_key;?>&action=edit" title="View <?php the_title();?>"><?php the_title(); ?></a><span class="meta">
<?php
$pub_date = get_the_modified_date('j M Y');
$status = get_post_status();
if ($status == 'publish') {
	$newstatus = 'Published';
	echo '&nbsp;&nbsp;(<span class="byline">Status: </span>'.$newstatus.'<!--, <span class="byline">Last saved: </span>'.$pub_date.'-->)';
}
if ($status == 'draft') {
	$newstatus = 'Draft';
	echo '&nbsp;&nbsp;(<span class="byline">Status: </span>'.$newstatus.'<!--, <span class="byline">Last saved: </span>'.$pub_date.'-->)';
}
if ($status == 'pending') {
	$newstatus = 'Pending Review';
	echo '&nbsp;&nbsp;(<span class="pending">Pending review. <a href="'.$app_url.'/?post_type=post&p='.$post->ID.'&preview=true" title="See what it will look like" target="_blank">Preview</a> it here.</span>)';
}
?>			
					</span>
				</li>
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
			echo '<ul class="author-links">';	
			while ($resourcesquery->have_posts()) {
				$resourcesquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
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
			echo '<ul class="author-links">';	
			while ($blogquery->have_posts()) {
				$blogquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();			
	} 

// Viewer author doesnt have posts	
	else {
		echo '<h5>You haven&#39;t created any Neighborhow content yet!</h5>';
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
			echo '<ul class="author-links">';	
			while ($guidequery->have_posts()) {
				$guidequery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
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
			echo '<ul class="author-links">';	
			while ($resourcesquery->have_posts()) {
				$resourcesquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
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
			echo '<ul class="author-links">';	
			while ($blogquery->have_posts()) {
				$blogquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();		
	}

	// Author doesnt have posts	
	else {
		echo '<h5>This author hasn&#39;t created any public Neighborhow content yet. Stay tuned!</h5>';
	}			
}
?>				
					</div><!--/ author-posts-->
				</div><!--/ tab1-->
				
				<div class="tab-pane tab-pane-author-comments" id="tab2">
					<div class="author-posts">
<?php
$args = array(
	'user_id' => $current_user->ID
);
$comments = get_comments($args);
foreach ($comments as $comment) {
	$tmp_content = $comment->comment_content;
	$comment_content = substr($tmp_content,0,160).' . . .';
	$comment_date = get_the_date($comment->comment_date);
	$comment_date = mysql2date('j M Y', $comment_date);
	$comment_post_url = get_permalink($comment->comment_post_ID);
	$comment_post_title = get_the_title($comment->comment_post_ID);
	echo '<p class="author-list">';
	echo '<span class="byline" style="font-size:1em;">&#8220;</span>'.$comment_content.'<span class="byline" style="font-size:1em;">&#8221;</span>';
	echo '<br/><span class="byline">about</span> <a href="'.$comment_post_url.'" title="View '.$comment_post_title.'">'.$comment_post_title.'</a>&nbsp;&nbsp;&nbsp<span class="meta"><span class="byline">on </span>'.$comment_date;
	echo '</span></p>';
}
?>
					</div>
				</div><!--/ tab2-->
				
				<div class="tab-pane tab-pane-author" id="tab3">
					<div class="author-posts">
<?php
$likes = get_user_meta($current_user->ID,'nh_li_user_loves');

foreach ($likes as $like) {
	$tmp = count($like);
	for ($i=0;$i<$tmp;$i++) {
		$nh_post_id = $like[$i];
		$nh_post_status = get_post_status($like[$i]);
		if ($current_user->ID != $curauth->ID) {
			if (get_post_status($like[$i]) == "publish")  {
				$post_title = get_the_title($like[$i]);
				$post_url = get_permalink($like[$i]);
				$post_like_count = lip_get_love_count($like[$i]);
				echo '<p class="author-list list-noborder"><a href="'.$post_url.'" title="View '.$post_title.'">';
				echo $post_title.'</a>';
				echo '&nbsp;&nbsp;<span class="meta">('.$post_like_count.' <span class="byline">people liked this</span>';
				echo ')</span></p>';
			}
		}
		elseif ($current_user->ID == $curauth->ID) {
			if (get_post_status($like[$i]) != "trash")  {
				$post_title = get_the_title($like[$i]);
				$post_url = get_permalink($like[$i]);
				$post_like_count = lip_get_love_count($like[$i]);
				echo '<p class="author-list list-noborder"><a href="'.$post_url.'" title="View '.$post_title.'">';
				echo $post_title.'</a>';
				echo '&nbsp;&nbsp;<span class="meta">('.$post_like_count.' <span class="byline">people liked this</span>';
				echo ')</span></p>';
			}
		}
	}	
}
?>								
					</div>
				</div><!--/ tab3-->	
				
		</div><!--/ tab content-->
	</div><!--/ tabbable-->	
</div><!--/ author-content-->

			</div><!--/ content-->
<?php // NO SIDEBAR ?>
		</div><!--/ main-->
	</div><!--/ wrapper-->		
</div><!--/ row-content-->
<?php get_footer(); ?>
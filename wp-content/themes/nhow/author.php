<?php get_header(); ?>
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
// Get viewer
global $current_user;
global $post;
get_currentuserinfo();
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
echo $nh_avatar;
?>				
	</p>
	<h3 class="page-title"><?php echo $welcometxt;?></h3>
	<div class="author-elements">
		<?php if ($descriptiontxt) { ?><p><span class="byline">bio:</span> <?php echo $descriptiontxt;?></p><?php } ?>
		<p><span class="byline">city:</span> 
<?php 
$nh_cities = get_terms('nh_cities');
$user_city = $nh_author->user_city;
 
$term = term_exists($user_city, 'nh_cities');
// If user city is an official city
if ($term !== 0 && $term !== null) {
	$term_id = $term['term_id'];
	$term_data = get_term_by('id',$term_id,'nh_cities');
	echo '<a href="'.$app_url.'/cities/'.$term_data->slug.'" title="View '.$user_city.'">'.$user_city.'</a>';
}
elseif ($term == 0 && $term == null) {
	echo $user_city;
}
?></p>
		<?php if ($nh_author->user_org) { ?><p><span class="byline">organization:</span> <?php echo $nh_author->user_org;?></p><?php } ?>
<?php 
if ($nh_author->user_url) {
	echo '<p><span class="byline">website:</span> ';
	echo '<a class="nhline" href="'.$nh_author->user_url.'" title="Go to '.$nh_author->user_url.'" target="_blank">';
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
			<li><a href="#tab4" data-toggle="tab"><?php if (is_user_logged_in() AND $nh_viewer_id == $nh_author_id) {echo 'Your ';}?>Votes</a></li>
			
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
$ideas_cat = get_category_id('ideas');

// VIEWER IS AUTHOR    
if ($curauth->ID == $current_user->ID) {
	$count = nh_get_user_posts_count($curauth->ID,array(
		'post_type' =>'post',
		'post_status'=> array('draft','pending','publish'),
		'posts_per_page' => -1
		));

// Viewer author has posts		
	if ($count > 0) {
		// Guides
		$guideargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $guide_cat,
			'posts_per_page' => -1
			);
		$guidequery = new WP_Query($guideargs);
		if ($guidequery->have_posts()) {
			echo '<h5>Neighborhow Guides</h5>';
			echo '<ul class="author-links">';	
			while ($guidequery->have_posts()) {
				$guidequery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); 
				echo $post_key;
				?>		
				<li><a class="nhline" href="<?php echo $app_url;?>/edit-guide?entry=<?php echo $post_key;?>&action=edit" title="View <?php the_title();?>"><?php the_title(); ?></a><span class="meta">
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
	echo '&nbsp;&nbsp;(<span class="pending">Pending review. <a class="nhline" href="'.$app_url.'/?post_type=post&p='.$post->ID.'&preview=true" title="See what it will look like" target="_blank">Preview</a> it here.</span>)';
}
?>			
					</span>
				</li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		
		// Ideas 
		$ideasargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $ideas_cat,
			'posts_per_page' => -1			
			);
		$ideasquery = new WP_Query($ideasargs);
		if ($ideasquery->have_posts()) {
			echo '<h5>Ideas</h5>';
			echo '<ul class="author-links">';	
			while ($ideasquery->have_posts()) {
				$ideasquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
	<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		
		// Blog posts
		$blogargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $blog_cat,
			'posts_per_page' => -1
			);
		$blogquery = new WP_Query($blogargs);
		if ($blogquery->have_posts()) {
			echo '<h5>Blog Posts</h5>';
			echo '<ul class="author-links">';	
			while ($blogquery->have_posts()) {
				$blogquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		
		// Resources
		$resourcesargs = array(
			'author' => $curauth->ID,
			'post_status' => array('pending','publish','draft'),
			'cat' => $resources_cat,
			'posts_per_page' => -1
			);
		$resourcesquery = new WP_Query($resourcesargs);
		if ($resourcesquery->have_posts()) {
			echo '<h5>Neighborhow Resources</h5>';
			echo '<ul class="author-links">';	
			while ($resourcesquery->have_posts()) {
				$resourcesquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
	} 
	
// Viewer author doesnt have posts	
	else {
		echo '<h5>You haven&#39;t created any Neighborhow content yet!</h5>';
		echo '<p>Start by creating a <a class="nhline" href="'.$app_url.'/create-guide" title="Create a Neighborhow Guide">Neighborhow Guide</a>, or <a class="nhline" href="'.$app_url.'/guides" title="Explore Neighborhow Guides">explore other Guides</a> for inspiration.';
	}
// VIEWER IS NOT AUTHOR
} 

elseif ($curauth->ID != $current_user->ID) {
	$count = nh_get_user_posts_count($curauth->ID,array(
		'post_type' =>'post',
		'post_status'=> 'publish'
		));	

	// Author has posts		
	if ($count > 0) {
		// Guides
		$guideargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $guide_cat,
			'posts_per_page' => -1
			);
		$guidequery = new WP_Query($guideargs);
		if ($guidequery->have_posts()) {
			echo '<h5>Neighborhow Guides</h5>';
			echo '<ul class="author-links">';	
			while ($guidequery->have_posts()) {
				$guidequery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		
		// Ideas 
		$ideasargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $ideas_cat,
			'posts_per_page' => -1
			);
		$ideasquery = new WP_Query($ideasargs);
		if ($ideasquery->have_posts()) {
			echo '<h5>Ideas</h5>';
			echo '<ul class="author-links">';	
			while ($ideasquery->have_posts()) {
				$ideasquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php the_permalink(); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
	<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();		

		// Blog posts		
		$blogargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $blog_cat,
			'posts_per_page' => -1
			);
		$blogquery = new WP_Query($blogargs);
		if ($blogquery->have_posts()) {
			echo '<h5>Blog Posts</h5>';
			echo '<ul class="author-links">';	
			while ($blogquery->have_posts()) {
				$blogquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
<?php
			}
			echo '</ul>';
		}
		wp_reset_postdata();
		
		// Resources		
		$resourcesargs = array(
			'author' => $curauth->ID,
			'post_status' => 'publish',
			'cat' => $resources_cat,
			'posts_per_page' => -1
			);
		$resourcesquery = new WP_Query($resourcesargs);
		if ($resourcesquery->have_posts()) {
			echo '<h5>Neighborhow Resources</h5>';
			echo '<ul class="author-links">';	
			while ($resourcesquery->have_posts()) {
				$resourcesquery->the_post();
				$post_key = nh_get_frm_entry_key($post->ID); ?>		
				<li><a class="nhline" href="<?php echo get_permalink($post->ID); ?>" title="View <?php the_title();?>"><?php the_title(); ?></a>&nbsp;&nbsp;<span class="meta">(<span class="byline">Published: </span><?php the_time('j M Y');?>)</span></li>
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
	echo '<p class="author-list list-noborder" style="padding-top:.25em;border-top:1px solid #ddd;"><span class="byline">* Content may not appear if the author has removed the content or is currently editing it.</span></p>';		
}
?>				
					</div><!--/ author-posts-->
				</div><!--/ tab1-->
				
				<div class="tab-pane tab-pane-author-comments" id="tab2">
					<div class="author-posts">
<?php
$args = array(
	'user_id' => $curauth->ID
);   
$comments = get_comments($args);
foreach ($comments as $comment) {
	$tmp = count($comment);
	for ($i=0;$i<$tmp;$i++) {
		$nh_post_status = get_post_status($comment->comment_post_ID);
		if ($nh_post_status == "publish")  {
			$tmp_content = $comment->comment_content;
			$comment_content = substr($tmp_content,0,160).' . . .';
			$comment_date = get_the_date($comment->comment_date);
			$comment_date = mysql2date('j M Y', $comment_date);
			$comment_post_url = get_permalink($comment->comment_post_ID);
			$comment_post_title = get_the_title($comment->comment_post_ID);
			echo '<p class="author-list">';
			echo '<span class="byline" style="font-size:1em;">&#8220;</span>'.$comment_content.'<span class="byline" style="font-size:1em;">&#8221;</span>';
			echo '<br/><span class="meta"><span class="byline">about</span> <a class="nhline" href="'.$comment_post_url.'" title="View '.$comment_post_title.'">';
			echo $comment_post_title.'</a></span>';
			echo ' <span class="byline">on</span> '.$comment_date;
			echo '</span></p>';				
		}
	}		
}
if (!$comments AND $current_user->ID == $curauth->ID) {
	echo '<h5>You haven&#39;t commented on anything yet!</h5>';
	echo '<p class="author-list style="margin-top:.25em;font-size:.9em">Join the Neighborhow conversation by exploring some <a class="nhline" href="'.$app_url.'/guides" title="Explore Neighborhow Guides">Neighborhow Guides</a>.</p>';
}
if (!$comments AND $current_user->ID != $curauth->ID) {
	echo '<h5>This author hasn&#39;t commented on anything yet. Stay tuned!</h5><p class="author-list"></p>';
}
echo '<p class="author-list list-noborder"><span class="byline">* Comments may not appear if an author has removed the content or is currently editing it.</span></p>';
?>
					</div>
				</div><!--/ tab2-->
				
				<div class="tab-pane tab-pane-author" id="tab3">
					<div class="author-posts">
<?php
$likes = get_user_meta($curauth->ID,'nh_li_user_loves');
foreach ($likes as $like) {
	$tmp = count($like);
	for ($i=0;$i<$tmp;$i++) {
		$nh_post_id = $like[$i];
		$nh_post_status = get_post_status($nh_post_id);
		if ($nh_post_status == "publish")  {
			$post_title = get_the_title($nh_post_id);
			$post_url = get_permalink($nh_post_id);
			$post_like_count = lip_get_love_count($nh_post_id);
			echo '<p class="author-list"><a class="nhline" href="'.$post_url.'" title="View '.$post_title.'">';
			echo $post_title.'</a>';
			echo '&nbsp;&nbsp;<span class="meta">('.$post_like_count.' <span class="byline">';
			if ($post_like_count == '1') {
				echo 'person ';
			}
			else {
				echo 'people ';
			}
			echo 'like this</span>';
			echo ')</span></p>';				
		}
	}	
}
if (!$likes AND $current_user->ID == $curauth->ID) {
	echo '<h5>You haven&#39;t liked anything yet!</h5>';
	echo '<p class="author-list" style="margin-top:.25em;font-size:.9em">Start exploring some <a class="nhline" href="'.$app_url.'/guides" title="Explore Neighborhow Guides">Neighborhow Guides</a>.</p>';
}
elseif (!$likes AND $current_user->ID != $curauth->ID) {
	echo '<h5>This author hasn&#39;t liked anything yet. Stay tuned!</h5><p class="author-list"></p>';
}
echo '<p class="author-list list-noborder"><span class="byline">* Likes may not appear if an author has removed the content or is currently editing it.</span></p>';
?>								
					</div>
				</div><!--/ tab3-->	
				
				<div class="tab-pane tab-pane-author" id="tab4">
					<div class="author-posts">
<?php
$votes = get_user_meta($curauth->ID,'nh_user_votes');
foreach ($votes as $vote) {
	$tmp = count($vote);
	for ($i=0;$i<$tmp;$i++) {
		$nh_post_id = $vote[$i];
		$nh_post_status = get_post_status($nh_post_id);
		if ($nh_post_status == "publish")  {
			$post_title = get_the_title($nh_post_id);
			$post_url = get_permalink($nh_post_id);
			$post_vote_count = get_post_meta($nh_post_id,'_nh_vote_count',true);
			echo '<p class="author-list">';
			echo '<span class="byline">for</span> <a class="nhline" href="'.$post_url.'" title="View '.$post_title.'">';
			echo $post_title.'</a>';
			echo '&nbsp;&nbsp;<span class="meta">('.$post_vote_count.' <span class="byline">';
			if ($post_vote_count == '1') {
				echo 'person ';
			}
			else {
				echo 'people ';
			}
			echo 'voted for this</span>';	
			echo ')</span></p>';				
		}
	}	
}
if (!$votes AND $current_user->ID == $curauth->ID) {
	echo '<h5>You haven&#39;t voted on anything yet!</h5>';
	echo '<p class="author-list" style="margin-top:.25em;font-size:.9em">Explore <a class="nhline" href="'.$app_url.'/ideas" title="View Neighborhow ideas">some of the ideas</a> from other people.</p>';
}
elseif (!$votes AND $current_user->ID != $curauth->ID) {
	echo '<h5>This author hasn&#39;t voted on anything yet. Stay tuned!</h5><p class="author-list"></p>';
}
echo '<p class="author-list list-noborder"><span class="byline">* Votes may not appear if an author has removed the content or is currently editing it.</span></p>';
?>								
					</div>
				</div><!--/ tab4-->			
				
		</div><!--/ tab content-->
	</div><!--/ tabbable-->	
</div><!--/ author-content-->

			</div><!--/ content-->
<?php // NO SIDEBAR ?>
		</div><!--/ main-->
	</div><!--/ wrapper-->		
</div><!--/ row-content-->
<?php get_footer(); ?>
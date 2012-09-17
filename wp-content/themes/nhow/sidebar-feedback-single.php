<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
global $current_user;
$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_slug = $nh_author->user_login;
$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;
?>
<div id="sidebar-int" class="sidebar-nh">	

	<div class="widget-side">
		<!--h5 class="widget-title">Details</h5-->
			<div class="widget-copy">
				<div class="guide-details">
					<p class="gde-avatar">
<?php
$nh_avatar_alt = 'Photo of '.$nh_author_name;
$nh_avatar = get_avatar($nh_author_id, '48','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
echo '<img class="avatar" alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=48&h=48&q=95&zc=1">';
}
else {
echo $nh_avatar;
}
?>
				</p>
				<p class="gde-byline"><?php echo $nh_author_name;?><span class="byline">by</span> <?php echo the_author_posts_link();?><br/>				
					<span class="byline">on</span> <?php the_date();?></p>
				<ul class="gde-meta">
<?php 
if (have_comments()) {
	echo '<li>';
	echo '<img src="'.$style_url.'/lib/timthumb.php?src=/images/icons/comment.png&h=16&zc=1&at=t" alt="Number of comments"> ';
	comments_number( '', '1', '%' );
	echo '</li>'; 
}
?>
					<li><img src="<?php echo $style_url;?>/lib/timthumb.php?src=/images/icons/eyeball.png&h=14&zc=1&at=t" alt="Number of views"> <?php if(function_exists('the_views')) { the_views(); } ?></li>												
				</ul>
			</div>
		</div>
	</div><!-- widget-side-->
	
	<div class="widget-side">			
		<!--h5 class="widget-title">Tools</h5-->			
		<div class="widget-copy">
			<div class="guide-details">		
				<ul class="gde-actions">	
					<li><a class="nhline" href="#leavecomment" title="Add Your Comment">Add a Comment</a></li>
				</ul>
			</div>
		</div>
	</div><!-- widget-side-->

	<div class="widget-side">			
		<h5 class="widget-title">Explore More In</h5>			
		<div class="widget-copy">
			<div class="guide-details">				
				<ul class="gde-actions">
<?php 
$parent_cat = get_cat_ID('feedback');
$cats = array(
	'orderby' => 'name',
	'order' => 'ASC',
	'child_of' => $parent_cat
);
$subcategories = get_categories($cats);
foreach($subcategories as $subcategory) {
	echo '<li><a href="' . get_category_link( $subcategory->term_id ) . '" title="View all feedback in '.$subcategory->name.'">'.$subcategory->name.'</a> </li> ';
}
?>
					
<?php
// popular in city? - see cities
?>						
				</ul>
			</div>
		</div>
	</div><!-- widget-side-->	
		
	</div><!--/ widget-->
</div><!--/ sidebar-->
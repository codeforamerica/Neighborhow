<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_slug = $nh_author->user_login;

$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;

?>
<div id="sidebar-nh" class="sidebar-nh">	

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
echo '<img class="avatar" alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=48&h=48&q=100&zc=1">';
echo userphoto($nh_author_id);
}
else {
echo $nh_avatar;
}
?>
				</p>
				<p class="gde-byline"><span class="byline">by</span> <a href="<?php $tmp = get_userdata($nh_author_id); echo $app_url;?>/author/<?php echo $nh_author_slug;?>" title="Sign In now"><?php the_author();?></a></p>
				<ul class="gde-meta">
					<li><img src="<?php echo $style_url;?>/lib/timthumb.php?src=/images/icons/heart.png&h=14&zc=1&at=t" alt="Number of likes"> 
<?php 
$tmp = lip_get_love_count($post_id); 
echo '<span class="love-count">'.$tmp.'</span>';
//echo '<a href="#" class="love-it nh-btn-blue" data-post-id="' . $post_id . '" data-user-id="' .  $user_ID . '">Love it</a> <span class="love-count">' . $love_count . '</span>';
//lip_get_love_count($post->ID);

?>
</li>
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
				<p class="side-buttons"><a href="<?php echo $app_url;?>/XXX" class="nh-btn-blue">Do This</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php lip_love_it_link();?></p>
				<ul class="gde-tools">
					<li><a href="" title="">Share This</a></li>	
					<li><a href="" title="">Add a Comment</a></li>											
					<li><a href="" title="">Add a Tip</a></li>
					<li><a href="" title="">Add a Resource</a></li>
				</ul>
			</div>
		</div>
	</div><!-- widget-side-->

	<!--div class="widget-side">			
		<h5 class="widget-title">Explore More</h5>			
		<div class="widget-copy">
			<div class="guide-details">				
				<ul class="gde-tools">
					<li>Categories</li>
					<li>Cities</li>
				</ul>
			</div>
		</div>
	</div--><!-- widget-side-->	
		
	</div><!--/ widget-->
</div><!--/ sidebar-->
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
<?php
$guide_city = get_post_meta($post->ID,'gde-user-city',true);
$guide_city_slug = strtolower($guide_city);
$guide_city_slug = str_replace(' ','-',$guide_city_slug);
?>					
					<span class="byline">on</span> <?php the_date();?><br/>
					<span class="byline">for</span> <a class="nhline" href="<?php echo $app_url;?>/cities/<?php echo $guide_city_slug;?>" title="See other Neighborhow Guides for this city"><?php echo $guide_city;?></a></p>
				<ul class="gde-meta">
					<li><img src="<?php echo $style_url;?>/lib/timthumb.php?src=/images/icons/heart.png&h=14&zc=1&at=t" alt="Number of likes"> 
<?php 
$tmp = lip_get_love_count($post->ID); 
echo '<span class="nh-love-count">'.$tmp.'</span>';
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
				<p class="side-buttons">
<?php 
if (lip_user_has_loved_post($current_user->ID, $post->ID)) {
	echo '<a id="likedthis" title="See your other Likes" href="'.$app_url.'/author/'.$current_user->user_login.'" class="likedthis nhline">You like this</a>';
}
else {
	lip_love_it_link();
}
?>
				</p>
				<p class="side-buttons">
					<span class='st_facebook_large' displayText='Facebook' style="margin -top:-2em;"></span>
				<span class='st_twitter_large' displayText='Tweet'></span>
				<span class='st_email_large' displayText='Email'></span></p>
				
				<ul class="gde-actions">	
					<li><a class="nhline" href="#leavecomment" title="Add Your Comment">Add a Comment</a></li>											
					<li><a class="nhline" href="" title="">Add a Tip</a></li>
					<li><a class="nhline" href="" title="">Add a Resource</a></li>
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
$post_categories = wp_get_post_categories($post->ID);
$cats = array();
foreach($post_categories as $c){
	$cat = get_category($c);
	$cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
	if (!$cat->name == "Guides") {
		echo '<li><a href="'.$app_url.'/'.$cat->slug.'" title="See all Neighborhow Guides in '.$cat->name.'">'.$cat->name.'</a></li>';
	}
}
?>
					
<?php
$post_cities = wp_get_post_terms($post->ID,'nh_cities');
$cats = array();
foreach($post_cities as $city){
	echo '<li><a href="'.$app_url.'/'.$city->slug.'" title="See all Neighborhow content for '.$city->name.'">'.$city->name.'</a></li>';
}
?>						
				</ul>
			</div>
		</div>
	</div><!-- widget-side-->	
		
	</div><!--/ widget-->
</div><!--/ sidebar-->
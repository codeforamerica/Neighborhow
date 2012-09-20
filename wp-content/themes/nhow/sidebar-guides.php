<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
get_currentuserinfo();
$auth_id = $post->post_author;

$nh_author = get_userdata($auth_id);
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
$nh_avatar = get_avatar($auth_id, '48','',$nh_avatar_alt);
echo $nh_avatar;
?>
				</p>
				<p class="gde-byline"><span class="byline">by</span> 
<?php 
echo '<a href="'.$app_url.'/author/'.$nh_author_slug.'" title="See '.$nh_author_name.'&#39;s profile">';
echo $nh_author_name;
echo '</a>';
?><br/>
					<span class="byline">on</span> <?php the_date();?><br/>
					<span class="byline">for</span> 
<?php
$post_cities = wp_get_post_terms($post->ID,'nh_cities');
$user_guide_cities = get_post_meta($post->ID,'gde-user-city',true);

// Post cities are official NH cities
if ($post_cities) {
	foreach ($post_cities as $post_city) {
		echo '<a class="nhline" href="'.$app_url.'/cities/'.$post_city->slug.'" title="See other Neighborhow Guides for this city">'.$post_city->name.'</a>, ';
	}
}

// User guide cities are cities input by users
// Not official yet so dont link to a city page
elseif ($user_guide_cities) {
	$user_guide_city = explode(',', $user_guide_cities);
	foreach ($user_guide_city as $city) {
		$slug = str_replace(' ','-', $city);
		$slug = strtolower($slug);
		echo '<a class="nhline" href="'.$app_url.'/cities/'.$slug.'" title="See other Neighborhow Guides for this city">'.$city.'</a>, ';
	}
}
?>					
				</p>	
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
			</div><!--/guide details-->
		</div><!--/widget copy-->
	</div><!-- widget-side-->
	
	<div class="widget-side" style="padding-top:1.25em !important;">			
		<!--h5 class="widget-title">Tools</h5-->			
		<div class="widget-copy">
			<div class="guide-details">
<?php 
if (lip_user_has_loved_post($current_user->ID, $post->ID)) {
	echo '<a id="likedthis" title="See your other Likes" href="'.$app_url.'/author/'.$current_user->user_login.'" class="likedthis nhline">You like this</a>';
}
else {
	lip_love_it_link();
}
?>
<?php 
// Turn on if function when workign locally - doesnt work hosted
//if ( function_exists( 'nh_sharing_display' ) ) 
echo sharing_display(); ?>
				<br/><a class="nhline" href="#leavecomment" title="Add Your Comment">Add a Comment</a>
			</div><!--/ guide details-->
		</div>
	</div><!-- widget-side-->

	<div class="widget-side">			
		<h5 class="widget-title">Explore More In</h5>			
		<div class="widget-copy">
			<div class="guide-details">				
				<ul class="gde-actions">
<?php 
/*
$post_categories = wp_get_post_categories($post->ID);
$cats = array();
foreach($post_categories as $c){
	$cat = get_category($c);
	$cats[] = array( 'name' => $cat->name, 'slug' => $cat->slug );
	if (!$cat->name == "Guides") {
		echo '<li><a href="'.$app_url.'/'.$cat->slug.'" title="See all Neighborhow Guides in '.$cat->name.'">'.$cat->name.'</a></li>';
	}
}
*/
?>
<?php
$post_cities = wp_get_post_terms($post->ID,'nh_cities');
$user_guide_cities = get_post_meta($post->ID,'gde-user-city',true);
if ($post_cities) {
	foreach ($post_cities as $post_city) {
		echo '<li><a class="nhline" href="'.$app_url.'/cities/'.$post_city->slug.'" title="See other Neighborhow Guides for this city">'.$post_city->name.'</a></li>';
	}
}
elseif ($user_guide_cities) {
	$user_guide_city = explode(',', $user_guide_cities);
	foreach ($user_guide_city as $city) {
		$slug = str_replace(' ','-', $city);
		$slug = strtolower($slug);
		echo '<li><a class="nhline" href="'.$app_url.'/cities/'.$slug.'" title="See other Neighborhow Guides for this city">'.$city.'</a></li>';
	}
}
?>						
				</ul>
			</div><!--/guide details-->
		</div><!--widget copy-->
	</div><!-- widget-side-->	
</div><!--/ sidebar-->
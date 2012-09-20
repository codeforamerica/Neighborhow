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
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
echo $nh_avatar;
?>
				</p>
				<p class="gde-byline"><span class="byline">by</span> 
<?php 
echo '<a href="'.$app_url.'/author/'.$nh_author_slug.'" title="See '.$nh_author_name.'&#39;s profile">';
echo $nh_author_name;
echo '</a>';
?><br/>								
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
			</div><!--guide details-->
		</div><!--widget copy-->
	</div><!-- widget-side-->
	
	<div class="widget-side">			
		<!--h5 class="widget-title">Tools</h5-->			
		<div class="widget-copy">
			<div class="guide-details">		
<?php 
if (nh_user_has_voted_post($current_user->ID, $post->ID)) {
	echo '<a style="font-style:italic;font-family:Georgia,serif;line-height:200% !important;" id="votedthis" title="See your other Votes" href="'.$app_url.'/author/'.$current_user->user_login.'" class="votedthis nhline">You voted</a>';
}
else {
	echo '<span style="line-height:250% !important;">';
	nh_vote_it_link();
	echo '</span>';
}
?>
<?php 
// Turn on if function when workign locally - doesnt work hosted
//if ( function_exists( 'nh_sharing_display' ) ) 
echo sharing_display(); ?>
				<br/><a class="nhline" href="#leavecomment" title="Add Your Comment">Add a Comment</a>

			</div><!--guide details-->
		</div><!--widget copy-->
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
			</div><!--/guide details-->
		</div><!--widget copy-->
	</div><!-- widget-side-->	
</div><!--/ sidebar-->
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
get_currentuserinfo();
$viewer_id = $current_user->ID;
$viewer = get_userdata($viewer_id);
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Do Something</h5>
		<div class="widget-copy">
			<ul>
<?php
if (is_user_logged_in()) :
?>						
						<li><a href="<?php echo $app_url;?>/author/<?php echo $viewer->display_name;?>">Visit your profile</a></li>
						<li><a href="">Create a guide</a></li>
						<li><a href="">Explore more</a></li>
						<li><a href="<?php echo wp_logout_url('home_url()');?>" title="Sign out of Neighborhow">Sign Out</a></li>												
<?php else : ?>				
						<li><a href="">Explore it</a></li>
						<li><a href="">Sign in</a></li>
						<li><a href="">Sign up</a></li>
<?php
endif;
?>						
			</ul>
		</div>
	</div>
</div>
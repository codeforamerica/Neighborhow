<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
global $current_user;
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Your Profile</h5>
		<div class="widget-copy">
			<ul>
				<li><a class="nhline" href="<?php echo $app_url;?>/author/<?php echo $current_user->user_login;?>" title="Edit Settings">Check out your public profile</a></li>
			</ul>
		</div>			
	</div><!--/ widget-->

</div><!--/ sidebar-->
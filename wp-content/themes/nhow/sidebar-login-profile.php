<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
global $current_user;
get_currentuserinfo();
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Other Settings</h5>
		<div class="widget-copy">
			<ul class="bullets">
				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/?wp-subscription-manager=<?php echo $current_user->ID;?>" title="Edit Email Notification Settings">Edit Email Notification Settings</a></li>
			</ul>
		</div>			
	</div><!--/ widget-->

</div><!--/ sidebar-->
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">My Neighborhow Account</h5>
		<div class="widget-copy">
			<ul>
				<li class="dropdown" id="menu2"><a class="dropdown-toggle" data-toggle="dropdown" href="#menu2" title="My Guides">My Guides <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="">Guide 1</a></li>
						<li><a href="">Guide 2</a></li>
						<li><a href="">Guide 3</a></li>					
					</ul>
				</li>
				<li><a href="<?php echo $app_url;?>/profile" title="Edit Settings">Settings</a></li>
				<li><a href="<?php echo wp_logout_url('home_url()');?>" title="Sign out of Neighborhow">Sign Out</a></li>
			</ul>
		</div>
	</div>
</div>
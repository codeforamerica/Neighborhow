<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">	

	<div class="widget-side">
		<h5 class="widget-title">Already have an account?</h5>
		<div class="widget-copy">
			<ul>
				<li><a href="<?php echo $app_url;?>/signin" title="Sign In now">Sign In</a></li>							
				<li><a href="<?php echo $app_url;?>/lostpwd" title="Forgot password">Forgot your password?</a></li>
			</ul>
		</div>			
	</div><!--/ widget-->
						
<?php include(STYLESHEETPATH.'/include_about_nhow.php');?>				

</div><!--/ sidebar-->
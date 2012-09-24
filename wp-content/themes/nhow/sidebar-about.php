<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">	

	<div class="widget-side">
		<h5 class="widget-title">About Neighborhow</h5>
		<div class="widget-copy">
			<ul class="bullets">
				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/about" title="Read about Neighborhow">About Neighborhow</a></li>
				
				<!--li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/how" title="See how Neighborhow works">How It Works</a></li-->

				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/terms" title="View terms of service">Terms of Service</a></li>

				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/privacy" title="View privacy policy">Privacy Policy</a></li>
				
				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/contact" title="Email Neighborhow">Contact us</a></li>									
			</ul>
		</div>			
	</div><!--/ widget-->
	
<?php
if (!is_user_logged_in()) :
?>
<div class="widget-side">
	<div class="widget-buttons" style="margin-top:.25em !important;margin-bottom:1em !important;">
		<p>Start Making Your Neighborhood Better</p>
		<p style="margin-top:1em;"><a title="Create a Guide" href="<?php echo $app_url;?>/create-guide" class="nh-btn-blue">Create a Guide</a></p>
		<p style="margin-top:1.75em;"><a title="Explore Neighborhow Guides" href="<?php echo $app_url;?>/guides" class="nh-btn-blue">Start Exploring</a></p>
	</div>
</div><!--/ widget-->				
<?php
endif;
?>					

</div><!--/ sidebar-->
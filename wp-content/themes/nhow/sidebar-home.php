<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">About Neighborhow</h5>
		<div class="widget-copy">
			<p>Nam tincidunt pharetra odio, vel pulvinar magna eleifend eu. Etiam faucibus tellus dui, consequat volutpat nunc. Nullam lobortis vulputate leo eget pulvinar. Morbi venenatis ultricies lorem, sit amet facilisis nibh porta sed.</p><p>Nulla suscipit lobortis enim, ac congue lorem fringilla. <a href="<?php echo $app_url;?>/about" title="Read more about what Neighborhow is">read the whole story ></a></p>
		</div>
	</div>
<?php
if (is_user_logged_in()) :
?>			
	<div class="widget-side">
		<div class="widget-buttons">
			<a href="#" class="nh-btn-blue">Sign In</a>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="#" class="nh-btn-blue">Sign Up</a>
		</div>
	</div>
<?php
endif;
?>		
	<div class="widget-side">
		<h5 class="widget-title">Explore Neighborhow</h5>
		<div class="widget-copy">
			<ul>
				<li><a href="<?php echo $app_url;?>/resources" title="Find a resource to help you">Find a resource</a></li>
				<li><a href="<?php echo $app_url;?>/stories" title="Get inspired by success stories">Get inspiration</a></li>
				<li><a href="<?php echo $app_url;?>/feedback" title="Help us make Neighborhow better">Help make Neighborhow better</a></li>
				<li><a href="<?php echo $app_url;?>/blog" title="Learn what's new at Neighborhow">Learn about what&#39;s new</a></li>
				<li><a href="<?php echo $app_url;?>/contact" title="Contact us">Get in touch</a></li>
			</ul>
		</div>
	</div>
	
</div>
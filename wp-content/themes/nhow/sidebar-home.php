<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Make Neighborhow Better</h5>
		<div class="widget-copy">
			<ul>
				<li><a href="<?php echo $app_url;?>/contact" title="Submit a story">Submit a story</a> about something you&#39;ve done to improve your neighborhood</li>							
				<li>Improve Neighborhow by <a href="<?php echo $app_url;?>/feedback" title="Give feedback">giving us your feedback</a></li>
				<li>Help decide what&#39;s next for Neighborhow &#8212; <a href="<?php echo $app_url;?>/blog" title="Help decide what&#39;s next for Neighborhow">vote on the features and content you want</a></li>
				<li>If you&#39;re a city who wants Neighborhow in your city, <a href="<?php echo $app_url;?>/contact" title="Contact us">contact us</a>!</li>
				<!--li>Other questions or comments? <a href="<?php echo $app_url;?>/contact" title="Contact us">Contact us</a>.</li-->
			</ul>
		</div>
	</div><!--/ widget-->							
	<div class="widget-side">
		<h5 class="widget-title">About Neighborhow</h5>
		<div class="widget-copy">
			<p>Nam tincidunt pharetra odio, vel pulvinar magna eleifend eu. Etiam faucibus tellus dui, consequat volutpat nunc. Nullam lobortis vulputate leo eget pulvinar. Morbi venenatis ultricies lorem, sit amet facilisis nibh porta sed.</p><p>Nulla suscipit lobortis enim, ac congue lorem fringilla. <a href="<?php echo $app_url;?>/about" title="Read more about what Neighborhow is">read the whole story ></a></p>
		</div>
	</div><!--/ widget-->
<?php
if (is_user_logged_in()) :
?>			
	<div class="widget-side">
		<div class="widget-buttons">
			<a href="#" class="nh-btn-blue">Sign In</a>&nbsp;&nbsp;or&nbsp;&nbsp;<a href="#" class="nh-btn-blue">Sign Up</a>
		</div>
	</div><!--/ widget-->
<?php
endif;
?>				

</div><!--/ sidebar-->
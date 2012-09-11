<?php get_header(); ?>

<div class="row-fluid row-content">
	<div class="wrapper">
		<div id="main">
			<div id="content">
				
				<div id="site-promo">
					<h3 class="promo-title">Welcome to Neighborhow</h3>
					<h4 class="promo-copy">Neighborhow makes it easy to find and share ways to improve your neighborhood.</h4>
					<p class="promo-buttons">
					<a title="Start exploring" href="<?php echo $app_url;?>/guides" class="nh-btn-blue">Start Exploring</a>
					<a title="Create a Guide" href="<?php echo $app_url;?>/create-guide" class="nh-btn-blue">Create a Guide</a>
					</p>
				</div><!--/ site-promo-->				
				
				<div class="hfeed">
lskdfjlsjdkf
				</div><!--/ hfeed-->
			</div><!--/ content -->
<?php get_sidebar('home');?>
		</div><!--/ main-->		
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer();?>
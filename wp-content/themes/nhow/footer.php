<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div class="row-fluid row-footer">
	<div class="wrapper">	
		<div id="footer">
			<div class="span4 odd clearfix">
				<h3>About Neighborhow</h3>
				<ul>
					<li><a class="noline footer-link" title="See how Neighborhow works" href="<?php echo $app_url;?>/about">How It Works</a></li>
					<li><a class="noline footer-link" title="Read Frequently Asked Questions" href="<?php echo $app_url;?>/faqs">Frequently Asked Questions</a></li>	
					<li><a class="noline footer-link" title="See our Terms and Conditions" href="<?php echo $app_url;?>/terms">Terms &amp; Conditions</a></li>
					<li><a class="noline footer-link" title="Read our Privacy Policy" href="<?php echo $app_url;?>/privacy">Privacy Policy</a></li>														
				</ul>
			</div><!-- /span4 -->

			<div class="span4 middle clearfix">
				<h3>Contact</h3>
				<ul>
					<li><a class="noline footer-link" title="Get in touch with us" href="<?php echo $app_url;?>/contact">Email Us</a></li>
						<li><a class="noline footer-link" title="Find out what we&#39;ve been up to" href="<?php echo $app_url;?>/blog">Read the Blog</a></li>					
					<li><p class="footerp">Find us on:</p></li>
					<li>
						<ul class="sub-footer">
							<li><a target="_blank" title="Follow Neighborhow on Twitter" href="https://twitter.com/Neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/twitter.png" alt="Twitter logo" width="26" /></a></li>
							<li><a target="_blank" title="Like Neighborhow on Facebook" href="http://www.facebook.com/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/fb.png" alt="Facebook logo" width="26" /></a></li>
							<li><a target="_blank" title="Visit Neighborhow on Github" href="https://github.com/codeforamerica/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/github.png" alt="Github logo" width="26" /></a></li>
						</ul>
					</li>
				</ul>			
			</div><!-- /span4 -->
			<div class="span4 odd clearfix partnerul">
				<h3>Partners</h3>
				<ul class="partnerul">
					<li><p class="footerp">Neighborhow is a collaboration between the <a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia">City of Philadelphia</a> and <a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America">Code for America</a>.</p></li>
					<li class="partners"><a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia"><img width="70" src="<?php echo $style_url;?>/images/logo_phl.png" alt="City of Philadelphia logo"></a></li>	
					<li class="partners cfa"><a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America"><img width="70" src="<?php echo $style_url;?>/images/logo_cfa.png" alt="Code for America logo"></a></li>														
				</ul>
			</div><!-- /span4 -->			
		</div><!-- / footer-->
	</div><!--/ wrapper -->
</div><!--/ row-fluid -->
<?php wp_footer();?>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/jquery.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-collapse.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-transition.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-alert.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-modal.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-dropdown.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-scrollspy.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-tab.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-tooltip.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-popover.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-button.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-carousel.js"></script>
<script src="<?php bloginfo('stylesheet_directory'); ?>/lib/js/bootstrap-typeahead.js"></script>
<script src="<?php bloginfo('stylesheet_directory');?>/lib/js/application.js"></script>


<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/fancybox/jquery.fancybox-1.3.4.pack.js?ver=1.0'></script>

<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/fitvids/jquery.fitvids.js?ver=1.0'></script>

<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/js/footer-scripts.js?ver=1.0'></script>

<script type='text/javascript' src='http://devpress.com/demo/origin/wp-content/themes/origin/library/js/drop-downs.js?ver=20110920'></script>

<script>
$(document).ready(function() {
/*	$('.fancybox').fancybox({
		autosize:false,
		height:340,
		width:500,
		helpers : {
			title : null            
			}
	});
*/
	$('.dropdown-toggle').dropdown();
		
// STOP HERE		
});
</script>

</body>
</html>
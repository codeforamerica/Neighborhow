<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
			<div class="span4 odd clearfix">
				<h3 class="footer-header" style="text-align:center;">About Neighborhow</h3>
				<ul>
					<li><a class="noline footer-link" title="See how Neighborhow works" href="<?php echo $app_url;?>/about">How It Works</a></li>
					<li><a class="noline footer-link" title="Read Frequently Asked Questions" href="<?php bloginfo('url');?>/faqs">FAQs</a></li>						
				</ul>
			</div><!-- /span4 -->
		
			<div class="span4 even clearfix">
				<h3 class="footer-header" style="text-align:center;">Contact</h3>
				<ul class="unstyled flickr">
				<li><a class="noline footer-link" title="Contact" href="<?php bloginfo('url');?>/contact">Email Us</a></li>
					<li><a class="noline footer-link" title="Read the blog" href="<?php bloginfo('url');?>/blog">Read the Blog</a></li>					
				<li>
					<ul class="footer-social">
						<li><a target="_blank" title="Follow Neighborhow on Twitter" href="http://www.twitter.com/!/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/twitter.png" alt="Twitter logo" width="26" /></a></li>
						<li><a target="_blank" title="Like Neighborhow on Facebook" href="http://www.facebook.com/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/fb.png" alt="Facebook logo" width="26" /></a></li>
						<li><a target="_blank" title="Visit Neighborhow on Github" href="http://www.github.com/neighborhow"><img src="<?php echo $style_url;?>/images/icons/social/github.png" alt="Github logo" width="26" /></a></li>
					</ul>
				</li>
				</ul>			
			</div><!-- /span4 -->			
		
			<div class="span4 odd clearfix">
				<h3 class="footer-header" style="text-align:center;">Partners</h3>
				<!--p>Neighborhow is brought to you by Code for America and the City of Philadelphia.<br/--><a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia"><img style="float:left;" width="70" src="<?php echo $style_url;?>/images/logo_phl.png" alt="City of Philadelphia logo"></a> <a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America"><img style="float:left;position:relative;top:1.9em;margin-left:1em;" width="70" src="<?php echo $style_url;?>/images/logo_cfa.png" alt="Code for America logo"></a> </p>
			</div><!-- /span4 -->
		
			<!--div class="span12 footer-copy">&copy;Neighborhow 2012</div-->


	
	</div></wrap>
</div></container>

<?php wp_footer(); ?>

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

<!--script type="text/javascript" src="<?php //bloginfo('stylesheet_directory');?>/lib/js/jquery.easing.1.2.js"></script>
<script type='text/javascript' src='<?php //bloginfo('stylesheet_directory');?>/lib/js/jquery.scrollTo-1.4.2-min.js'></script>
<script type='text/javascript' src='<?php //bloginfo('stylesheet_directory');?>/lib/js/jquery.localscroll-1.2.7-min.js'></script-->

<!-- History.js --> 
<!--script defer src="<?php //bloginfo('stylesheet_directory');?>/lib/js/hashchange.js" type="text/javascript"></script-->

<!--script type="text/javascript" src="<?php //bloginfo('stylesheet_directory');?>/lib/js/fancybox/source/jquery.fancybox.pack.js"></script-->


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
		
		
});
</script>

</body>
</html>
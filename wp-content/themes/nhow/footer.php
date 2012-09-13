<?php
$style_url = get_bloginfo('stylesheet_directory');
global $app_url;
$app_url = get_bloginfo('url');
global $current_user;
?>
<div class="row-fluid row-footer">
	<div class="wrapper">	
		<div id="footer">
			
			<div class="span4 odd1 clearfix partners">
				<h6>Partners</h6>
				<ul class="partnerul footer">
					<li><p class="footerp">Neighborhow is a collaboration between the <a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia">City of Philadelphia</a> and <a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America">Code for America</a>.</p></li>
					<li class="partners"><a target="_blank" href="http://www.phila.gov" title="Go to City of Philadelphia"><img width="70" src="<?php echo $style_url;?>/images/logo_phl.png" alt="City of Philadelphia logo"></a></li>	
					<li class="partners cfa"><a target="_blank" href="http://www.codeforamerica.org" title="Go to Code for America"><img width="70" src="<?php echo $style_url;?>/images/logo_cfa.png" alt="Code for America logo"></a></li>														
				</ul>
			</div><!-- /span4 -->			
			
			<div class="span4 middle clearfix">
				<h6>About Neighborhow</h6>
				<ul class="footer">
					<li><a class="noline footer-link" title="See how Neighborhow works" href="<?php echo $app_url;?>/about">How It Works</a></li>
					<li><a class="noline footer-link" title="Read Frequently Asked Questions" href="<?php echo $app_url;?>/faqs">Frequently Asked Questions</a></li>	
					<li><a class="noline footer-link" title="See our Terms and Conditions" href="<?php echo $app_url;?>/terms">Terms &amp; Conditions</a></li>
					<li><a class="noline footer-link" title="Read our Privacy Policy" href="<?php echo $app_url;?>/privacy">Privacy Policy</a></li>														
				</ul>
			</div><!-- /span4 -->

			<div class="span4 odd2 clearfix">
				<h6>Contact</h6>
				<ul class="footer">
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
			
			<div class="span12 trade"><p class="trade">All Neighborhow code is open source and free for government and non-profit use, so <a class="whitelink" href="https://github.com/codeforamerica/neighborhow" title="Visit Neighborhow on Github">visit us on Github</a>.<br/>&#169; 2012 Neighborhow. The Neighborhow name and logo are trademarks of Neighborhow. All rights reserved.</p>
			</div>			
		</div><!-- / footer-->
	</div><!--/ wrapper -->
</div><!--/ row-fluid -->
<?php
$city_terms = get_terms('nh_cities');
foreach ($city_terms as $city_term) {
	$city_term = $city_term->name;
	$cities[] = $city_term;
}
$current_user_login = $current_user->user_login;
?>
<?php wp_footer();?>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo $style_url; ?>/lib/js/jquery.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-collapse.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-transition.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-alert.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-modal.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-dropdown.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-scrollspy.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-tab.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-tooltip.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-popover.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-button.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-carousel.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/bootstrap-typeahead.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/application.js"></script>
<script src="<?php echo $style_url; ?>/lib/js/jquery-ui-1.8.23.custom.min.js"></script>

<script>
$(document).ready(function() {
	$('.dropdown-toggle').dropdown();
});

$('#likethis').tooltip();

function removeMyFile(id){
	jQuery("input[name='item_meta["+id+"]']").val('');
	jQuery('#frm_field_'+id+'_container img, #remove_link_'+id).fadeOut('slow');
}

$(function() {
	var cities = <?php echo json_encode($cities); ?>;
	$( "#user_city" ).autocomplete({
		source: cities,
		minLength: 2
	});
});
</script>

<?php
//phpinfo();
?>

</body>
</html>
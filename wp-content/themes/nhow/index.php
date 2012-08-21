<?php get_header();?>
<?php if (is_user_logged_in()) : 
//TODO - 
// change to if user is cookied
?>
<div class="row-fluid row-promo">
	<div id="site-promo">
		<h2>Neighborhow makes it easy to find and share ways to improve your neighborhood.</h2>
		<p class="buttons">
		<a href="<?php echo $app_url;?>/guides" class="button button-start">Start Exploring</a><br/>
		<a href="<?php echo $app_url;?>/create-guide" class="button button-start">Create a Guide</a>
		</p>			
	</div><!--/ site-promo-->
</div><!--/ row-promo-->
<?php endif; ?>

<div class="row-fluid row-content">
	<div id="main">
		<div id="content">
			<div class="hfeed">
<?php 
$sticky = get_option('sticky_posts');
$args = array(
'posts_per_page' => 1,
'post_in' => $sticky,
'ignore_sticky_posts' => 1,
'post_status' => 'publish',
'orderby' => 'date',
'order' => 'DESC'
);
$query1 = new WP_Query($args);
while ( $query1->have_posts() ) : $query1->the_post();
$sticky_id = $post->ID;
if ($sticky[0]) :
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($sticky_id), 'full');
$do_not_duplicate = $sticky_id;
?>					
				<div id="post-<?php echo $sticky_id;?>" class="hentry sticky sticky-div"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=636&h=320&q=100&zc=1" alt="Photo of <?php echo the_title();?>" class="single-thumbnail featured" /></a>							
					<div class="entry-details">
						<p class="home-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a><br/>
						<span><?php echo the_date('j M Y');?></span>
						</p>
						<div class="home-author">
							<p class="home-avatar">
<?php 
$nh_author_alt = 'Photo of '.get_the_author();
$nh_author_id = get_the_author_meta('ID');
$nh_author_avatar = get_avatar($nh_author_id,'','identicon',$nh_author_alt);
echo $nh_author_avatar;
?>								
							</p>
							<p class="author vcard author-link"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>"><span class="byline">by</span> <?php the_author();?></a></p>
						</div>										
					</div><!--/ entry-details-->
<?php
endif;
endwhile;
?>
<?php wp_reset_query();?>					
				</div><!--/ sticky-div-->

				<div class="break break-feature"></div>
<?php
$args = array(
'posts_per_page' => 4,
'post_status' => 'publish',
'cat' => '-1', //exclude blog posts
'orderby' => 'date',
'order' => 'DESC'
);
$query2 = new WP_Query($args);
while ( $query2->have_posts() ) : $query2->the_post();
$feat_id = $post->ID; 
if( $feat_id == $do_not_duplicate ) continue;

$featImgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($feat_id), 'full');
?>						
				<div id="post-<?php echo $feat_id;?>" class="hentry feat-div"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $featImgSrc[0];?>&w=150&h=150&q=100&zc=1" alt="Photo of <?php echo the_title();?>" class="thumbnail featured" /></a>							
					<div class="feat-header">
						<p class="home-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a></p>
						<p class="author vcard author-link">
							<span><?php echo the_date('j M Y');?>
							&nbsp;&middot;&nbsp; <a class="gray-link url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>"><span class="byline">by</span> <?php the_author();?></a>
							&nbsp;&middot;&nbsp; <span class="byline">in</span>
<?php 
$feat_cats = get_the_category($feat_id);
$count = count($feat_cats);
$i = 1;
foreach ($feat_cats as $feat_cat) {
	$feat_cat_id = get_cat_ID($feat_cat->cat_name);
	$feat_cat_url = get_category_link($feat_cat_id);

	if ($i < $count) {
		echo '<a rel="tag" class="gray-link" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'">'.$feat_cat->cat_name.'</a> + ';
	}
	else {		
		echo '<a rel="tag" class="gray-link" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'">'.$feat_cat->cat_name.'</a>';
	}
$i++;
}
?> 
							</span></p> 										
					</div><!--/ feat-header-->
					<div class="feat-summary"><?php echo the_excerpt();?>											
					</div><!--/ feat-summary-->
				</div><!--/ feat-div-->
<?php 
endwhile;  
?>					
			</div><!--/ hfeed-->
			
			<div class="pagination loop-pagination"><span class='page-numbers current'>1</span><a class='page-numbers' href='http://demo.alienwp.com/origin/page/2/'>2</a><a class="next page-numbers" href="http://demo.alienwp.com/origin/page/2/">Next &rarr;</a>
			</div>
		</div><!--/ content-->
<?php get_sidebar('primary'); ?>
	</div><!--/ main-->
</div><!--/ row-content-->
		
<div class="row-fluid row-footer">
	<div id="footer2">
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
	</div><!--/ footer-->
</div><!--/ row-footer-->

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
		
// STOP HERE		
});
</script>	

</body>
</html>
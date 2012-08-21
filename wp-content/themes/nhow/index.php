<?php get_header();?>
		<div id="main">

			<div id="site-promo">
				<h2>Neighborhow makes it easy to find and share ways to improve your neighborhood.</h2>
				<p class="buttons">
				<a href="<?php echo $app_url;?>/guides" class="button button-start">Start Exploring</a><br/>
				<a href="<?php echo $app_url;?>/create-guide" class="button button-start">Create a Guide</a>
				</p>			
			</div><!--/ promo-->
			
			<div class="break break-promo"></div>
			
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
						<div class="sticky-header">
							<h2 class="entry-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a></h2>
							<div class="byline">
								<abbr class="published" title="Tuesday, January 17th, 2012, 1:54 pm"><?php echo the_date('j M Y');?></abbr> &middot; by 
								<span class="author vcard"><a class="gray-link url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>">
									<span class="byline">by</span> <?php the_author();?></a></span> &middot; in
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
								</span> 
							</div>										
						</div><!--/ sticky-header-->
	
						<div class="entry-summary"><?php echo the_excerpt();?>											
						</div><!--/ entry-summary-->
					</div><!--/ hentry-->

<?php 
endwhile; 
//endif; 
?>					
				</div><!--/ hfeed-->
				<div class="pagination loop-pagination"><span class='page-numbers current'>1</span><a class='page-numbers' href='http://demo.alienwp.com/origin/page/2/'>2</a><a class="next page-numbers" href="http://demo.alienwp.com/origin/page/2/">Next &rarr;</a>
				</div>
			</div><!--/ content-->
<?php get_sidebar('primary'); ?>
		</div><!--/ main-->
		
		<div id="footer">	
				<p class="copyright">Copyright &#169; 2012 <a class="site-link" href="http://demo.alienwp.com/origin" title="Origin" rel="home"><span>Origin</span></a></p>
				<p class="credit">Powered by <a class="wp-link" href="http://wordpress.org" title="State-of-the-art semantic personal publishing platform"><span>WordPress</span></a> and <a class="theme-link" href="http://alienwp.com/themes/origin/" title="Origin WordPress Theme"><span>Origin</span></a></p>
		</div><!--/ footer-->
	</div><!--/ wrap-->
</div><!--/ container-->

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
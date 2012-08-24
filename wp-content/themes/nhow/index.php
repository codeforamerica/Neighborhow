<?php get_header();?>
<?php if (is_user_logged_in()) { 
//TODO - 
// change to if user is cookied ??
?>
<div class="row-fluid row-promo">
	<div id="site-promo">
		<h2>Neighborhow makes it easy to find and share ways to improve your neighborhood.</h2>
		<p class="buttons">
		<a href="<?php echo $app_url;?>/guides" class="nh-btn-orange">Start Exploring</a><br/>
		<a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Create a Guide</a>
		</p>			
	</div><!--/ site-promo-->
</div><!--/ row-promo-->
<?php
}
?>
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
						<p class="entry-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a><br/>
						<span class="nh-meta"><?php echo the_date('j M Y');?></span>
						</p>
						<div class="home-author">
							<p class="home-avatar">
<?php 
$nh_author_alt = 'Photo of '.get_the_author();
$nh_author_id = get_the_author_meta('ID');
$nh_author_avatar = get_avatar($nh_author_id,'40','identicon',$nh_author_alt);
echo $nh_author_avatar;
?>								
							</p>
							<p class="author vcard author-link"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>"><span>by</span> <?php the_author();?></a></p>
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
				<div class="feat-container">
					<div id="post-<?php echo $feat_id;?>" class="hentry feat-div"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $featImgSrc[0];?>&w=150&h=150&q=100&zc=1" alt="Photo of <?php echo the_title();?>" class="thumbnail featured" /></a>	
					</div>					
					<div class="feat-details">
						<p class="entry-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a></p>
						<div class="feat-summary"><?php echo the_excerpt();?>											
						</div><!--/ feat-summary-->
						<p class="author vcard author-link">
							<span><?php echo the_date('j M Y');?>
							&nbsp;&middot;&nbsp; <a class="byline url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php the_author();?>"><span>by</span> <?php the_author();?></a>
							&nbsp;&middot;&nbsp; 
<?php 
$feat_cats = get_the_category($feat_id);
$count = count($feat_cats);
$i = 1;
foreach ($feat_cats as $feat_cat) {
	$feat_cat_id = get_cat_ID($feat_cat->cat_name);
	$feat_cat_url = get_category_link($feat_cat_id);

	if ($i < $count) {
		echo '<a rel="tag" class="byline" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'"><span>in</span> '.$feat_cat->cat_name.'</a> + ';
	}
	else {		
		echo '<a rel="tag" class="byline" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'"><span>in</span> '.$feat_cat->cat_name.'</a>';
	}
$i++;
}
?> 
							</span></p> 	
					</div><!--/ feat-details-->
				</div><!--feat-container-->
<?php 
endwhile;  
?>					
			</div><!--/ hfeed-->
			
			<div class="pagination loop-pagination"><span class='page-numbers current'>1</span><a class='page-numbers' href='http://demo.alienwp.com/origin/page/2/'>2</a><a class="next page-numbers" href="http://demo.alienwp.com/origin/page/2/">Next &rarr;</a>
			</div>
		</div><!--/ content-->
<?php get_sidebar('home'); ?>
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

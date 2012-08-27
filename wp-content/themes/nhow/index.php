<?php get_header();?>

<div class="row-fluid row-content">
	<div class="wrapper">
		<div id="main">
			<div id="content">
				<div class="hfeed">
<?php 
// sticky posts show automatically
// dont need to query for them 
$sticky_id = $post->ID;
$do_not_duplicate = $sticky_id;
//if ($sticky_id) {
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($sticky_id), 'full');	
//}
?>					
					<div id="post-<?php echo $sticky_id;?>" class="hentry sticky sticky-div"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>"><img class="sticky-img" src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=636&h=220&q=90&zc=1" alt="Photo of <?php echo the_title();?>" alt="Photo of <?php echo the_title();?>" class="single-thumbnail featured" /></a>	
						<div class="entry-details">
							<div class="entry-txt" style="">
								<p class="entry-title"><a href="<?php echo the_permalink();?>" title="<?php echo the_title();?>" rel="bookmark"><?php echo the_title();?></a></p>
								<!--p class="entry-excerpt">
<?php 
$entry_exc = get_the_excerpt();
$entry_exc = trim_by_chars($entry_exc,'130',' &#187;');
echo $entry_exc;
?><br/>
								<span class="nh-meta"><?php echo get_the_date('j M Y');?></span>
								</p-->
							</div>
<?php 
$nh_author_id = $post->post_author;
$nh_author_name = get_the_author_meta('display_name',$nh_author_id);
?>							
							<div class="entry-author">
								<p class="entry-avatar"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php echo $nh_author_name;?>" title="See more from <?php echo $nh_author_name;?>">

<?php
$nh_author_alt = 'Photo of '.$nh_author_name;
$nh_author_avatar = get_avatar($nh_author_id, '36','identicon',$nh_author_alt);
$nh_author_photo_url = nh_get_avatar_url($nh_author_avatar);
if ($nh_author_photo_url) {
	echo '<img alt="'.$nh_author_alt.'" src="'.$style_url.'/lib/timthumb.php?src='.$nh_author_photo_url.'&w=36&h=36&q=100&zc=1">';
}
else {
	echo $nh_author_avatar;
}
?>								</a></p>
								<p class="author vcard author-link"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php echo $nh_author_name;?>"><span>by</span> <?php echo $nh_author_name;?></a></p>
							</div>										
						</div><!--/ entry-details-->
<?php
//endif;
//endwhile;
//wp_reset_query();
?>						
					</div><!--/ sticky-div-->
					<div class="break break-feature"></div>					
<?php
$args2 = array(
'posts_per_page' => 4,
'post_status' => 'publish',
'cat' => '-1', //exclude blog posts
'orderby' => 'date',
'order' => 'DESC'
);
$query2 = new WP_Query($args2);
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
							<!--div class="feat-summary">
<?php
$sticky_exc = get_the_excerpt();
$sticky_exc = trim_by_chars($sticky_exc,'200',' &#187;');
echo '<p>'.$sticky_exc.'</p>';
?>																		
							</div--><!--/ feat-summary-->
<?php 
$nh_author_id = $post->post_author;
$nh_author_name = get_the_author_meta('display_name',$nh_author_id);
?>
							<p class="entry-avatar feat-avatar"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php echo $nh_author_name;?>" title="See more from <?php echo $nh_author_name;?>">

<?php
$nh_author_alt = 'Photo of '.$nh_author_name;
$nh_author_avatar = get_avatar($nh_author_id, '36','identicon',$nh_author_alt);
$nh_author_photo_url = nh_get_avatar_url($nh_author_avatar);
if ($nh_author_photo_url) {
echo '<img alt="'.$nh_author_alt.'" src="'.$style_url.'/lib/timthumb.php?src='.$nh_author_photo_url.'&w=36&h=36&q=100&zc=1">';
}
else {
echo $nh_author_avatar;
}
?>								</a>&nbsp;<span class="author-link"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php echo $nh_author_name;?>"><span class="byline">by</span> <?php echo $nh_author_name;?></a></span></p>
							<!--p class="author vcard author-link"><a class="url fn n" href="<?php echo $app_url;?>/author/<?php the_author();?>" title="See more from <?php echo $nh_author_name;?>"><span>by</span> <?php echo $nh_author_name;?></a></p-->

							
							<p class="author vcard author-link" style="margin-top:1em;">
<?php 
if (get_the_terms($feat_id,'nh_cities')) {
	$cities = get_the_terms($feat_id,'nh_cities');
	$countcity = count($cities);
	echo '&nbsp;&middot;&nbsp;<span class="byline">for</span> ';
	$i = 1;
	foreach ($cities as $city) {
		$city_name = $city->name;		
		$city_url = get_term_link($city->slug,'nh_cities');		
		if ($i < $countcity) {
			echo '<a rel="tag" href="'.esc_url($city_url).'" title="'.$city_name.'">'.$city_name.'</a> + ';
		}
		else {		
			echo '<a rel="tag" href="'.esc_url($city_url).'" title="'.$city_name.'">'.$city_name.'</a>';
		}
		$i++;
	}
}
?>			 
<?php 
$feat_cats = get_the_category($feat_id);
$countcats = count($feat_cats);
if ($countcats > 0) {
	echo '&nbsp;&middot;&nbsp;<span class="byline">in</span> ';
	$j = 1;
	foreach ($feat_cats as $feat_cat) {
		$feat_cat_id = get_cat_ID($feat_cat->cat_name);
		$feat_cat_url = get_category_link($feat_cat_id);

		if ($j < $countcats) {
			echo '<a rel="tag" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'">'.$feat_cat->cat_name.'</a> + ';
		}
		else {		
			echo '<a rel="tag" href="'.esc_url($feat_cat_url).'" title="'.$feat_cat->cat_name.'">'.$feat_cat->cat_name.'</a>';
		}
		$j++;
	}
}
?> 
&nbsp;&middot;&nbsp;<span class="byline">on</span> <?php echo the_date('j M Y');?>
								</p> 	
						</div><!--/ feat-details-->
					</div><!--/ feat-container-->
<?php 
endwhile;  
?>					
				</div><!-- .hfeed -->

				<!--div class="pagination loop-pagination"><span class='page-numbers current'>1</span>
<a class='page-numbers' href='http://devpress.com/demo/origin/page/2/'>2</a>
<a class="next page-numbers" href="http://devpress.com/demo/origin/page/2/">Next &rarr;</a></div-->

			</div><!--/ content -->
<?php get_sidebar('home');?>
			
		</div><!--/ main-->		
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer();?>
<?php get_header();?>

<div class="row-fluid row-content">
	<div class="wrapper">
		<div id="main">
			<div id="content">
				
				<div id="site-promo">
					<h3 class="promo-title">Welcome to Neighborhow</h3>
					<h4 class="promo-copy">Neighborhow makes it easy to find and share ways to improve your neighborhood.</h4>
					<p class="promo-buttons">
					<a href="<?php echo $app_url;?>/guides" class="nh-btn-orange">Start Exploring</a>
					<a href="<?php echo $app_url;?>/create-guide" class="nh-btn-orange">Create a Guide</a>
					</p>
				</div><!--/ site-promo-->				
				
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
					</div><!--/ sticky-div-->
					
					<!--div class="break break-feature"></div-->

				</div><!--/ hfeed-->
			</div><!--/ content -->
<?php get_sidebar('home');?>
			
		</div><!--/ main-->		
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer();?>
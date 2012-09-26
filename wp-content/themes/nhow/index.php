<?php get_header(); ?>
<div class="row-fluid row-content">
	<div id="beta"></div>
	<div class="wrapper">
		<div id="main">
			<div class="row-fluid home-promo">
				<div id="site-promo" class="span7">
					<h1 class="promo-copy">Neighborhow makes it easy<br/> to find and share ways to improve your neighborhood.</h1> 
					<h2 class="promo-copy">Browse Neighborhow Guides on topics submitted by neighbors, and create your own Guides to help them.</h2>
				</div>

				<div id="site-promo-list" class="span5">
					<h4>Make Your Neighborhood Better</h4>
					<p><span>1.</span>&nbsp;&nbsp;<a class="promo_suggest" href="<?php echo $app_url;?>/add-idea" data-title="Tell us about the content you want, and we'll make getting it a priority." rel="tooltip" data-placement="top"><button class="nh-btn-blue btn-fixed">Add an Idea for a Guide</button></a></p>

					<p><span>2.</span>&nbsp;&nbsp;<a class="promo_suggest" href="<?php echo $app_url;?>/create-guide" data-title="Share Your Neighborhow -- Create a Guide and share what you know with others." rel="tooltip" data-placement="top"><button class="nh-btn-blue btn-fixed">Create a Neighborhow Guide</button></a></p>

					<p><span>3.</span>&nbsp;&nbsp;<a class="promo_suggest" href="<?php echo $app_url;?>/request" data-title="Request Neighborhow -- Get Neighborhow for your city." rel="tooltip" data-placement="top"><button class="nh-btn-blue btn-fixed">Get Neighborhow for Your City</button></a></p>
				</div>
			</div><!--/ row-fluid inner-->
			
			<div class="row-fluid">
				<div class="span12">
					<div class="home-featured">
						<h5 class="widget-title">Explore These Neighborhow Guides</h5>
						<ul class="list-guides list-guides-home">
<?php
// if sticky ids show them
// must be in groups of 4 for home page
$sticky_ids = get_option('sticky_posts');
if(count($sticky_ids) != 0) {
	$sticky_args = array(
        'category_name' => 'guides',
        'posts_per_page' => 4,
        'post__in' => $sticky_ids
    );
    $sticky_query = new WP_Query($sticky_args);
	while ($sticky_query->have_posts()) : $sticky_query->the_post();
		$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		echo '<li class="guides-list" id="post-'.$post->ID.'"><a rel="bookmark" title="See '.get_the_title().'" href="'.get_permalink().'"><img style="text-decoration:none !important;" src="'.$style_url.'/lib/timthumb.php?src='.$imgSrc[0].'&w=184&h=135&zc=1&at=t" alt="Photo from '.get_the_title().'" />';
		echo '<div class="home-caption">';
		$pad = ' ...';
		$pic_title = trim_by_chars(get_the_title(),'60',$pad);
		echo '<p><a class="nhline link-other" rel="bookmark" title="See '.get_the_title().'" href="'.get_permalink().'">'.$pic_title.'</a></p>';
		echo '</div></li>';
	endwhile;
wp_reset_query();	
}
else {
	$normal_args = array(
        'category_name' => 'guides',
        'posts_per_page' => 4,
        'orderby' => 'date'
    );
	$normal_query = new WP_Query($normal_args);
	while ($normal_query->have_posts()) : $normal_query->the_post();
		$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
		echo '<li class="guides-list" id="post-'.$post->ID.'"><a rel="bookmark" title="See '.get_the_title().'" href="'.get_permalink().'"><img src="'.$style_url.'/lib/timthumb.php?src='.$imgSrc[0].'&w=184&h=135&zc=1&at=t" alt="Photo from '.get_the_title().'" /></a>';
		echo '<div class="home-caption">';
		$pad = ' ...';
		$pic_title = trim_by_chars(get_the_title(),'60',$pad);
		echo '<p><a class="nhline link-other" rel="bookmark" title="See '.get_the_title().'" href="'.get_permalink().'">'.$pic_title.'</a></p>';
		echo '</div></li>';
	endwhile;
wp_reset_query();
}
?>
<?php
echo '<div class="see_all"><a href="'.$app_url.'/guides" title="">See all Neighborhow Guides &#187;</a></div>';
?>
						</ul>
					</div>
				</div><!--/ span12-->
			</div><!--/ row-fluid inner-->
			
			<div class="row-fluid home-combo">
				<div class="span6 home-ideas">
					<h5 class="widget-title">Latest Neighborhow Ideas</h5>	
<p><a id="addfdbk" <?php if (!is_user_logged_in()) {echo ' rel="tooltip"';
	echo ' data-placement="bottom"';
	echo ' data-title="You\'ll need to sign in--or sign up--before you can add your idea."';
}
;?> alt="Add Your Idea" class="nh-btn-blue" href="<?php echo $app_url;?>/add-idea" >Add Your Idea</a></p>
						<ul class="list-ideas list-ideas-home">
<?php
$fdbk_sub_cat = get_cat_ID('ideas');
$fdbk_sub_args = array(
	'post_status' => 'publish',
	'cat' => $fdbk_sub_cat,
	'orderby' => 'date',
	'order' => DESC,
	'posts_per_page' => '5'
);
$fdbk_sub_query = new WP_Query($fdbk_sub_args);
if ($fdbk_sub_query->have_posts()) :
while($fdbk_sub_query->have_posts()) :
$fdbk_sub_query->the_post();	
?>					

<li class="ideas-list"><a class="nhline" href="<?php echo get_permalink();?>" title="See <?php echo the_title();?>"><?php echo the_title();?></a>&nbsp;&nbsp;<span class="meta meta-small"><span class="byline">added</span> <?php echo get_the_date();?></span></span></li>

<?php 
endwhile;
endif;
wp_reset_query();
?>								

<li class="ideas-list"><a class="nhline" href="<?php echo $app_url;?>/ideas" title="See all the ideas">See all the ideas &#187;</a></li>
						</ul>						
				</div><!--/ span4-->

				<div class="span6 home-about">
					<h5 class="widget-title">About Neighborhow</h5>
<?php
$page_id = get_ID_by_slug('about');
$post = get_post($page_id); 
$content = $post->post_content;
$content = trim_by_words($content,'140',nh_continue_reading_link());
echo $content;
?>					
				</div>
			</div><!--/ row-fluid inner-->
			
		</div><!--/ main-->		
	</div><!--/ wrapper-->
</div><!--/ row-fluid-->
<?php get_footer();?>
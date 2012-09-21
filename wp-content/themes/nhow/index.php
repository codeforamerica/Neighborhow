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
					<p><span>1.</span>&nbsp;&nbsp;<a id="promo_suggest" href="<?php echo $app_url;?>/add-idea" title="Join the Conversation -- Tell us about the content you want, and we'll make getting it a priority." rel="tooltip" data-placement="top"><button class="nh-btn-blue btn-fixed">Add an Idea for a Guide</button></a></p>

					<p><span>2.</span>&nbsp;&nbsp;<a id="promo_create" href="<?php echo $app_url;?>/create-guide" title="Share Your Neighborhow -- Create a Guide and share what you know with others." rel="tooltip" data-placement="top"><button class="nh-btn-blue btn-fixed">Create a Neighborhow Guide</button></a></p>

					<p><span>3.</span>&nbsp;&nbsp;<a id="promo_contact" href="<?php echo $app_url;?>/contact" data-title="<strong>Contact Us -- If you want Neighborhow for your city." rel="tooltip" data-placement="bottom"><button class="nh-btn-blue btn-fixed">Get Neighborhow for Your City</button></a></p>
				</div>
			</div><!--/ row-fluid inner-->
			
			<div class="row-fluid">
				<div class="span12">
					<div class="home-featured">
						<h5 class="widget-title">Explore These Neighborhow Guides</h5>
						<ul>
<?php
$guide_cat = get_category_id('guides');
$promo_args = array(
	'post_status' => 'publish',
	'cat' => $guide_cat
	);
$promo_query = new WP_Query($promo_args);
if ($promo_query->have_posts()) : 
while($promo_query->have_posts()) : $promo_query->the_post();
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
?>							

<li id="post-<?php echo $post->ID;?>"><a class="nhline" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=180&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />
	<div class="home-caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
		<p><a class="nhline" href="<?php echo get_permalink();?>" title="See <?php echo the_title();?>"><strong><?php echo $pic_title;?></strong></a></p>
	</div>	
</li>

<?php
endwhile;
endif;
?>
						</ul>
					</div>
				</div><!--/ span12-->
			</div><!--/ row-fluid inner-->
			
			<div class="row-fluid home-combo">
				<div class="span6 home-ideas">
					<h5 class="widget-title">Latest Neighborhow Ideas</h5>	
<p><a id="addfdbk" rel="tooltip" data-placement="bottom" class="nh-btn-blue" href="<?php echo $app_url;?>/add-idea" data-title="You'll need to sign in--or sign up--before you can add your idea.">Add Your Idea</a></p>
						<ul>
<?php
$fdbk_sub_cat = get_cat_ID('content');
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

<li><a href="<?php echo get_permalink();?>" title="See <?php echo the_title();?>"><?php echo the_title();?></a>&nbsp;&nbsp;<span class="meta meta-small"><span class="byline">added</span> <?php echo get_the_date();?></span></span></li>

<?php 
endwhile;
endif;
wp_reset_query();
?>								

<li><a href="<?php echo $app_url;?>/ideas/content" title="See all the ideas">See all the ideas &#187;</a></li>
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
<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">	
			<div class="row-fluid guides-list">					
				<div id="" class="span12">
					
				<h1 class="page-title">Neighborhow Guides</h1>
				<div class="intro-block">We&#39;re adding Neighborhow Guides all the time. You should too. You or tell a friend. And suggest some idea with the button.</div>
				</h1>
	
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
<li class="gde-list" id="post-<?php echo $post->ID;?>"><a class="nhline" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=174&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />
	
	<div class="home-caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
		<p><a class="nhline" href="<?php echo get_permalink();?>" title="See <?php echo the_title();?>"><strong><?php echo $pic_title;?></strong></a></p>
	</div>	
</li>
<?php endwhile; ?>	
<?php else : ?>	
<li id="post-no-guides">Sorry, there are no public Neighborhow Guides to see at this time.</li>
<?php endif; ?>	
</ul>							
		
				</div><!--/ span12-->
						
			</div><!--/ guides list-->
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>
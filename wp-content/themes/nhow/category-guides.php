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
				<div id="site-promo" class="span12">
					
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
<li class="gde-list" style="float:left;margin-right:1em;" id="post-<?php echo $post->ID;?>"><a class="nhline" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=180&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />
	
	
<!--li class="thumb-archive" id="post-<?php echo $post->ID; ?>">
	<div class="thumbnail thumbnail-wrapper" style="float:left;margin-right:1em;"><a rel="bookmark" title="View <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php bloginfo('stylesheet_directory');?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=180&h=140&zc=1&at=t" alt="Photo from <?php echo the_title();?>" /></a-->
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
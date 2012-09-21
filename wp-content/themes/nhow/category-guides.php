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
				<div class="intro-block">We&#39;re adding Neighborhow Guides all the time. You should too. You or tell a friend. And suggest some idea with the button.</p></div>
				</h1>
	
				<div id="post-<?php echo $post->ID;?>">			
<?php 
if (have_posts()) : ?>
<?php while(have_posts()) : the_post();?>
<?php 
$tmpID = $post->ID;
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($tmpID), 'full');
?>
						<li class="thumb-archive" id="post-<?php echo $tmpID; ?>">
							<div class="thumbnail thumbnail-wrapper" style="float:left;margin-right:1em;"><a rel="bookmark" title="View <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php bloginfo('stylesheet_directory');?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=180&h=140&zc=1&at=t" alt="Photo from <?php echo the_title();?>" /></a>
								<div class="caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
									<h5><a class="noline" rel="bookmark" href="<?php the_permalink();?>" title="View <?php the_title();?>"><?php echo $pic_title;?></a></h5>
								</div>
							</div>
						</li>
<?php endwhile; ?>
					</ul>	
<?php else : ?>	
					<ul class="thumbs">	
						<li class="thumb-archive" id="post">
							<div class="thumb-wrapper">
								<div class="caption">
									<h5>Apologies. There are no How-to Guides to see right now.</h5>							
								</div><!--/caption-->
							</div><!--/thumbnail-wrapper-->
						</li>
					</ul>
<?php endif; ?>								
				</div>		
				</div><!--/ span12-->
						
			</div><!--/ guides list-->
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>
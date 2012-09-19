<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">			
			<div id="content">
				<h1 class="page-title">Neighborhow Guides</h1>
				<p>We're adding new Neighborhow Guides all the time. If you've got an idea for a news one, let us know!</p>	
				</h1>
	
				<div id="post-<?php echo $TmpID;?>">			
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
			</div><!--/ content-->
<?php get_sidebar('home');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
<?php get_header(); ?>

<div id="container-int" class="container-int">
	<div class="row-fluid">
		<div class="span9">
			<?php nhow_breadcrumb(); ?>
			<h1 class="page-title page-int-title">Neighborhow Cities</h1>
			<p>We're adding new Neighborhow Guides all the time. If you've got an idea for a new one, let us know!</p>	
		</div>
		<div class="span3" style="border:1px solid red;">What guides do you want? <a rel="prettyPhoto" href="http://neighborhow/index.php?plugin=formidable&controller=forms&frm_action=preview&form=nizlpn&iframe=true&width=600&height=800" rel="wp-prettyPhoto[iframes]" title="Title Goes HERE(optional)">Click me</a>
		</div>
	</div><!--/ row-->
	<div class="row-fluid" style="margin-top:3em;">	
		<div class="span12">
			<div class="content content-archive">	
				<div class="span12 post-<?php echo $TmpID;?>">				
<?php 
$args = array(
	'post_type' => array('nh_guides'), //include projects
	'posts_per_page' => '-1',
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC'
);
query_posts($args);
if (have_posts()) : ?>
					<!--div style="float:right;margin-left:1em;width:220px;min-height:195px;margin-bottom:2em;border:1px solid red;">
					sdfsdf
					</div-->
					
					<ul class="thumbs">
						
						<!--li class="thumb-archive">
							<div class="thumbnail thumbnail-wrapper" style="float:left;margin-right:1em;">
								<div style="background-color:#45A648;height:100px;"><div style="color:#fff;font-size:1em;font-weight:700;letter-spacing:.1em;line-height:140%;padding:1em;">Tell us what the next <span>Neighborhow Guide</span> should be about.</div><div><?php //echo do_shortcode("[formidable id=9 description=false]"); ?></div>
							</div>
						</li-->
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
				</div><!--internal span-->	
				<!--div class="span3" style="border:1px solid red;">
					
					
				</div-->				
			</div><!--/content-->					
		</div><!--/span-->
	</div><!--/row-->
</div><!--/container-->
<?php //get_sidebar(); ?>
<?php get_footer(); ?>
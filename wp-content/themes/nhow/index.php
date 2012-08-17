<?php get_header(); ?>

<div id="main">
<!--div id="container">
	<div class="row-fluid">
		<div class="span12">
			<h1 class="page-title home-title">Want to make your neighborhood better?</h1>
			<div class="content content-home">
				<div class="span7">
					<div class="pic-theme">
						<img alt="Start project icon" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/glyphs/database.png">
						<h4><a class="noline" href="<?php bloginfo('stylesheet_directory');?>/about#start" title="Start a project">Start a Project ></a>
						<p>Create a project from a Neighborhow template, or suggest a new one.</p>
						</h4>
					</div>
					
					<div class="pic-theme">
						<img alt="Fund project icon" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/glyphs/database.png">
						<h4><a class="noline" href="<?php bloginfo('stylesheet_directory');?>/about#fund" title="Fund a project">Fund It ></a>
						<p>Use Neighborhow to raise money from neighbors, organizations, and businesses.</p>
						</h4>
					</div>
					
					<div class="pic-theme">
						<img alt="Do project icon" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/glyphs/database.png">
						<h4><a class="noline" href="<?php bloginfo('stylesheet_directory');?>/about#do" title="Do a project">Do It ></a>
						<p>Make your project a success using Neighborhow how-to guides and resources.</p>
						</h4>
					</div>
					
					<div class="pic-theme">
						<img alt="Share project icon" src="<?php bloginfo('stylesheet_directory'); ?>/images/icons/glyphs/database.png">
						<h4><a class="noline" href="<?php bloginfo('stylesheet_directory');?>/about#share" title="Do a project">Share It ></a>
							<p>Celebrate and share your story so others can learn from your best practices.</p>
						</h4>
					</div>
				</div><!/internal span>

				<div class="span1"></div></internal span>
				<div class="span4">
					<div class="container-sidebar">
						<div class="feature-box">
							<h4>Featured Neighborhow Guide</h4>
							<div class="box-padded">
<?php 
$args = array(
'post_type' => array('guides','projects'),
'posts_per_page' => '1',
'post_status' => 'publish',
'orderby' => 'date',
'order' => 'DESC'
);
$my_query = new WP_Query($args);
while ($my_query->have_posts()) : $my_query->the_post();
$nhow_postID = $post->ID;
$do_not_duplicate = $nhow_postID;
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($tmpID), 'full');
if (is_sticky($nhow_postID)) {
?>
								<a class="noline" title="View <?php echo the_title();?>" href="<?php echo the_permalink();?>"><img src="<?php bloginfo('stylesheet_directory');?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=268&q=100&zc=1" alt="Photo from <?php echo the_title();?>" /></a>
								<p class="caption-featured" style=""><a class="noline" title="See more about <?php echo the_title();?>" href="<?php echo the_permalink();?>">How to <?php the_title();?></a></p>								
<?php 
}
endwhile;
?>								
							</div>
						</div>
						<div class="feature-box nocolor"><p class="small-link"><a class="small-link" title="See all Guides" href="/guides">see all Neighborhow Guides ></a></p>
							<h4>Recent Neighborhow Guides</h4>
							<div class="box-padded">
								<ul>
<?php 
//if (have_posts()) : while (have_posts()) : the_post(); 
//if( $post->ID == $do_not_duplicate ) continue;
?>								
									<li><a class="noline" title="See more about <?php //echo the_title();?>" href="<?php //echo the_permalink();?>"><?php the_title();?></a></li>
<?php
//endwhile;
//endif;
?>								
								</ul>								
							</div>
						</div>
					</div></ container-sidebar>
				</div></internal span>
			</div></span>
		</div></row>
		<div class="row-fluid">	
			<div class="span12" style="margin-top:1em;border:1px solid blue;height:100px;">
				<div class="span4" style="border:1px solid red;">
					what is neighborhow
				</div>
				
				<div class="span4" style="border:1px solid red;">
					how you can help - suggest guides and topics, sign up to do a project, fund a project, share your knowledge
				</div>
				
				<div class="span4" style="border:1px solid red;">
					Ask people to suggest new Guides to be written
<a class="btn btn-success fancybox fancybox.iframe" rel="gallery" href="http://neighborhow/modal-forms/?form=nizlpn" title="Suggest a Guide Topic!">Suggest a Guide Topic</a>
							
							
							
						</div>
				</div>
			</div>

		</div></row>
	</div></content>	
</div></container-->

</div><!--/main-->
</div><!--/wrap-->
</div><!--/container-->
<?php get_footer(); ?>
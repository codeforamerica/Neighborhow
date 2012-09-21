<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			
			<div class="row-fluid">
				<div class="span8">	
					<h3 class="page-title">Neighborhow Guides</h3>
					<div class="intro-block noborder"><p>A Neighborhow Guide can be about anything that&#39;s useful to people in a community. Maybe that&#39;s how to organize a block party. Or how to get a free backyard tree from the city. Or maybe it&#39;s how to track blighted properties in the neighborhood.</p><p>If it&#39;s something someone knows how to do, it&#39;s probably something other people want to know how to do. So suggest a topic for a new Neighborhow Guide, create your own Guide, or ask a friend to write one.</p>
					</div>
				</div>
				<div class="span4 sidebar-faux">
					<div class="sidebar-button-panel">
						<a href="<?php echo $app_url;?>/add-idea" title="Join the Conversation -- Tell us about the content you want, and we'll make getting it a priority."><button class="nh-btn-blue btn-fixed-small">Add an Idea for a Guide</button></a>
						
						<a href="<?php echo $app_url;?>/create-guide" title="Share Your Neighborhow -- Create a Guide and share what you know with others."><button class="nh-btn-blue btn-fixed-small">Create a Guide</button></a>
<?php
// Turn on if function when workign locally - doesnt work hosted
//if ( function_exists( 'nh_sharing_display' ) ) 
echo '<div class="jetpack-cat-guides">';
echo sharing_display(); 
echo '</div>';
?>
						<!--br/><button class="nh-btn-blue btn-fixed-small">Tell a Friend</button-->			
					</div><!--/ widget-->	
				</div>
			</div><!--/ row-fluid-->
			
			<div id="content-full" class="row-fluid">
				<div class="span12">
					<ul class="guides-list">
<?php
$guide_cat = get_category_id('guides');
$list_args = array(
	'post_status' => 'publish',
	'cat' => $guide_cat
	);
$list_query = new WP_Query($list_args);
if ($list_query->have_posts()) : 
while($list_query->have_posts()) : $list_query->the_post();
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
?>
<li class="gde-list" id="post-<?php echo $post->ID;?>"><a class="nhline" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=180&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />
	
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
					</ul><!-- / guides-list-->							
				</div>
			</div><!--/ row-fluid-->
				
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>
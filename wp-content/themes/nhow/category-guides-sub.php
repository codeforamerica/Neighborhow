<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
$cat = get_the_category();
$cat_name = $cat[0]->name;
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">			
			<div class="row-fluid">
				<div class="span8">	
					<h3 class="page-title">Neighborhow Guides &#8212; <?php echo $cat_name;?></h3>
					<div class="intro-block noborder"><p>A Neighborhow Guide can be about anything that&#39;s useful to people in a community. Maybe that&#39;s how to organize a block party. Or how to get a free backyard tree from the city. Or maybe it&#39;s how to track blighted properties in the neighborhood.</p><p>If it&#39;s something someone knows how to do, it&#39;s probably something other people want to know how to do. So suggest a topic for a new Neighborhow Guide, create your own Guide, or ask a friend to write one.</p>
					</div>
				</div>
				<div class="span4 sidebar-faux">
					<div class="sidebar-button-panel">
						<a class="btns" href="<?php echo $app_url;?>/add-idea" rel="tooltip" data-title="Join the Conversation -- Tell us about the content you want, and we'll make getting it a priority."><button class="nh-btn-blue btn-fixed-small" data-placement="top">Add an Idea for a Guide</button></a>

						<a class="btns" href="<?php echo $app_url;?>/create-guide" rel="tooltip" data-title="Share Your Neighborhow -- Create a Guide and share what you know with others." data-placement="top"><button class="nh-btn-blue btn-fixed-small">Create a Guide</button></a>
<?php
// Turn off function when working locally - only works hosted
echo '<div class="jetpack-cat-guides">';
//echo sharing_display(); 
echo '</div>';
?>
						<!--br/><button class="nh-btn-blue btn-fixed-small">Tell a Friend</button-->			
					</div><!--/ widget-->	
				</div>
			</div><!--/ row-fluid-->
			
			<div id="content-full" class="row-fluid">
				<div class="span12" id="list-guides">
					<ul class="list-guides">
<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$guide_sub_cat = get_cat_ID($cat[0]->name);
$list_sub_args = array(
	'post_status' => 'publish',
	'cat' => $guide_sub_cat,
	'posts_per_page' => -1,
//	'paged' => $paged	
	);
$list_sub_query = new WP_Query($list_sub_args);
if ($list_sub_query->have_posts()) : 
while($list_sub_query->have_posts()) : $list_sub_query->the_post();
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
?>

<li class="guides-list" id="post-<?php echo $post->ID;?>"><a class="nhline link-other" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=184&h=135&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />

	<div class="home-caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
		<p><?php echo $pic_title;?></a></p>
	</div>	
</li>

<?php endwhile; ?>	
<?php else : ?>	

<li class="guides-list" id="post-no-guides">Sorry, there are no public Neighborhow Guides to see at this time.</li>

<?php 
endif; 

$big = 999999999; // need an unlikely integer
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link($big) ) ),
	'format' => '?paged=%#%',
	'current' => max(1, get_query_var('paged')),
	'total' => $wp_query->max_num_pages
));

wp_reset_query();
?>	
					</ul><!-- / list-guides-->							
				</div>
			</div><!--/ row-fluid-->

		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
<?php //The template for displaying Search Results pages */ ?>
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
				<h3 class="page-title">Search Results</h3>
				<div id="list-fdbk">
					<ul class="list-fdbk">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<li class="fdbk-list" id="post-<?php echo $post->ID; ?>"><strong><a href="<?php echo get_permalink();?>" title="View <?php echo the_title();?>"><?php echo the_title();?></a></strong>
	<div class="search-results">
<?php 
$tmp = get_the_content();
$content_trimmed = trim_by_words($tmp,'30',nh_continue_reading_link());
echo '<p>'.$content_trimmed.'</p>';?>
	
<?php
$categories = get_the_category();
if ($categories) {
	echo '<p><span class="byline">in</span> ';
	foreach ($categories as $cat) {
		$cat_name = $cat->name;
		$cat_id = get_cat_ID($cat_name);
		$cat_link = get_category_link($cat_id);
		echo '<a href="'.$cat_link.'" title="View '.$cat->name.'">';
		echo $cat->name;
		echo '</a>';
	}	
}
?>		
		</p>
	</div>

<?php endwhile; ?>
<?php else : ?>

<li class="fdbk-list" style="border-bottom:none;">Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
<?php get_search_form(); ?></li>

					</ul>
<?php endif; ?>					
				</div>

			</div><!--/ content-->
<?php get_sidebar('misc');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
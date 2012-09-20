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
				<div id="list-feedback">
					<ul class="list-feedback">

<?php if (have_posts()) : ?>
<?php while (have_posts()) : the_post(); ?>

<li class="list-vote" id="post-<?php echo $post->ID; ?>"><a href="<?php echo get_permalink();?>" title="View <?php echo the_title();?>"><?php echo the_title();?></a>
	<div class="search-results">
<?php 
$tmp = get_the_content();
$content_trimmed = trim_by_words($tmp,'24',nh_continue_reading_link());
echo '<p>'.$content_trimmed.'</p>';?>
	<p><span class="byline">in</span>
<?php
$categories = get_the_category();
foreach ($categories as $cat) {
	$cat_name = $cat->name;
	$cat_id = get_cat_ID($cat_name);
	$cat_link = get_category_link($cat_id);
	echo '<a href="'.$cat_link.'" title="View '.$cat->name.'">';
	echo $cat->name;
	echo '</a>';
}
?>		
		</p>
	</div>

<?php endwhile; ?>
<?php else : ?>

<li class="list-vote" style="border-bottom:none;">Sorry, but nothing matched your search criteria. Please try again with some different keywords.</p>
<?php get_search_form(); ?></li>

					</ul>
<?php endif; ?>					
				</div>



<!--article id="post-0" class="post no-results not-found">
<header class="entry-header">
<h1 class="entry-title"><?php //_e( 'Nothing Found', 'twentyeleven' ); ?></h1>
</header><.entry-header>

<div class="entry-content">
<p><?php //_e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyeleven' ); ?></p>
<?php get_search_form(); ?>
</div--><!-- .entry-content -->
<!--/article><!-- #post-0 -->



			</div><!--/ content-->
<?php get_sidebar('home');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
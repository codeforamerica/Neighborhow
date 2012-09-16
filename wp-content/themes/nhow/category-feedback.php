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
				<h3 class="page-title">Neighborhow Feedback</h3>
				<div class="intro-block">Help make Neighborhow better by voting on the features and content you&#39;d like to see on Neighborhow. Or add your own feedback.</div>
					
				<div id="list-feedback">
					<div class="intro-block-button"><a class="nh-btn-green" href="" title="">Add Feedback</a></div>			

<?php 
$fdk_cat = get_category_id('feedback');
$fdbk_args = array(
	'post_status' => 'publish',
	'cat' => $fdk_cat
);
$fdbk_query = new WP_Query($fdbk_args);
if (!$fdbk_query->have_posts()) : ?>

					<p>if nothing found</p>

<?php else: ?>
					<ul class="list-fdbk">
<?php while($fdbk_query->have_posts()) : $fdbk_query->the_post();?>

						<li class="list-vote" id="post-<?php echo $post->ID; ?>">
							<div class="vote-box"><?php wdpv_vote(false); ?>
							</div>
							<div class="vote-question"><strong><a href="<?php the_permalink();?>" title=""><?php the_title();?></a></strong>
								<p>
<?php 
$tmp = get_the_excerpt();
$link = nh_continue_reading_link();
$excerpt = trim_by_words($tmp,14,$link);
echo $excerpt;?>
								</p>
								<p class="comment-meta"><span class="byline"><?php comments_number( '', '1 comment', '% comments' ); ?></span></p>
								<p class="comment-meta"><span class="byline">in </span>
<?php 
$category = get_the_category(); 
foreach ($category as $cat) {
	echo '<a href="'.$app_url.'/feedback/'.$cat->slug.'" title="">';
	echo $cat->name;
	echo '</a>';
}
?>
								</p>								
								
							</div>							
						</li>
<?php endwhile; ?>
					</ul>					
<?php endif; ?>								
				</div>				
			</div><!--/ content-->
<?php get_sidebar('feedback');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
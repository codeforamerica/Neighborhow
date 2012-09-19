<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
$cat = get_the_category();
$cat_name = $cat[0]->name;
if ($cat_name == "Content Suggestions") {
	$cat_name = 'Content';
}
elseif ($cat_name == "Feature Ideas") {
	$cat_name = 'Features';
}
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">			
			<div id="content">
				<h3 class="page-title">Neighborhow Feedback &#8212; <?php echo $cat_name;?></h3>
				<div class="intro-block">Help make Neighborhow better by telling us about the features and content you&#39;d like to see. Vote on ideas in this category, or give us your own feedback.</div>
					
				<div id="list-feedback">
					<div class="intro-block-button"><a id="addfdbk" class="nh-btn-green" href="<?php echo $app_url;?>/add-feedback" rel="tooltip" data-placement="bottom" data-title="<strong>Please sign in before giving feedback.</strong>">Give Feedback</a></div>
					<ul class="list-feedback">	

<?php 
	$fdbk_sub_cat = get_cat_ID($cat[0]->name);
	$fdbk_sub_args = array(
		'post_status' => 'publish',
		'cat' => $fdbk_sub_cat
	);
	$fdbk_sub_query = new WP_Query($fdbk_sub_args);	

	$sql2 = "SELECT SUM(vote) as total,nh_posts.ID from nh_posts LEFT JOIN nh_wdpv_post_votes ON nh_posts.ID=nh_wdpv_post_votes.post_id WHERE nh_posts.ID = '.$post->ID.' ORDER BY total DESC";
	$my_posts = $wpdb->get_results($sql2, ARRAY_A);
	print_r($my_posts);

		
	if (!$fdbk_sub_query->have_posts()) : ?>
		<li>Looks like there&#39;s no feedback in this category yet. Give your feedback!</li>
<?php else: ?>
<?php while($fdbk_sub_query->have_posts()) : $fdbk_sub_query->the_post();?>
		
			<li class="list-vote" id="post-<?php echo $post->ID; ?>">
				
				
				
				<div class="vote-box"><?php wdpv_vote(false); ?>
				</div>
				<div class="vote-question"><strong><a href="<?php the_permalink();?>" title=""><?php the_title();?></a></strong>
					<!--p>
<?php 
//$tmp = get_the_excerpt();
//$link = nh_continue_reading_link();
//$excerpt = trim_by_words($tmp,14,$link);
//echo $excerpt;?>
					</p-->
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
<?php endif;
wp_reset_query(); ?>								
		</ul>			
					</div><!-- / list-feedback-->
			</div><!--/ content-->
<?php get_sidebar('feedback');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
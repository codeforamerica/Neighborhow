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
				<div class="intro-block">Help make Neighborhow better by telling us about the features and content you&#39;d like to see. Vote on these popular ideas, explore all the feedback, or give us your own feedback.</div>
					
				<div id="list-feedback">
					<div class="intro-block-button"><a id="addfdbk" class="nh-btn-green" href="<?php echo $app_url;?>/add-feedback" rel="tooltip" data-placement="bottom" data-title="<strong>Please sign in before giving feedback.</strong>">Give Feedback</a></div>
						<ul class="list-feedback">
<?php
$fdbk_cat = get_cat_ID('feedback');
$fdbk_args = array(
	'post_status' => 'publish',
	'cat' => $fdbk_cat
);
$fdbk_query = new WP_Query($fdbk_args);	
if (!$fdbk_query->have_posts()) : ?>

	<li>Looks like there&#39;s no feedback yet. Add your ideas or questions!</li>

<?php else: ?>
<?php while($fdbk_query->have_posts()) : $fdbk_query->the_post();?>

		<li class="list-vote" id="post-<?php echo $post->ID; ?>">
			<div class="vote-btn">
<?php 
if (nh_user_has_voted_post($current_user->ID, $post->ID)) {
	echo '<span class="byline"><a id="votedthis" title="See your other Votes" href="'.$app_url.'/author/'.$current_user->user_login.'" class="votedthis nhline">You voted</a></span>';
}
else {
	nh_vote_it_link();
}							
?>
			</div>
			<div class="vote-question"><strong><a href="<?php the_permalink();?>" title=""><?php the_title();?></a></strong>
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
			</div><!--/ vote question-->
			<div class="nh-vote-count"><span class="nh-vote-count  vote-<?php echo $post->ID;?>">
<?php echo nh_get_vote_count($post->ID);?></span>
<br/><span class="small-vote">votes</span>
				</div>											
			</li>
<?php endwhile; ?>			
<?php endif; 
wp_reset_query();?>								
<?php
//} // end if count < 0
?>
		</ul>
					</div><!-- / list-feedback-->
			</div><!--/ content-->
<?php get_sidebar('feedback');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
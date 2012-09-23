<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
$cat = get_the_category();
$cat_name = $cat[0]->name;
if ($cat_name == "Content") {
	$cat_name = 'Content';
}
elseif ($cat_name == "Features") {
	$cat_name = 'Features';
}
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">			
			<div id="content">
				<h3 class="page-title">Ideas + Suggestions &#8212; <?php echo $cat_name;?></h3>
				<div class="intro-block"><p>Help make Neighborhow better by telling us about the content and features you want.</p><p>Voting on ideas and questions from other people is a good way to help us understand what&#39;s most important to you. But if you don&#39;t see your idea on the list, go ahead and add your own idea!</p></div>
					
				<div id="list-fdbk">
					<div class="intro-block-button"><a id="addfdbk" class="nh-btn-green" href="<?php echo $app_url;?>/add-idea" rel="tooltip" data-placement="bottom" data-title="Please sign in before <br/>adding your idea.">Add Your Idea</a></div>
					<ul class="list-fdbk">	

<?php 
	$fdbk_sub_cat = get_cat_ID($cat[0]->name);
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$vote_sub_args = array(
		'post_status' => 'publish',
		'cat' => $fdbk_sub_cat,
//		'orderby' => 'meta_value_num',
		'orderby' => 'date',		
		'order' => DESC,
		'meta_key' => '_nh_vote_count',
		'posts_per_page' => '10',
		'paged' => get_query_var('paged')
	);
	$fdbk_sub_query = new WP_Query($vote_sub_args);	
		
	if (!$fdbk_sub_query->have_posts()) : ?>
		<li>Looks like there are no ideas in this category yet. Add your ideas or questions!</li>
<?php else: ?>
<?php while($fdbk_sub_query->have_posts()) : $fdbk_sub_query->the_post();?>
		
			<li class="fdbk-list" id="post-<?php echo $post->ID; ?>">
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
				<div class="vote-question"><strong><a class="nhline" href="<?php the_permalink();?>" title="View <?php echo the_title();?>"><?php the_title();?></a></strong>
					<p class="comment-meta"><span class="byline"><?php comments_number( '', '1 comment', '% comments' ); ?></span></p>
					<p class="comment-meta"><span class="byline">in </span>
<?php 
$category = get_the_category(); 
foreach ($category as $cat) {
	echo '<a class="nhline" href="'.$app_url.'/ideas/'.$cat->slug.'" title="View ideas in '.$cat->name.'">';
	echo $cat->name;
	echo '</a>';
}
?>
					</p>													
				</div>
				<div class="nh-vote-count"><span class="nh-vote-count  vote-<?php echo $post->ID;?>">
<?php echo nh_get_vote_count($post->ID);?></span>
<br/><span class="small-vote">votes</span>
				</div>						
			</li>
<?php endwhile; ?>		
<?php endif;
$big = 999999999; // need an unlikely integer
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages
) );

wp_reset_query(); ?>								
		</ul>			
					</div><!-- / list-feedback-->
			</div><!--/ content-->
<?php get_sidebar('ideas');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
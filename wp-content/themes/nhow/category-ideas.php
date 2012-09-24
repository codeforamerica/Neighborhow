<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
$cat = get_the_category();
?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">			
			<div id="content">
				<h3 class="page-title">Ideas + Suggestions</h3>
				<div class="intro-block"><p>Help make Neighborhow better by telling us about the content and features you want.</p><p>We gathered these ideas from an online questionnaire we conducted earlier in the year. Voting on these ideas is a good way to help us understand what&#39;s most important to you. But if you don&#39;t see your idea on the list, go ahead and add it!</p></div>
					
				<div id="list-fdbk">
					<div class="intro-block-button"><a id="addfdbk" <?php if (!is_user_logged_in()) {echo ' rel="tooltip"';
	echo ' data-placement="bottom"';
	echo ' data-title="You\'ll need to sign in--or sign up--before you can add your idea."';
}
;?> alt="Add Your Idea" class="nh-btn-green" href="<?php echo $app_url;?>/add-idea">Add Your Idea</a></div>
						<ul class="list-fdbk">
<?php
$fdbk_cat = get_cat_ID('ideas');
//echo $fdbk_cat;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$vote_args = array(
	'post_status' => 'publish',
	'cat' => $fdbk_cat,
//	'orderby' => 'meta_value_num',
	'orderby' => 'date',	
	'order' => DESC,
	'meta_key' => '_nh_vote_count',
	'posts_per_page' => '20',
//	'paged' => get_query_var('paged')
	'paged' => $paged	
);
$fdbk_query = new WP_Query($vote_args);	

if (!$fdbk_query->have_posts()) : ?>
		<li class="fdbk-list">Looks like there are no ideas yet. Add your ideas or questions!</li>
<?php else: ?>
<?php while($fdbk_query->have_posts()) : $fdbk_query->the_post();?>

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

$nh_cities = get_terms('nh_cities');

$idea_city = get_post_meta($post->ID,'nh_idea_city',true);
$term = term_exists($idea_city, 'nh_cities');

// If idea city is an official city
if ($term !== 0 && $term !== null) {
	$term_id = $term['term_id'];
	$term_data = get_term_by('id',$term_id,'nh_cities');
	echo ' + <a href="'.$app_url.'/cities/'.$term_data->slug.'" title="View '.$idea_city.'">'.$idea_city.'</a>';
}
elseif ($term == 0 && $term == null) {
	echo ' + '.$idea_city;
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
$big = 999999999; // need an unlikely integer
echo paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $wp_query->max_num_pages
) );

wp_reset_query();
?>							
		</ul>
					</div><!-- / list-feedback-->
			</div><!--/ content-->
<?php get_sidebar('ideas');?>
		</div><!--/ main-->
	</div><!--/ wrapper-->
</div><!--/ row-content-->
<?php get_footer(); ?>
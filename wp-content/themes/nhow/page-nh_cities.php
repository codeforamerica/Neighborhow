<?php /* Template Name: page-nh_cities */ ?>
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

				<h3 class="page-title">Neighborhow Cities</h3>
	
				<div id="list-ideas">
					<ul class="list-ideas">			
<?php 
// Get the official cities
$guide_cat = get_category_id('guides');

$city_args = array(
	'orderby' => 'name',
	'order' => 'ASC'
);
$cities = get_terms('nh_cities',$city_args);
foreach ($cities as $city) {

// get guide count per city per guide cat
$myquery = array(
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'category',
			'field' => 'id',
			'terms' => array($guide_cat)
		),
		array(
			'taxonomy' => 'nh_cities',
			'field' => 'slug',
			'terms' => array($city->slug)
		)
	)
);

$city_guides = query_posts($myquery);
$count_city_guides = count($city_guides);

// get user count per city
	$users = $wpdb->get_results("SELECT * from nh_usermeta where meta_value = '".$city->name."' AND meta_key = 'user_city'");		
	$users_count = count($users);
	
// get idea count per city
	$ideas = $wpdb->get_results("SELECT * from nh_postmeta where meta_value = '".$city->name."' AND meta_key = 'nh_idea_city'");		
	$ideas_count = count($ideas);	

// show results	
	echo '<li class="nhline" style="margin:.75em 0 .75em 0;border-top:1px solid #ccc;padding-top:1em;">';
//	echo 'guide cat :'.$guide_cat.' city: '.$city->slug.' count: '.$count_city_guides.'<br/>';
	echo '<a class="nhline" href="'.$app_url.'/cities/'.$city->slug.'" title="View all Neighborhow content for '.$city->name.'">'.$city->name.'</a>';
	if ($posts) {
		if ($count_city_guides == '1') {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$count_city_guides.'&nbsp;Guide</span></span>';
		}
		elseif ($count_city_guides > 1) {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$count_city_guides.'&nbsp;Guides</span></span>';
		}
	}
	
	if ($ideas) {
		if ($ideas_count == '1') {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$ideas_count.'&nbsp;Idea</span></span>';
		}
		elseif ($ideas_count > 1) {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$ideas_count.'&nbsp;Ideas</span></span>';
		}
	}
		
	if ($users) {
		if ($users_count == '1') {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$users_count.'&nbsp;User</span></span>';
		}
		elseif ($users_count > 1) {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$users_count.'&nbsp;Users</span></span>';
		}
	}
	echo '</li>';
}
?>
					</ul>			
				</div>
								
			</div><!--/ content-->
<?php get_sidebar('misc');?>			
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
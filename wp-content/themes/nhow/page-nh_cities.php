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
				<div class="intro-block noborder"><p>how it works - sign up, get 20 peoplet o sign up, get 1 person to write a guide, and we'll get in touch with your city and orgs to kick it off</p>
				</div>
	
				<div id="list-ideas">
					<ul class="list-ideas">			
<?php 
// Get the official cities
$city_args = array(
	'orderby' => 'name',
	'order' => 'ASC'
);
$cities = get_terms('nh_cities',$city_args);
foreach ($cities as $city) {

// get post count per city
	global $wpdb, $post;
	$posts = $wpdb->get_results("SELECT nh_posts.ID from nh_posts 
		LEFT JOIN nh_term_relationships 
		ON nh_posts.ID=nh_term_relationships.object_id 
		LEFT JOIN nh_term_taxonomy 
		ON nh_term_relationships.term_taxonomy_id=nh_term_taxonomy.term_id 
		LEFT JOIN nh_terms 
		ON nh_term_taxonomy.term_id=nh_terms.term_id 
		WHERE nh_terms.slug = '".$city->slug."' 
		AND nh_posts.post_status = 'publish'");		
	$posts_count = count($posts);

// get user count per city
	$users = $wpdb->get_results("SELECT * from nh_usermeta where meta_value = '".$city->name."'");		
	$users_count = count($users);
	
	echo '<li class="nhline" style="margin:.75em 0 .75em 0;border-top:1px solid #ccc;padding-top:1em;">';
	echo '<a class="nhline" href="'.$app_url.'/cities/'.$city->slug.'" title="View all Neighborhow content for '.$city->name.'">'.$city->name.'</a>';
	if ($posts) {
		if ($posts_count == '1') {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$posts_count.'&nbsp;Guide</span></span>';
		}
		elseif ($posts_count > 1) {
			echo '<span class="meta"><span class="byline">&nbsp;&nbsp;&#8226;&nbsp;&nbsp;'.$posts_count.'&nbsp;Guides</span></span>';
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
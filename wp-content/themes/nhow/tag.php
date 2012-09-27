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
				
				<h3 class="page-title"><?php echo single_tag_title();?></h3>
				<div id="list-fdbk">
					<ul class="list-fdbk">
<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$tag_args = array(
	'post_status' => 'publish',
	'tag_id' => $tag_id,
	'orderby' => 'date',	
	'order' => DESC,
	'posts_per_page' => '20',
	'paged' => $paged	
);
$tag_query = new WP_Query($tag_args);
if (!$tag_query->have_posts()) :
?>	
	<li class="fdbk-list">Sorry, there is no public content matching this Topic right now.</li>

<?php else: ?>	

<?php while($tag_query->have_posts()) : $tag_query->the_post();?>	
	<li class="fdbk-list" id="post-<?php echo $post->ID; ?>"><strong><a href="<?php echo get_permalink();?>" title="View <?php echo the_title();?>"><?php echo the_title();?></a></strong>
		<div class="search-results">
<?php 
$tmp = get_the_content();
$new_content = strip_tags($tmp,'<p>');
$content_trimmed = trim_by_words($new_content,'20',nh_continue_reading_link());
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
<?php
// If official post cities
$post_cities = wp_get_post_terms($post->ID,'nh_cities');
if ($post_cities) {
	$count = count($post_cities);
	$j = $count - 1;	

	echo ' + ';
	for ($i=0; $i<=$j; $i++) {
		$city = $post_cities[$i]->name;
		$city_slug = strtolower($city);
		$city_slug = str_replace(' ','-',$city_slug);
		
		if ($count == 1) {
			echo '<a href="'.$app_url.'/cities/'.$city_slug.'" title="See all topics in '.$city.'">'.$city.'</a>';
		}
		elseif ($count > 1 AND $i < $j) {
			echo '<a href="'.$app_url.'/cities/'.$city_slug.'" title="See all topics in '.$city.'">'.$city.'</a>, ';
		}			
		elseif ($count > 1 AND $i == $j) {
				echo '<a href="'.$app_url.'/cities/'.$city_slug.'" title="See all topics in '.$city.'">'.$city.'</a>';
		}
	}
}
// Dont show if not official post cities
/*
elseif (!$post_cities) {
	$user_guide_cities = get_post_meta($post->ID,'gde-user-city',true);
	$clean = str_replace(',',', ',$user_guide_cities);
	$cities = explode(',',$clean);
	$count = count($cities);
	$j = $count - 1;
	
	echo ' + ';	
	for ($i=0; $i<=$j; $i++) {
		$city = $cities[$i];
		if ($count == 1) {
			echo $city;
		}
		elseif ($count > 1 AND $i < $j) {
			echo $city.', ';
		}			
		elseif ($count > 1 AND $i == $j) {
			echo $city;				
		}
	}
}
*/
?>	
			</p>
		</div>

<?php endwhile; 

$total_pages = $tag_query->max_num_pages;
if ($total_pages > 1){
  $current_page = max(1, get_query_var('paged'));
  echo paginate_links(array(
      'base' => get_pagenum_link(1) . '%_%',
      'format' => '/page/%#%',
      'current' => $current_page,
      'total' => $total_pages,
    ));
}
wp_reset_query();
endif;
?>
					</ul>
				</div>

			</div><!--/ content-->
<?php get_sidebar('misc');?>
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
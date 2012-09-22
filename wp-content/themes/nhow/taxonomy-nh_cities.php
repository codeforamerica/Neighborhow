<?php get_header(); ?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php
$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );?>
<div class="row-fluid row-content">	
	<div class="wrapper">
		<div id="main">
			<div id="row-fluid">
				<div class="span8">	
					<h3 class="page-title"><?php echo $term->name;?></h3>
					<div class="intro-block noborder"><p>how it works - sign up, get 20 peoplet o sign up, get 1 person to write a guide, and we'll get in touch with your city and orgs to kick it off</p>
					</div>
				</div>
				<div class="span4 sidebar-faux">
					<div class="sidebar-button-panel">
						<a class="btns" href="<?php echo $app_url;?>/add-idea" rel="tooltip" data-title="Contact Us -- Get Neighborhow for your city."><button class="nh-btn-blue btn-fixed-small" data-placement="top">Get Neighborhow</button></a>

						<a class="btns" href="<?php echo $app_url;?>/create-guide" rel="tooltip" data-title="Share Your Neighborhow -- Create a Guide and share what you know with others." data-placement="top"><button class="nh-btn-blue btn-fixed-small">Create a Guide</button></a>	
						<!--a class="btns" href="<?php //echo $app_url;?>/create-guide" rel="tooltip" data-title="Friends -- Ask a friend to write a Guide." data-placement="top"><button class="nh-btn-blue btn-fixed-small">Tell a Friend</button></a-->			
					</div><!--/ widget-->	
				</div>
			</div><!-- /row-fluid-->
			<div class="content-full">
				<div id="list-city">
					<h5 class="widget-title widget-title-city">Neighborhow Guides for <?php echo $term->name;?></h5>
					<ul class="list-city">
												
<?php 
$city_args = array(
	'post_type' => array('post'), //include projects
	'posts_per_page' => '-1',
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
	'nh_cities' => $term->slug,
	'posts_per_page' => '12',
	'paged' => get_query_var('paged')
);
$city_query = new WP_Query($city_args);
if ($city_query->have_posts()) : 
while($city_query->have_posts()) : $city_query->the_post();
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');	
?>
<li class="city-list" id="post-<?php echo $post->ID;?>"><a class="nhline link-other" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=180&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />
	<div class="home-caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
		<p><?php echo $pic_title;?></a></p>
	</div>	
</li>
<?php 
endwhile; 
?>
<?php else : ?>
<li class="city-list" id="nopost">Sorry, there is no public information available yet for <?php echo $term->name;?></li>
					</ul>
<?php
endif;
wp_reset_query();					 
?>								
				</div><!--/ list city-->	
				
				<div id="list-people">
					<h5 class="widget-title">Neighborhow People in <?php echo $term->name;?></h5>
					<ul class="list-people">								
<?php 
// Get users whose user_city = term name
//echo $term->name.'<br/>';
$user_args = array(
	'meta_key' => 'user_city',
	'meta_value' => $term->name,
	'orderby' => 'post_count'
 );
$users = get_users($args);
if ($users) {
	foreach ($users as $user) {
		$user_data = get_userdata($user->ID);
		// get likes count
		$user_likes = get_user_meta($user->ID,'nh_li_user_loves',true);
		$user_likes_count = count($user_likes);
		// get comment count
		$comment_args = array('user_id' => $user->ID);   
		$comments = get_comments($comment_args);
		$user_comments_count = count($comments);
		// get vote count
		$user_votes = get_user_meta($user->ID,'nh_user_votes',true);
		$user_votes_count = count($user_votes);
		// get content count
		$user_content_count = nh_get_user_posts_count($user->ID,array(
			'post_status'=> 'publish'
		));
		$user_content_count;
			
		$user_name = $user_data->first_name.' '.$user_data->last_name;
		$user_avatar = get_avatar($user->ID,'96','identicon','');		
		echo '<li class="people-list">';
		echo '<a href="'.$app_url.'/author/'.$user->user_login.'" class="cityuser" rel="tooltip" data-placement="top" data-title="<strong>'.$user_name.'</strong><br/>';
		if ($user_content_count) {
			echo $user_content_count.'&nbsp;content &nbsp;&#8226;&nbsp; ';
		}
		if ($user_likes_count) {
			echo $user_likes_count.'&nbsp;likes &nbsp;&#8226;&nbsp; ';
		}
		if ($user_comments_count) {
			echo $user_comments_count.'&nbsp;comments &nbsp;&#8226;&nbsp; ';
		}
		if ($user_votes_count) {
			echo $user_votes_count.'&nbsp;votes';
		}
 		echo '">'.$user_avatar.'</a>';
		echo '</li>';
	}
}
elseif (!$users) {
	echo '<li class="people-list" id="nopost">Currently there are no Neighborhow users from '.$term->name.'.</li>';
}
wp_reset_query();					 
?>				
</ul>				
				</div><!--/ list people-->				
							
			</div><!--/ content-full -->
		
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
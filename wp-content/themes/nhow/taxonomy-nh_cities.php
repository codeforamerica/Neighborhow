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
<?php
if ($term->name == 'Any City') :
?>					
					<div class="intro-block noborder"><p>Neighborhow users have said these Neighborhow Guides are applicable to any city. Try them out, and let us know!</p>
						<p>Or get started by <a href="<?php echo $app_url;?>/create-guide" title="Create a Neighborhow Guide">creating a Neighborhow Guide</a>. <!--or telling your friends about Neighborhow. Find out more about how Neighborhow works.--></p>
					</div>
<?php
else : // other cities
?>					
					<div class="intro-block noborder"><p>We&#39;ve created a few "city pages" like this one to showcase what&#39;s happening in a city. We hope you&#39;ll add more. Before we add a "city page" for your city, your city should have:
							<ol>
								<li>At least one Neighborhow Guide</li>
								<li>At least 20 people signed up with Neighborhow</li>
							</ol>
						</p>
						<p>Get started by <a href="<?php echo $app_url;?>/create-guide" title="Create a Neighborhow Guide">creating a Neighborhow Guide</a>. <!--or telling your friends about Neighborhow. Find out more about how Neighborhow works.--></p>
					</div>
<?php
endif; // end if Any City
?>					
				</div>
				<div class="span4 sidebar-faux">
					<div class="sidebar-button-panel">
						<a class="btns" href="<?php echo $app_url;?>/add-idea" rel="tooltip" data-title="Request Neighborhow -- Get Neighborhow for your city."><button class="nh-btn-blue btn-fixed-small" data-placement="top">Get Neighborhow</button></a>

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
//	'posts_per_page' => '-1',
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
	'nh_cities' => $term->slug, //the city taxonomy
	'posts_per_page' => '12',
	'paged' => get_query_var('paged')
);
$city_query = new WP_Query($city_args);
if ($city_query->have_posts()) : 
while($city_query->have_posts()) : $city_query->the_post();
$imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');	
$post_cities = wp_get_post_terms($post->ID,'nh_cities');
$term = array_pop($post_cities);
?>
<li class="city-list" id="post-<?php echo $post->ID;?>"><a class="nhline link-other" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><img src="<?php echo $style_url;?>/lib/timthumb.php?src=<?php echo $imgSrc[0];?>&w=184&h=115&zc=1&at=t" alt="Photo from <?php echo the_title();?>" />
	<div class="home-caption">
<?php
$pad = ' ...';
$pic_title = trim_by_chars(get_the_title(),'60',$pad);
?>
		<p><?php echo $pic_title;?></a></p>
<?php
if ($term->name) {
echo '<p class="city-caption">'.$term->name.'</p>';	
}
else {
	echo '<p class="city-caption">Any City</p>';
}
?>		
	</div>	
</li>
<?php 
endwhile; 
?>
<?php else : ?>
<li style="margin-left:1.5em !important;border:none;width:100%;" class="city-list" id="nopost">There are no public Neighborhow Guides for <?php echo $term->name;?>. <a href="'.$app_url.'/create-guide" title="Create a Neighborhow Guide">Create one!</a></li>
					</ul>
<?php
endif;
wp_reset_query();					 
?>								
				</div><!--/ list city-->
				
				<div id="list-ideas-city">
<?php
if ($term->name != 'Any City') :
?>					
					<h5 class="widget-title">Neighborhow Ideas for <?php echo $term->name;?></h5>
					<ul class="list-ideas-city">
												
<?php 
$idea_cat = get_category_id('ideas');
$idea_args = array(
	'post_type' => array('post'), //include projects
	'post_status' => 'publish',
	'orderby' => 'date',
	'order' => 'DESC',
	'cat' => $idea_cat,
	'meta_key' => 'nh_idea_city',
	'meta_value' => $term->name,	
	'posts_per_page' => '-1',
	'paged' => get_query_var('paged')
);
$idea_query = new WP_Query($idea_args);
if ($idea_query->have_posts()) : 
while($idea_query->have_posts()) : $idea_query->the_post();	
?>
<li class="idea-city-list" id="post-<?php echo $post->ID;?>"><a class="nhline" rel="bookmark" title="See <?php echo the_title();?>" href="<?php the_permalink();?>"><?php echo the_title();?></a>	
</li>
<?php 
endwhile; 
?>
<?php else : ?>
<li style="margin-left:1.5em !important;border:none;width:100%;" class="idea-list" id="nopost">There are no Neighborhow Ideas yet for <?php echo $term->name;?>. <a href="'.$app_url.'/add-idea" title="Add Your Idea">Add your own idea!</a></li>
					</ul>
<?php
endif;
wp_reset_query();
endif; // end if Any City					 
?>								
				</div><!--/ list ideas-->
				
				<div id="list-people">
<?php
if ($term->name != 'Any City') :
?>					
					<h5 class="widget-title">Neighborhow People in <?php echo $term->name;?></h5>
					<ul class="list-people">								
<?php 
// Get users whose user_city = term name
$users = $wpdb->get_results("SELECT * from nh_usermeta where meta_value = '".$term->name."' AND meta_key = 'user_city'");		
$users_count = count($users);

if ($users) {
	foreach ($users as $user) {
		$user_data = get_userdata($user->user_id);
		
		$user_name = $user_data->first_name.' '.$user_data->last_name;
		$user_dataar = get_avatar($user_data->ID,'72','identicon','');
			
		echo '<li class="people-list">';
		echo '<a href="'.$app_url.'/author/'.$user_data->user_login.'" class="cityuser" rel="tooltip" data-placement="top" data-title="<strong>'.$user_name.'</strong><br/>';
		
//		$user_content_count = count_user_posts_by_type($user_data->ID);
		
		$user_content_count = nh_get_user_posts_count($user_data->ID,array(
			'post_type' =>'post',
			'post_status'=> 'publish',
			'posts_per_page' => -1
			));
		if ($user_content_count) {
			if ($user_content_count == '1') {
				echo $user_content_count.'&nbsp;article';
			}
			elseif ($user_content_count > 1) {
				echo $user_content_count.'&nbsp;articles';
			}
		}
		$user_likes = get_user_meta($user_data->ID,'nh_li_user_loves');
		foreach ($user_likes as $like) {
			$user_likes_count = count($like);
			if ($user_likes_count) {
				if ($user_likes_count == '1') {
					echo ' &nbsp;&#8226;&nbsp; '.$user_likes_count.'&nbsp;like';
				}
				elseif ($user_likes_count > 1) {
					echo ' &nbsp;&#8226;&nbsp; '.$user_likes_count.'&nbsp;likes';
				}
			}
		}
		$comment_args = array('user_id' => $user_data->ID);   
		$comments = get_comments($comment_args);
		$user_comments_count = count($comments);
		if ($user_comments_count) {
			if ($user_comments_count == '1') {
				echo ' &nbsp;&#8226;&nbsp; '.$user_comments_count.'&nbsp;comment';
			}
			elseif ($user_comments_count > 1) {
				echo ' &nbsp;&#8226;&nbsp; '.$user_comments_count.'&nbsp;comments';
			}
		}
		
		$user_votes = get_user_meta($user_data->ID,'nh_user_votes');
		foreach ($user_votes as $vote) {
			$user_votes_count = count($vote);
			if ($user_votes_count) {
				if ($user_votes_count == '1') {
					echo ' &nbsp;&#8226;&nbsp; '.$user_votes_count.'&nbsp;vote';
				}
				elseif ($user_votes_count > 1) {
					echo ' &nbsp;&#8226;&nbsp; '.$user_votes_count.'&nbsp;votes';
				}
			}
		}
		echo '">'.$user_avatar.'</a>';
		echo '</li>';
	}
}
elseif (!$users) {
	echo '<li style="margin-left:1.5em !important;border:none;width:100%;" class="people-list" id="nopost">There are no Neighborhow people from '.$term->name.' yet. <a href="'.$app_url.'/register" title="Sign up for Neighborhow">Sign up!</a></li>';
}
wp_reset_query();					 
?>				
</ul>	
<?php
endif; // end if Any City
?>			
				</div><!--/ list people-->				
							
			</div><!--/ content-full -->
		
		</div><!--/ main-->
	</div><!--/ content-->
</div><!--/ row-content-->
<?php get_footer(); ?>
<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Latest Neighborhow Ideas</h5>
		<div class="recent-fdbk">
			<p style="font-size:90%;margin:.85em 0 1.5em 0;text-align:right;"><a id="addfdbk" rel="tooltip" data-placement="bottom" class="nh-btn-blue" href="<?php echo $app_url;?>/add-idea" data-title="You'll need to sign in--or sign up--before you can add your idea." title="You'll need to sign in--or sign up--before you can add your idea.">Add Your Idea</a></p>
			<ul>
<?php
$fdbk_sub_cat = get_cat_ID('content');
$fdbk_sub_args = array(
	'post_status' => 'publish',
	'cat' => $fdbk_sub_cat,
	'orderby' => 'date',
	'order' => DESC,
	'posts_per_page' => '5'
);
$fdbk_sub_query = new WP_Query($fdbk_sub_args);
if ($fdbk_sub_query->have_posts()) :
while($fdbk_sub_query->have_posts()) :
$fdbk_sub_query->the_post();	
?>					
				<li class="list-fdbk" id="post-<?php echo $post->ID; ?>"><a class="nhline" href="<?php echo get_permalink();?>" title="View <?php echo the_title();?>"><?php echo the_title();?></a>&nbsp;&nbsp;<span class="meta meta-small"><span class="byline">added</span> <?php echo get_the_date();?></span></span>

<!--br/><span class="meta meta-small"><span class="byline">from</span> 
<?php 
//global $post;
//$auth_id = $post->post_author;
//$author = get_user_by('id',$auth_id);
?>
<a class="nhline" href="<?php //echo $app_url;?>/author/<?php //echo $author->user_login;?>" title="View <?php //echo $author->first_name.' '.$author->last_name;?>'s profile"><?php //echo $author->first_name.' '.$author->last_name;?></a></span-->	
				</li>
<?php 
endwhile;
endif;
?>								
				<li class="list-fdbk" style="text-align:right;border-bottom:none;"><a class="nhline" href="<?php echo $app_url;?>/ideas/content" title="See all the ideas">See all the ideas</a></li>
			</ul>	
		</div><!--/ recent fdbk-->
	</div><!--/ widget side-->
	
	<div class="widget-side">
		<h5 class="widget-title">Help Make Neighborhow Better</h5>
		<div class="widget-copy">
			<ul class="bullets">
				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/submit-story" title="Share your story">Share Your Story</a> &#8212; We&#39;ll feature stories about how you&#39;ve improved your neighborhood.</li>
				
				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/ideas" title="Share your story">Join the conversation</a> &#8212; Help us decide what content and features to include.</li>
				
				<li class="bullets"><a class="nhline" href="<?php echo $app_url;?>/contact" title="Share your story">Contact us</a> &#8212; If you&#39;re a city or organization who wants Neighborhow in your city.</li>												
			</ul>
		</div>
	</div><!--/ widget-->

</div><!--/ sidebar-->
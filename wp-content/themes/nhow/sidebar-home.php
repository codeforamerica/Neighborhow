<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">New Ideas for Neighorhow Guides</h5>
		<div class="recent-fdbk">
			<p style="margin:.5em 0 1.5em 0;text-align:right;"><a id="addfdbk" class="nh-btn-green" href="<?php echo $app_url;?>/add-feedback" rel="tooltip" data-placement="bottom" data-title="<strong>Please sign in before adding your idea.</strong>">Add Your Idea</a></p>
			<ul>
<?php
$fdbk_sub_cat = get_cat_ID('content suggestions');
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
				<li class="list-fdbk" id="post-<?php echo $post->ID; ?>"><a href="<?php echo get_permalink();?>" title="View <?php echo the_title();?>"><?php echo the_title();?></a>&nbsp;&nbsp;<span class="meta meta-small"><span class="byline">added</span> <?php echo get_the_date();?></span></span>

<!--br/><span class="meta meta-small"><span class="byline">from</span> 
<?php 
global $post;
$auth_id = $post->post_author;
$author = get_user_by('id',$auth_id);
?>
<a href="<?php echo $app_url;?>/author/<?php echo $author->user_login;?>" title="View <?php echo $author->first_name.' '.$author->last_name;?>'s profile"><?php echo $author->first_name.' '.$author->last_name;?></a></span-->	
				</li>
<?php 
endwhile;
endif;
?>								
				<li class="list-fdbk" style="text-align:right;border-bottom:none;"><a href="<?php echo $app_url;?>/feedback/content" title="View all ideas">See all the ideas</a></li>
			</ul>	
		</div><!--/ recent fdbk-->
	</div><!--/ widget side-->

<?php //include(STYLESHEETPATH.'/include_make_better.php');?>

<?php
if (!is_user_logged_in()) :
?>
<?php include(STYLESHEETPATH.'/include_signin.php');?>				
<?php
endif;
?>
						
<?php //include(STYLESHEETPATH.'/include_about_nhow.php');?>

</div><!--/ sidebar-->
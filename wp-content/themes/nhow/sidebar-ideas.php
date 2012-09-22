<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
get_currentuserinfo();
$auth_id = $post->post_author;

?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Explore Ideas + Suggestions</h5>
		<div class="widget-copy">
			<ul class="bullets">
<?php
$fdbk_cat = get_cat_ID('ideas');
$args = array('child_of' => $fdbk_cat);
$categories = get_categories($args);
foreach ($categories as $cat) {
// only show sub-cats with posts
?>				
				<li class="bullets">
<?php
if ($cat->slug == 'content') {
	echo '<a class="nhline" href="'.$app_url.'/ideas/'.$cat->slug.'" title="View '.$cat->name.'">Content suggestions for Neighborhow Guides</a>';
}
elseif ($cat->slug == 'features') {
	echo '<a class="nhline" href="'.$app_url.'/ideas/'.$cat->slug.'" title="View '.$cat->name.'">Ideas about Features</a>';
}
elseif ($cat->slug == 'questions') {
	echo '<a class="nhline" href="'.$app_url.'/ideas/'.$cat->slug.'" title="View '.$cat->name.'">Questions from users</a>';
}
?>					
</li>
<?php } ?>				
			</ul>
		</div>			
	</div><!--/ widget-->
	
	<div class="widget-side">
		<h5 class="widget-title">Show Your Neighborhow</h5>
		<div class="widget-copy">
			<ul class="bullets">				
				<li class="bullets">
<a class="nhline" href="<?php echo $app_url;?>/create-guide" title="Create a Neighborhow Guide">Create a Guide</a> &#8212; share your knowledge
				</li>	
				<li class="bullets">
				<a class="nhline" href="<?php echo $app_url;?>/submit-story" title="Submit a story">Submit a story</a> &#8212; share something you&#39;ve done to improve your neighborhood				
				</li>
			
			</ul>
		</div>			
	</div><!--/ widget-->		

</div><!--/ sidebar-->
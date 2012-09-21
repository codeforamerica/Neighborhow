<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');

global $current_user;
get_currentuserinfo();
$auth_id = $post->post_author;

?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<div class="widget-copy">
			<div class="intro-block-button"><a id="" class="nh-btn-green" href="<?php echo $app_url;?>/xcvxv" title="Please sign in before adding your idea.">Suggest a Guide Topic</a></div>

					<div class="intro-block-button"><a id="" class="nh-btn-green" href="<?php echo $app_url;?>/xcvxv" title="Please sign in before adding your idea.">Create a Guide</a></div>

					<div class="intro-block-button"><a id="" class="nh-btn-green" href="<?php echo $app_url;?>/xcvxv" title="Please sign in before adding your idea.">Tell a Friend</a></div>
		</div>			
	</div><!--/ widget-->	

</div><!--/ sidebar-->
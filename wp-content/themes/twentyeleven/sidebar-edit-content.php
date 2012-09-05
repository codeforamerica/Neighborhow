<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
	<div class="widget-side">
		<h5 class="widget-title">Some links for authors</h5>
		<div class="widget-copy">
			<ul>
<?php if (is_page('edit-guide')) {;?>				
				<li>
<?php 
echo do_shortcode('[frm-entry-links id=9  field_key=guide-title logged_in=1 edit=1]'); 
?>
				</li>		
<?php } ?>									
				<li><a href="<?php echo $app_url;?>/lostpwd" title="Forgot password">Link Two</a></li>
			</ul>
		</div>			
	</div><!--/ widget-->

</div><!--/ sidebar-->
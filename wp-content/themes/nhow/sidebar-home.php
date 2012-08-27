<?php
$style_url = get_bloginfo('stylesheet_directory');
$app_url = get_bloginfo('url');
?>
<div id="sidebar-nh" class="sidebar-nh">
<?php include(STYLESHEETPATH.'/include_make_better.php');?>
						
<?php include(STYLESHEETPATH.'/include_about_nhow.php');?>

<?php
if (!is_user_logged_in()) :
?>
<?php include(STYLESHEETPATH.'/include_signin.php');?>				
<?php
endif;
?>

</div><!--/ sidebar-->
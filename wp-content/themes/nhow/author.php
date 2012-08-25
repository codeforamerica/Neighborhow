<?php get_header();?>
<?php
//get viewer
global $current_user;
get_currentuserinfo();
$viewer_id = $current_user->ID;
$viewer = get_userdata($viewer_id);

//get profile owner
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
//get id
$nhauthor_id = $curauth->ID;
//get data
$nhauthor = get_userdata($nhauthor_id);
//get some info
$nhauthor_name = $nhauthor->first_name.' '.$nhauthor->last_name;
$nhauthor_avatar_alt = 'Photo of '.$nhauthor_name;
$nhauthor_avatar = get_avatar($nhauthor_id, '72','',$nhauthor_avatar_alt);
?>
<div class="row-fluid row-breadcrumbs">
	<div id="nhbreadcrumb">
<?php nhow_breadcrumb(); ?>
	</div>
</div>
<?php 
if ($nhauthor_id == $viewer_id) {
	$welcome_txt = 'Hi, '.$nhauthor_name;
	$description_txt = $nhauthor->description;
}
else {
	$welcome_txt = $nhauthor_name;
	$description_txt = $nhauthor->description;
}
?>
<div class="row-fluid row-content">	
	<div id="main">
		<div id="content">
			<div class="profile-welcome" style="border:1px solid #ddd;padding:1em;">
				<p style="float:left;margin-right:1em;"><?php echo $nhauthor_avatar;?></p>
				<div class="profile-elements">
					<h3 class="page-title" style=""><?php echo $welcome_txt;?></h3>
					<p><?php ?></p>
					<p><?php echo $description_txt?></p>
				</div>
			</div><!--/ profile-welcome-->

			<div class="profile-posts" style="border:1px solid #ddd;">
				<div class="feat-container">sdfkjsdlkj	
				</div><!--/ feat-container-->	

			</div><!--/ profile-posts-->

		</div><!--/ content-->
<?php
if ($nhauthor_id == $viewer_id) {
	get_sidebar('login-profile');
?>			
<?php
} else {
	get_sidebar('login-author');
}
?>
	</div><!--/ main-->
</div><!--/ row-content-->
<?php get_footer(); ?>		

<?php get_header();?>
<?php
// VIEWER
global $current_user;
get_currentuserinfo();
$nh_viewer_id = $current_user->ID;
$nh_viewer = get_userdata($nh_viewer_id);

// PROFILE OWNER
$curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

$nh_author_id = $curauth->ID;
$nh_author = get_userdata($nh_author_id);
$nh_author_name = $nh_author->first_name.' '.$nh_author->last_name;

//$nhauthor_avatar_alt = 'Photo of '.$nh_author_name;
//$nhauthor_avatar = get_avatar($nhauthor_id, '72','',$nhauthor_avatar_alt);
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
			<div class="author-welcome" style="border:1px solid #ddd;padding:1em;">
				<p style="float:left;margin-right:1em;">
<?php
$nh_avatar_alt = 'Photo of '.$nh_author_name;
$nh_avatar = get_avatar($nh_author_id, '96','',$nh_avatar_alt);
$nh_user_photo_url = nh_get_avatar_url($nh_avatar);
if ($nh_user_photo_url) {
	echo '<img alt="" src="'.$style_url.'/lib/timthumb.php?src='.$nh_user_photo_url.'&w=96&h=96&q=100&zc=1"><br/>';
	echo userphoto($posts[0]->post_author);

}
else {
	echo $nh_avatar.'<br/>';

}
?>					
				</p>
				<div class="author-elements">
					<h3 class="page-title" style=""><?php echo $welcome_txt;?></h3>
					<p><?php ?></p>
					<p><?php echo $description_txt?></p>
				</div>
			</div><!--/ profile-welcome-->

			<div class="author-posts" style="border:1px solid #ddd;">
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
